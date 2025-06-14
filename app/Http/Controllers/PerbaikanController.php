<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perbaikan;
use App\Models\Rusak;
use Illuminate\Support\Str;
use App\Helpers\ActivityHelper;

class PerbaikanController extends Controller
{
    public function index()
    {
        $perbaikan = Perbaikan::with(['rusak.elektronik', 'rusak.mobiler', 'rusak.lainnya'])
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get();

        return view('perbaikan.index', compact('perbaikan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rusak_id' => 'required|exists:rusaks,id',
            'tanggal_perbaikan' => 'required|date',
            'biaya_perbaikan' => 'required|integer', // ⚠️ ubah ke 'integer' jika memang itu biaya dalam rupiah
            'jumlah_perbaikan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->update($validated);

        ActivityHelper::log('Update Perbaikan', 'Data Perbaikan ID ' . $perbaikan->id . ' berhasil diperbarui');

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikanId = $perbaikan->id;
        $perbaikan->delete();

        ActivityHelper::log('Hapus Perbaikan', 'Data Perbaikan ID ' . $perbaikanId . ' berhasil dihapus');

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil dihapus.');
    }


   public function selesaikanPerbaikan($rusak_id)
{
    $rusak = Rusak::findOrFail($rusak_id);

    if ($rusak->status !== 'Dalam Perbaikan') {
        return redirect()->back()->withErrors(['msg' => 'Barang ini belum berstatus Dalam Perbaikan.']);
    }

    return view('perbaikan.create', compact('rusak'));
}

public function selesaikanPerbaikanStore(Request $request)
{
    $validated = $request->validate([
        'rusak_id' => 'required|exists:rusaks,id',
        'tanggal_perbaikan' => 'required|date',
        'biaya_perbaikan' => 'required|integer',
        'jumlah_perbaikan' => 'required|integer|min:1',
        'keterangan' => 'nullable|string'
    ]);

    $rusak = Rusak::with(['elektronik', 'mobiler', 'lainnya'])->findOrFail($validated['rusak_id']);

    // Simpan ke tabel perbaikan
    Perbaikan::create([
        'id' => Str::uuid(),
        'rusak_id' => $rusak->id,
        'tanggal_perbaikan' => $validated['tanggal_perbaikan'],
        'biaya_perbaikan' => $validated['biaya_perbaikan'],
        'jumlah_perbaikan' => $validated['jumlah_perbaikan'],
        'keterangan' => $validated['keterangan'] ?? '',
        'status' => 'Selesai'
    ]);

    // Tambahkan stok kembali ke barang yang diperbaiki
    $jumlah = $validated['jumlah_perbaikan'];
    if ($rusak->elektronik) {
        $rusak->elektronik->jumlah_brg += $jumlah;
        $rusak->elektronik->save();
    } elseif ($rusak->mobiler) {
        $rusak->mobiler->jumlah_brg += $jumlah;
        $rusak->mobiler->save();
    } elseif ($rusak->lainnya) {
        $rusak->lainnya->jumlah_brg += $jumlah;
        $rusak->lainnya->save();
    }

    // Update status barang rusak
    $rusak->status = 'Selesai Diperbaiki';
    $rusak->save();

    ActivityHelper::log('Selesaikan Perbaikan', 'Barang rusak ID ' . $rusak->id . ' diselesaikan dan stok dikembalikan');

    return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil disimpan dan stok dikembalikan.');
}


public function ubahStatusDalamPerbaikan($rusak_id)
{
    $rusak = Rusak::findOrFail($rusak_id);

    if ($rusak->status !== 'Rusak') {
        return redirect()->back()->withErrors(['msg' => 'Barang ini tidak dapat diperbaiki karena statusnya bukan "Rusak".']);
    }

    $rusak->status = 'Dalam Perbaikan';
    $rusak->save();

    ActivityHelper::log('Perbaikan Dimulai', 'Barang rusak ID ' . $rusak->id . ' status diubah menjadi Dalam Perbaikan');

    return redirect()->route('rusak.index')->with('success', 'Status diubah menjadi Dalam Perbaikan.');
}


}
