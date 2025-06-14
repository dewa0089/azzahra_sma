<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Barang;
use App\Helpers\ActivityHelper;
use App\Models\User;

class PeminjamanController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();

    $query = Peminjaman::with('barang')
        ->orderByRaw("CASE WHEN status = 'Menunggu Persetujuan' THEN 0 ELSE 1 END")
        ->orderBy('created_at', 'desc');

    if (in_array($user->role, ['A', 'K', 'W'])) {
        // Jika admin, kepala, wakil => bisa filter berdasarkan nama peminjam
        if ($request->filled('nama_peminjam')) {
            $query->where('nama_peminjam', $request->nama_peminjam);
        }

        $peminjaman = $query->get();

        // Ambil daftar user role U untuk dropdown filter
        $users = User::where('role', 'U')->orderBy('name')->get();

        return view('peminjaman.index', compact('peminjaman', 'users'));
    } else {
        // Untuk User biasa, hanya tampilkan data miliknya sendiri
        $peminjaman = $query->where('user_id', $user->id)->get();

        return view('peminjaman.index', compact('peminjaman'));
    }
}



    public function create()
    {
        $barang = Barang::all();
        return view('peminjaman.create', compact('barang'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_peminjam' => 'required',
        'barang_id' => 'required|exists:barangs,id',
        'kode_barang' => 'required',
        'jumlah_peminjam' => 'required|integer|min:1',
        'tgl_peminjam' => 'required|date',
    ]);

    $barang = Barang::findOrFail($request->barang_id);

    // Validasi stok
    if ($request->jumlah_peminjam > $barang->jumlah_barang) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['stok' => 'Jumlah peminjaman melebihi stok yang tersedia (' . $barang->jumlah_barang . ').']);
    }

    $tgl_kembali = Carbon::parse($request->tgl_peminjam)->addYear();

    $peminjaman = Peminjaman::create([
        'nama_peminjam' => $request->nama_peminjam,
        'barang_id' => $request->barang_id,
        'kode_barang' => $request->kode_barang,
        'jumlah_peminjam' => $request->jumlah_peminjam,
        'tgl_peminjam' => $request->tgl_peminjam,
        'tgl_kembali' => $tgl_kembali->format('Y-m-d'),
        'status' => 'Menunggu Persetujuan',
        'user_id' => Auth::id(),
    ]);

    ActivityHelper::log('Ajukan Peminjaman', 'Peminjaman oleh ' . $peminjaman->nama_peminjam . ' untuk Inventaris Barang Kecil dengan nama ' . $peminjaman->barang->nama_barang . ' telah diajukan.');

    return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan!');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_peminjam' => 'required',
            'jumlah_peminjam' => 'required|integer|min:1',
            'tgl_peminjam' => 'required|date',
            'status' => 'required',
            'barang_id' => 'required|exists:barangs,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update($request->all());

        ActivityHelper::log('Update Peminjaman', 'Data peminjaman untuk Inventaris Barang Kecil dengan nama ' . $peminjaman->barang->nama_barang . ' diperbarui.');

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $barang = Barang::findOrFail($peminjaman->barang_id);

        if ($peminjaman->status !== 'Disetujui') {
            $barang->jumlah_barang += $peminjaman->jumlah_peminjam;
            $barang->save();
        }

        $kodeBarang = $peminjaman->kode_barang;
        $peminjaman->delete();

        ActivityHelper::log('Hapus Peminjaman', 'Peminjaman untuk Inventaris Barang Kecil dengan nama ' . $peminjaman->barang->nama_barang . ' dihapus.');

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman dihapus.');
    }

    public function setujui($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status === 'Disetujui') {
            return redirect()->back()->with('warning', 'Peminjaman sudah disetujui sebelumnya.');
        }

        $barang = $peminjaman->barang;

        if ($barang->jumlah_barang < $peminjaman->jumlah_peminjam) {
            return redirect()->back()->withErrors(['stok' => 'Stok barang tidak mencukupi untuk menyetujui.']);
        }

        $barang->jumlah_barang -= $peminjaman->jumlah_peminjam;
        $barang->save();

        $peminjaman->status = 'Disetujui';
        $peminjaman->save();

        if (!$peminjaman->pengembalian) {
            Pengembalian::create([
                'id' => Str::uuid(),
                'peminjaman_id' => $peminjaman->id,
                'jumlah_pengembalian' => 0,
                'jumlah_barang_rusak' => 0,
                'jumlah_barang_hilang' => 0,
                'tanggal_pengembalian' => null,
                'status' => 'Belum Dikembalikan',
            ]);
        }

        ActivityHelper::log('Setujui Peminjaman', 'Peminjaman untuk Inventaris Barang Kecil dengan nama ' . $peminjaman->barang->namabarang . ' disetujui.');

        return redirect()->back()->with('success', 'Peminjaman disetujui dan data pengembalian dibuat.');
    }

    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'Ditolak';
        $peminjaman->save();

        ActivityHelper::log('Tolak Peminjaman', 'Peminjaman untuk Inventaris Barang Kecil dengan nama ' . $peminjaman->barang->nama_barang . ' ditolak.');

        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function batalkan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'Dibatalkan';
        $peminjaman->save();

        ActivityHelper::log('Batalkan Peminjaman', 'Peminjaman untuk Inventaris Barang Kecil dengan nama ' . $peminjaman->barang->nama_barang . ' dibatalkan.');

        return redirect()->back()->with('success', 'Peminjaman dibatalkan.');
    }
}
