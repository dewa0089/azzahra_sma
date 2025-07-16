<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;

class BarangController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    if ($search) {
        // Hanya cari dari data yang belum dihapus (tanpa withTrashed)
        $barang = Barang::whereNull('deleted_at')
            ->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%");
            })
            ->get();
    } else {
        // Hanya ambil data yang belum dihapus
        $barang = Barang::all();
    }

    // Total harga hanya dari data yang belum dihapus
    $totalHarga = $barang->sum('total_harga');

    return view("barang.index", compact('barang', 'totalHarga'));
}


    public function create()
    {
        return view("barang.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'jumlah_barang' => 'required|min:0',
            'jumlah_rusak' => 'nullable|min:0',
            'jumlah_hilang' => 'nullable|min:0',
            'tgl_peroleh' => 'required',
            'harga_perunit' => 'required|min:0',
            'total_harga' => 'required',
        ]);

        $validated['jumlah_rusak'] = $validated['jumlah_rusak'] ?? 0;
        $validated['jumlah_hilang'] = $validated['jumlah_hilang'] ?? 0;

        $barang = Barang::create($validated);

        // Simpan riwayat aktivitas
        ActivityHelper::log('Tambah Barang', 'Inventaris Barang Kecil dengan nama ' . $barang->nama_barang . ' berhasil ditambahkan');

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil disimpan');
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $id,
            'nama_barang' => 'required',
            'jumlah_barang' => 'required|min:0',
            'jumlah_rusak' => 'nullable|min:0',
            'jumlah_hilang' => 'nullable|min:0',
            'tgl_peroleh' => 'required',
            'harga_perunit' => 'required|min:0',
            'total_harga' => 'required',
        ]);

        $barang = Barang::find($id);
        $barang->update($validated);

        // Simpan riwayat aktivitas
        ActivityHelper::log('Edit Barang', 'Inventaris Barang Kecil dengan nama ' . $barang->nama_barang . ' berhasil diupdate');

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil diupdate');
    }

    public function destroy($id)
    {
    try {
        $barang = Barang::findOrFail($id);
        $nama = $barang->nama_barang;
        $barang->delete();

        // Simpan riwayat
        ActivityHelper::log('Hapus Barang', 'Inventaris Barang Kecil dengan nama ' . $nama . ' berhasil dihapus');

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->route('barang.index')->with('error', 'Data tidak dapat dihapus karena masih digunakan.');
    } catch (\Exception $e) {
        return redirect()->route('barang.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
    }
    }

    public function trash()
{
    $barang = Barang::onlyTrashed()->get();
    return view('barang.trash', compact('barang'));
}

public function restore($id)
{
    $barang = Barang::withTrashed()->findOrFail($id);
    $barang->restore();

    ActivityHelper::log('Restore Barang', 'Barang ' . $barang->nama_barang . ' berhasil direstore');

    return redirect()->route('barang.index')->with('success', 'Barang berhasil direstore');
}


}
