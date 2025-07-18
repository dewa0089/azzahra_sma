<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;
use App\Models\User;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Storage;

class PengembalianController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $statusOrder = ['Menunggu Persetujuan', 'Belum Dikembalikan', 'Disetujui'];

    $query = Pengembalian::with(['peminjaman.barang']);

    if (in_array($user->role, ['A', 'K'])) {
        // Filter nama_peminjam jika dikirim
        if ($request->filled('nama_peminjam')) {
            $query->whereHas('peminjaman', function ($q) use ($request) {
                $q->where('nama_peminjam', $request->nama_peminjam);
            });
        }

        $pengembalian = $query
            ->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "')")
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil user dengan role 'U' untuk dropdown
        $users = User::where('role', 'U')->orderBy('name')->get();

        return view('pengembalian.index', compact('pengembalian', 'users'));
    } else {
        $pengembalian = $query->whereHas('peminjaman', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengembalian.index', compact('pengembalian'));
    }
}

    public function edit($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.barang'])->findOrFail($id);
        return view('pengembalian.edit', compact('pengembalian'));
    }

   public function update(Request $request, $id)
{
    $validated = $request->validate([
        'jumlah_brg_baik' => 'required|integer|min:0',
        'jumlah_brg_rusak' => 'required|integer|min:0',
        'jumlah_brg_hilang' => 'required|integer|min:0',
        'tgl_pengembalian' => 'required|date',
        'keterangan' => 'nullable|string',
    ]);

    $pengembalian = Pengembalian::with(['peminjaman.barang'])->findOrFail($id);
    $jumlahDipinjam = $pengembalian->peminjaman->jumlah_peminjam;

    $totalDikembalikan = $validated['jumlah_brg_baik'] + $validated['jumlah_brg_rusak'] + $validated['jumlah_brg_hilang'];

    if ($totalDikembalikan > $jumlahDipinjam) {
        return back()->withErrors([
            'total_pengembalian' => 'Jumlah total pengembalian tidak sesuai dengan jumlah peminjaman yaitu ' . $jumlahDipinjam
        ])->withInput();
    }

    $pengembalian->update([
        'jumlah_brg_baik' => $validated['jumlah_brg_baik'],
        'jumlah_brg_rusak' => $validated['jumlah_brg_rusak'],
        'jumlah_brg_hilang' => $validated['jumlah_brg_hilang'],
        'tgl_pengembalian' => $validated['tgl_pengembalian'],
        'keterangan' => $validated['keterangan'],
        'status' => 'Menunggu Persetujuan',
    ]);

    ActivityHelper::log(
        'Pengajuan Pengembalian',
        'Pengembalian untuk Inventaris Barang Kecil dengan nama ' . $pengembalian->peminjaman->barang->nama_barang . ' telah diajukan.'
    );

    $this->sendPushNotificationToAdmins(
    'Pengajuan Pengembalian Baru',
    'oleh ' . auth()->user()->name . ' atas barang ' . $pengembalian->peminjaman->barang->nama_barang
);


    return redirect()->route('pengembalian.index')->with('success', 'Pengajuan pengembalian berhasil diajukan.');
}

    public function destroy($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.barang'])->findOrFail($id);
        $namaBarang = $pengembalian->peminjaman->barang->nama_barang;

        $pengembalian->delete();

        // Log aktivitas
        ActivityHelper::log(
            'Hapus Pengembalian',
            'Data Pengembalian untuk Inventaris Barang Kecil dengan nama ' . $namaBarang . ' telah dihapus.'
        );

        return redirect()->route('pengembalian.index')->with('success', 'Data Barang berhasil dihapus');
    }

    public function setujui($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.barang')->findOrFail($id);

        // Update status jadi "Disetujui"
        $pengembalian->status = 'Disetujui';

        // Ambil data barang terkait
        $barang = $pengembalian->peminjaman->barang;

        if ($barang) {
            // Tambahkan jumlah barang baik ke stok
            if ($pengembalian->jumlah_brg_baik > 0) {
                $barang->jumlah_barang += $pengembalian->jumlah_brg_baik;
            }

            // Tambahkan jumlah barang rusak dan hilang ke properti barang
            if ($pengembalian->jumlah_brg_rusak > 0) {
                $barang->jumlah_rusak += $pengembalian->jumlah_brg_rusak;
            }

            if ($pengembalian->jumlah_brg_hilang > 0) {
                $barang->jumlah_hilang += $pengembalian->jumlah_brg_hilang;
            }

            $barang->save();
        }

        $pengembalian->save();

        // Log aktivitas
        ActivityHelper::log(
            'Persetujuan Pengembalian',
            'Pengembalian untuk Inventaris Barang Kecil dengan nama ' . $barang->nama_barang . ' telah disetujui. Stok dan status diperbarui.'
        );

        $user = $pengembalian->peminjaman->user;
if ($user && $user->fcm_token) {
    $this->sendPushNotificationToUser(
        $user->fcm_token,
        'Pengembalian Anda Disetujui',
        'Pengembalian untuk barang ' . $barang->nama_barang . ' telah disetujui oleh admin.'
    );
}

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian disetujui dan data barang diperbarui.');
    }

    private function sendPushNotificationToAdmins($title, $body)
{
    $keyFile = storage_path('app/firebase-service-account.json');

    $credentials = new \Google\Auth\Credentials\ServiceAccountCredentials(
        'https://www.googleapis.com/auth/firebase.messaging',
        $keyFile
    );

    $authToken = $credentials->fetchAuthToken();
    $accessToken = $authToken['access_token'];

    $projectId = 'inventarissekolah-c84fc';
    $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $tokens = \App\Models\User::whereIn('role', ['A', 'K'])
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token')
        ->toArray();

    $client = new \GuzzleHttp\Client();

    foreach ($tokens as $token) {
        $message = [
            "message" => [
                "token" => $token,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
            ]
        ];

        $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $message,
        ]);
    }
}

private function sendPushNotificationToUser($token, $title, $body)
{
    $keyFile = storage_path('app/firebase-service-account.json');

    $credentials = new \Google\Auth\Credentials\ServiceAccountCredentials(
        'https://www.googleapis.com/auth/firebase.messaging',
        $keyFile
    );

    $authToken = $credentials->fetchAuthToken();
    $accessToken = $authToken['access_token'];

    $projectId = 'inventarissekolah-c84fc';
    $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $message = [
        "message" => [
            "token" => $token,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
        ]
    ];

    $client = new \GuzzleHttp\Client();
    $client->post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $message,
    ]);
}


}
