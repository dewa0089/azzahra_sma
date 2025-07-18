<?php

namespace App\Http\Controllers;

use App\Models\Rusak;
use App\Models\Elektronik;
use App\Models\Mobiler;
use App\Models\Lainnya;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;
use App\Models\Pemusnaan;
use App\Models\Perbaikan;


class RusakController extends Controller
{
public function index()
{
    $rusak = Rusak::orderByRaw("FIELD(status, 'Rusak', 'Dalam Perbaikan', 'Selesai Diperbaiki', 'Berhasil Dimusnahkan')")
                 ->orderBy('created_at', 'desc')
                 ->get();

    return view("rusak.index", compact("rusak"));
}



    public function create()
    {
        $rusak = Rusak::all();
        $elektronik = Elektronik::all();
        $mobiler = Mobiler::all();
        $lainnya = Lainnya::all();

        return view('rusak.create', compact('rusak', 'elektronik', 'mobiler', 'lainnya'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_brg_rusak' => 'required',
            'jumlah_brg_rusak' => 'required|integer|min:1',
            'gambar_brg_rusak' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'tgl_rusak' => 'required|date',
            'keterangan' => 'nullable|string',
            'elektronik_id' => 'nullable|exists:elektroniks,id',
            'mobiler_id' => 'nullable|exists:mobilers,id',
            'lainnya_id' => 'nullable|exists:lainnyas,id',
        ]);

        $data = $request->all();
        $data['status'] = 'Rusak';

        // Upload gambar
        if ($request->hasFile('gambar_brg_rusak')) {
            $file = $request->file('gambar_brg_rusak');
            $tanggalRusak = date('Ymd', strtotime($request->tgl_rusak));
            $fileName = 'rusak_' . $tanggalRusak . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('gambar'), $fileName);
            $data['gambar_brg_rusak'] = $fileName;
        }

        // Kurangi jumlah barang dari inventaris sesuai jenis
        $jumlahRusak = (int)$request->jumlah_brg_rusak;
        $barang = null;

        if ($request->jenis_brg_rusak === 'elektronik' && $request->elektronik_id) {
            $barang = Elektronik::find($request->elektronik_id);
        } elseif ($request->jenis_brg_rusak === 'mobiler' && $request->mobiler_id) {
            $barang = Mobiler::find($request->mobiler_id);
        } elseif ($request->jenis_brg_rusak === 'lainnya' && $request->lainnya_id) {
            $barang = Lainnya::find($request->lainnya_id);
        }

        if ($barang && $barang->jumlah_brg >= $jumlahRusak) {
            $barang->jumlah_brg -= $jumlahRusak;
            $barang->save();
        }

        $rusak = Rusak::create($data);

        // Logging aktivitas
        if ($barang) {
            ActivityHelper::log(
                'Tambah Barang Rusak',
                ucfirst($request->jenis_brg_rusak) . ' ' . $barang->nama_barang . ' sejumlah ' . $jumlahRusak . ' dilaporkan rusak.'
            );
        }

        return redirect()->route('rusak.index')->with('success', 'Data Barang Rusak berhasil disimpan');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_brg_rusak' => 'required',
            'jumlah_brg_rusak' => 'required|integer|min:1',
            'gambar_brg_rusak' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'tgl_rusak' => 'required|date',
            'keterangan' => 'nullable|string',
            'status' => 'required|string',
            'elektronik_id' => 'nullable|exists:elektroniks,id',
            'mobiler_id' => 'nullable|exists:mobilers,id',
            'lainnya_id' => 'nullable|exists:lainnyas,id',
        ]);

        $rusak = Rusak::findOrFail($id);
        $data = $request->except('gambar_brg_rusak');
        
        if ($request->hasFile('gambar_brg_rusak')) {
            if ($rusak->gambar_brg_rusak && file_exists(public_path('gambar/' . $rusak->gambar_brg_rusak))) {
                unlink(public_path('gambar/' . $rusak->gambar_brg_rusak));
            }
            $file = $request->file('gambar_brg_rusak');
            $tanggalRusak = date('Ymd', strtotime($request->tgl_rusak));
            $fileName = 'rusak_' . $tanggalRusak . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('gambar'), $fileName);
            $data['gambar_brg_rusak'] = $fileName;
        }

        $rusak->update($data);

        // Logging aktivitas
        ActivityHelper::log(
            'Update Barang Rusak',
            'Data barang rusak ID ' . $rusak->id . ' berhasil diperbarui.'
        );

        return redirect()->route('rusak.index')->with('success', 'Data Rusak berhasil diperbarui');
    }

    public function destroy($id)
{
    try {
        $rusak = Rusak::with(['pemusnahan', 'perbaikan'])->findOrFail($id);

        // Cegah penghapusan jika digunakan
        if ($rusak->pemusnahan || $rusak->perbaikan) {
            return redirect()->route('rusak.index')
                ->with('error', 'Data tidak bisa dihapus karena sudah digunakan dalam pemusnahan atau perbaikan.');
        }

        $jumlahRusak = $rusak->jumlah_brg_rusak;

        // Kembalikan jumlah barang ke inventaris
        if ($rusak->jenis_brg_rusak === 'elektronik' && $rusak->elektronik_id) {
            $barang = Elektronik::find($rusak->elektronik_id);
        } elseif ($rusak->jenis_brg_rusak === 'mobiler' && $rusak->mobiler_id) {
            $barang = Mobiler::find($rusak->mobiler_id);
        } elseif ($rusak->jenis_brg_rusak === 'lainnya' && $rusak->lainnya_id) {
            $barang = Lainnya::find($rusak->lainnya_id);
        } else {
            $barang = null;
        }

        if ($barang) {
            $barang->jumlah_brg += $jumlahRusak;
            $barang->save();
        }

        // Hapus gambar jika ada
        if ($rusak->gambar_brg_rusak && file_exists(public_path('gambar/' . $rusak->gambar_brg_rusak))) {
            unlink(public_path('gambar/' . $rusak->gambar_brg_rusak));
        }

        $rusak->delete();

        ActivityHelper::log(
            'Hapus Barang Rusak',
            'Data barang rusak dengan ID ' . $id . ' telah dihapus dan jumlah barang dikembalikan.'
        );

        return redirect()->route('rusak.index')->with('success', 'Data Rusak berhasil dihapus dan jumlah barang dikembalikan.');

    } catch (\Exception $e) {
        return redirect()->route('rusak.index')->with('error', 'Terjadi error: ' . $e->getMessage());
    }
}


public function trash()
{
    $rusak = Rusak::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->get();

    return view('rusak.trash', compact('rusak'));
}

public function restore($id)
{
    $rusak = Rusak::withTrashed()->findOrFail($id);

    // Kembalikan stok di inventaris dikurangi kembali saat restore
    $jumlahRusak = $rusak->jumlah_brg_rusak;
    $barang = null;

    if ($rusak->jenis_brg_rusak === 'elektronik' && $rusak->elektronik_id) {
        $barang = Elektronik::find($rusak->elektronik_id);
    } elseif ($rusak->jenis_brg_rusak === 'mobiler' && $rusak->mobiler_id) {
        $barang = Mobiler::find($rusak->mobiler_id);
    } elseif ($rusak->jenis_brg_rusak === 'lainnya' && $rusak->lainnya_id) {
        $barang = Lainnya::find($rusak->lainnya_id);
    }

    if ($barang && $barang->jumlah_brg >= $jumlahRusak) {
        $barang->jumlah_brg -= $jumlahRusak;
        $barang->save();
    }

    $rusak->restore();

    ActivityHelper::log('Restore Barang Rusak', 'Data barang rusak dengan ID ' . $id . ' berhasil direstore.');

    return redirect()->route('rusak.index')->with('success', 'Data barang rusak berhasil direstore.');
}

 public function forceDelete($id)
{
    $rusak = Rusak::withTrashed()->findOrFail($id);

    // Ambil nama barang dari model terkait
    $nama = 'Tidak diketahui';

    if ($rusak->jenis_brg_rusak === 'elektronik' && $rusak->elektronik_id) {
        $barang = Elektronik::withTrashed()->find($rusak->elektronik_id);
        $nama = $barang ? $barang->nama_barang : $nama;
    } elseif ($rusak->jenis_brg_rusak === 'mobiler' && $rusak->mobiler_id) {
        $barang = Mobiler::withTrashed()->find($rusak->mobiler_id);
        $nama = $barang ? $barang->nama_barang : $nama;
    } elseif ($rusak->jenis_brg_rusak === 'lainnya' && $rusak->lainnya_id) {
        $barang = Lainnya::withTrashed()->find($rusak->lainnya_id);
        $nama = $barang ? $barang->nama_barang : $nama;
    }

    // Hapus gambar jika masih ada di server
    if ($rusak->gambar_brg_rusak && file_exists(public_path('gambar/' . $rusak->gambar_brg_rusak))) {
        unlink(public_path('gambar/' . $rusak->gambar_brg_rusak));
    }

    $rusak->forceDelete();

    ActivityHelper::log('Hapus Permanen Barang Rusak', ucfirst($rusak->jenis_brg_rusak) . ' ' . $nama . ' dihapus permanen dari data rusak.');

    return redirect()->route('rusak.trash')->with('success', 'Data barang rusak berhasil dihapus permanen.');
}


}
