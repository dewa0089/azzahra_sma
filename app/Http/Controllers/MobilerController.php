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
            $mobiler = Mobiler::whereNull('deleted_at')
                ->where(function ($query) use ($search) {
                    $query->where('nama_barang', 'like', "%{$search}%")
                          ->orWhere('kode_barang', 'like', "%{$search}%")
                          ->orWhere('merk', 'like', "%{$search}%")
                          ->orWhere('type', 'like', "%{$search}%");
                })
                ->get();
        } else {
            $mobiler = Mobiler::all(); // hanya yang belum terhapus
        }

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
            'jumlah_brg' => 'required|min:0',
            'harga_perunit' => 'required|min:0',
            'total_harga' => 'nullable|numeric|min:0',
        ]);

        $mobiler = Mobiler::create($validated);

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
            'jumlah_brg' => 'required|min:0',
            'harga_perunit' => 'required|min:0',
            'total_harga' => 'nullable|numeric',
        ]);

        $mobiler = Mobiler::find($id);
        $mobiler->update($validated);

        ActivityHelper::log('Edit Barang', 'Inventaris Barang Besar Mobiler dengan nama ' . $mobiler->nama_barang . ' berhasil diupdate');

        return redirect()->route('mobiler.index')->with('success', 'Data Barang Mobiler berhasil diupdate');
    }

    public function destroy($id)
    {
        try {
            $mobiler = Mobiler::findOrFail($id);
            $nama = $mobiler->nama_barang;
            $mobiler->delete(); // soft delete

            ActivityHelper::log('Hapus Barang', 'Inventaris Barang Besar Mobiler dengan nama ' . $nama . ' berhasil dihapus');

            return redirect()->route('mobiler.index')->with('success', 'Data Barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('mobiler.index')->with('error', 'Data tidak dapat dihapus karena masih digunakan.');
        } catch (\Exception $e) {
            return redirect()->route('mobiler.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function trash()
    {
        $mobiler = Mobiler::onlyTrashed()->get();
        return view('inventaris.mobiler.trash', compact('mobiler'));
    }

    public function restore($id)
    {
        $mobiler = Mobiler::withTrashed()->findOrFail($id);
        $mobiler->restore();

        ActivityHelper::log('Restore Barang', 'Barang Mobiler ' . $mobiler->nama_barang . ' berhasil direstore');

        return redirect()->route('mobiler.index')->with('success', 'Barang berhasil direstore');
    }
}
