<?php

namespace App\Http\Controllers;

use App\Models\Mobiler;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;

class MobilerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $mobiler = Mobiler::where('nama_barang', 'like', "%{$search}%")
                ->orWhere('kode_barang', 'like', "%{$search}%")
                ->orWhere('merk', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->get();
        } else {
            $mobiler = Mobiler::orderBy('created_at', 'desc')->get();
        }

        // Hitung total harga dari data yang ditampilkan
        $totalHarga = $mobiler->sum('total_harga');

        return view("inventaris.mobiler.index", compact('mobiler', 'totalHarga'));
    }

    public function create()
    {
        return view("inventaris.mobiler.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:mobilers',
            'nama_barang' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'tgl_peroleh' => 'required',
            'asal_usul' => 'required',
            'cara_peroleh' => 'required',
            'jumlah_brg' => 'required',
            'harga_perunit' => 'required',
            'total_harga' => 'nullable|numeric',
        ]);

        $mobiler = Mobiler::create($validated);

        // Simpan riwayat
        ActivityHelper::log('Tambah Barang', 'Inventaris Barang Besar Mobiler dengan nama ' . $mobiler->nama_barang . ' berhasil ditambahkan');

        return redirect()->route('mobiler.index')->with('success', 'Data Barang Mobiler berhasil disimpan');
    }

    public function edit($id)
    {
        $mobiler = Mobiler::find($id);
        return view('inventaris.mobiler.edit', compact('mobiler'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:mobilers,kode_barang,' . $id,
            'nama_barang' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'tgl_peroleh' => 'required',
            'asal_usul' => 'required',
            'cara_peroleh' => 'required',
            'jumlah_brg' => 'required',
            'harga_perunit' => 'required',
            'total_harga' => 'nullable|numeric',
        ]);

        $mobiler = Mobiler::find($id);
        $mobiler->update($validated);

        // Simpan riwayat
        ActivityHelper::log('Edit Barang', 'Inventaris Barang Besar Mobiler dengan nama ' . $mobiler->nama_barang . ' berhasil diupdate');

        return redirect()->route('mobiler.index')->with('success', 'Data Barang Mobiler berhasil diupdate');
    }

   public function destroy($id)
{
    try {
        $mobiler = Mobiler::findOrFail($id);
        $nama = $mobiler->nama_barang;
        $mobiler->delete();

        // Simpan riwayat
        ActivityHelper::log('Hapus Barang', 'Inventaris Barang Besar Mobiler dengan nama ' . $nama . ' berhasil dihapus');

        return redirect()->route('mobiler.index')->with('success', 'Data Barang berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->route('mobiler.index')->with('error', 'Data tidak dapat dihapus karena masih digunakan.');
    } catch (\Exception $e) {
        return redirect()->route('mobiler.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
    }
}

}
