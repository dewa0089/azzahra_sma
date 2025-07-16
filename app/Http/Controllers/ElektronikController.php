<?php

namespace App\Http\Controllers;

use App\Models\Elektronik;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;

class ElektronikController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $elektronik = Elektronik::whereNull('deleted_at')
                ->where(function ($query) use ($search) {
                    $query->where('nama_barang', 'like', "%{$search}%")
                          ->orWhere('kode_barang', 'like', "%{$search}%")
                          ->orWhere('merk', 'like', "%{$search}%")
                          ->orWhere('type', 'like', "%{$search}%");
                })
                ->get();
        } else {
            $elektronik = Elektronik::all(); // hanya yang belum terhapus
        }

        $totalHarga = $elektronik->sum('total_harga');

        return view("inventaris.elektronik.index", compact('elektronik', 'totalHarga'));
    }

    public function create()
    {
        return view("inventaris.elektronik.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:elektroniks',
            'nama_barang' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'tgl_peroleh' => 'required',
            'asal_usul' => 'required',
            'cara_peroleh' => 'required',
            'jumlah_brg' => 'required|min:0',
            'harga_perunit' => 'required|min:0',
            'total_harga' => 'nullable|numeric'
        ]);

        $elektronik = Elektronik::create($validated);

        ActivityHelper::log('Tambah Barang', 'Inventaris Barang Besar Elektronik dengan nama ' . $elektronik->nama_barang . ' berhasil ditambahkan');

        return redirect()->route('elektronik.index')->with('success', 'Data Barang Elektronik berhasil disimpan');
    }

    public function edit($id)
    {
        $elektronik = Elektronik::find($id);
        return view('inventaris.elektronik.edit', compact('elektronik'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:elektroniks,kode_barang,' . $id,
            'nama_barang' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'tgl_peroleh' => 'required',
            'asal_usul' => 'required',
            'cara_peroleh' => 'required',
            'jumlah_brg' => 'required|min:0',
            'harga_perunit' => 'required|min:0',
            'total_harga' => 'nullable|numeric'
        ]);

        $elektronik = Elektronik::find($id);
        $elektronik->update($validated);

        ActivityHelper::log('Edit Barang', 'Inventaris Barang Besar Elektronik dengan nama ' . $elektronik->nama_barang . ' berhasil diupdate');

        return redirect()->route('elektronik.index')->with('success', 'Data Barang Elektronik berhasil diupdate');
    }

    public function destroy($id)
    {
        try {
            $elektronik = Elektronik::findOrFail($id);
            $nama = $elektronik->nama_barang;
            $elektronik->delete(); // Soft delete

            ActivityHelper::log('Hapus Barang', 'Inventaris Barang Besar Elektronik dengan nama ' . $nama . ' berhasil dihapus');

            return redirect()->route('elektronik.index')->with('success', 'Data Barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('elektronik.index')->with('error', 'Data tidak dapat dihapus karena masih digunakan.');
        } catch (\Exception $e) {
            return redirect()->route('elektronik.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function trash()
    {
        $elektronik = Elektronik::onlyTrashed()->get();
        return view('inventaris.elektronik.trash', compact('elektronik'));
    }

    public function restore($id)
    {
        $elektronik = Elektronik::withTrashed()->findOrFail($id);
        $elektronik->restore();

        ActivityHelper::log('Restore Barang', 'Barang Elektronik ' . $elektronik->nama_barang . ' berhasil direstore');

        return redirect()->route('elektronik.index')->with('success', 'Barang berhasil direstore');
    }
}
