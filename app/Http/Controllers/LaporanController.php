<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;
use App\Models\Elektronik;
use App\Models\Mobiler;
use App\Models\Lainnya;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Rusak;
use App\Models\Pemusnaan;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    private function applyFilter($query, $request)
    {
        $now = Carbon::now();

        switch ($request->filter) {
            case 'bulan':
                $query->whereYear('created_at', $now->year)
                      ->whereMonth('created_at', $now->month);
                break;
            case 'tahun':
                $query->whereYear('created_at', $now->year);
                break;
            case 'semua':
            default:
                // Tidak difilter
                break;
        }

        return $query;
    }

    public function cetakElektronik(Request $request)
{
    $elektronik = $this->applyFilter(Elektronik::query(), $request)
                       ->orderBy('created_at', 'desc')->get();
    $totalHarga = $elektronik->sum('total_harga');

    if ($request->format === 'word') {
        return response()->view('laporan.elektronik', compact('elektronik', 'totalHarga'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Elektronik.doc"');
    }
    

    return view('laporan.elektronik', compact('elektronik', 'totalHarga'));
}


   public function cetakMobiler(Request $request)
{
    $mobiler = $this->applyFilter(Mobiler::query(), $request)
                    ->orderBy('created_at', 'desc')->get();
    $totalHarga = $mobiler->sum('total_harga');

    ActivityHelper::log('Cetak Laporan', 'Laporan Inventaris Barang Mobiler berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.mobiler', compact('mobiler', 'totalHarga'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Mobiler.doc"');
    }

    return view('laporan.mobiler', compact('mobiler', 'totalHarga'));
}

public function cetakLainnya(Request $request)
{
    $lainnya = $this->applyFilter(Lainnya::query(), $request)
                    ->orderBy('created_at', 'desc')->get();
    $totalHarga = $lainnya->sum('total_harga');

    ActivityHelper::log('Cetak Laporan', 'Laporan Inventaris Barang Lainnya berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.lainnya', compact('lainnya', 'totalHarga'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Lainnya.doc"');
    }

    return view('laporan.lainnya', compact('lainnya', 'totalHarga'));
}

public function cetakBarangKecil(Request $request)
{
    $barang = $this->applyFilter(Barang::query(), $request)
                   ->orderBy('created_at', 'desc')->get();
    $totalHarga = $barang->sum('total_harga');

    ActivityHelper::log('Cetak Laporan', 'Laporan Inventaris Barang Kecil berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.barangKecil', compact('barang', 'totalHarga'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_BarangKecil.doc"');
    }

    return view('laporan.barangKecil', compact('barang', 'totalHarga'));
}

public function cetakPeminjaman(Request $request)
{
    $peminjaman = $this->applyFilter(Peminjaman::query(), $request)
        ->join('users', 'peminjamans.user_id', '=', 'users.id')
        ->orderBy('users.name', 'asc')
        ->select('peminjamans.*')
        ->get();

    ActivityHelper::log('Cetak Laporan', 'Laporan Peminjaman Inventaris Barang Kecil berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.peminjaman', compact('peminjaman'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Peminjaman.doc"');
    }

    return view('laporan.peminjaman', compact('peminjaman'));
}


public function cetakPengembalian(Request $request)
{
    $pengembalian = $this->applyFilter(Pengembalian::query(), $request)
        ->join('users', 'pengembalians.user_id', '=', 'users.id')
        ->orderBy('users.name', 'asc')
        ->select('pengembalians.*')
        ->get();

    ActivityHelper::log('Cetak Laporan', 'Laporan Pengembalian Inventaris Barang Kecil berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.pengembalian', compact('pengembalian'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Pengembalian.doc"');
    }

    return view('laporan.pengembalian', compact('pengembalian'));
}


public function cetakPemusnaan(Request $request)
{
    $pemusnaan = $this->applyFilter(Pemusnaan::query(), $request)
                      ->orderBy('created_at', 'desc')->get();
    $totalHarga = $pemusnaan->sum('total_harga');

    ActivityHelper::log('Cetak Laporan', 'Laporan Pemusnaan Inventaris Barang Besar berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.pemusnaan', compact('pemusnaan', 'totalHarga'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Pemusnaan.doc"');
    }

    return view('laporan.pemusnaan', compact('pemusnaan', 'totalHarga'));
}

public function cetakBarangRusak(Request $request)
{
    $rusak = $this->applyFilter(Rusak::query(), $request)
                  ->orderBy('created_at', 'desc')->get();

    ActivityHelper::log('Cetak Laporan', 'Laporan Inventaris Barang Besar Rusak berhasil dicetak');

    if ($request->format === 'word') {
        return response()->view('laporan.rusak', compact('rusak'))
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="Laporan_BarangRusak.doc"');
    }

    return view('laporan.rusak', compact('rusak'));
}

}
