<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elektronik;
use App\Models\Mobiler;
use App\Models\Lainnya;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Rusak;
use App\Models\Pemusnaan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $filter = $request->get('filter', 'semua');

    // Data jumlah barang
    $countElektronik = Elektronik::sum('jumlah_brg');
    $countMobiler = Mobiler::sum('jumlah_brg');
    $countLainnya = Lainnya::sum('jumlah_brg');

    $countRusak = Rusak::sum('jumlah_brg_rusak');
    $countPemusnahan = Pemusnaan::sum('jumlah_pemusnaan');

    // Inisialisasi query
    $peminjamanQuery = Peminjaman::query();
    $pengembalianQuery = Pengembalian::query();

    if ($filter == '6bulan') {
        $startDate = Carbon::now()->subMonths(6);
        $peminjamanQuery->where('created_at', '>=', $startDate);
        $pengembalianQuery->where('created_at', '>=', $startDate);
    } elseif ($filter == '1tahun') {
        $startDate = Carbon::now()->subYear();
        $peminjamanQuery->where('created_at', '>=', $startDate);
        $pengembalianQuery->where('created_at', '>=', $startDate);
    }

    $countPeminjaman = $peminjamanQuery->count();
    $countPengembalian = $pengembalianQuery->count();

    return view('dashboard', compact(
        'countElektronik', 'countMobiler', 'countLainnya',
        'countPeminjaman', 'countPengembalian', 'filter','countRusak', 'countPemusnahan'
    ));
}

}
