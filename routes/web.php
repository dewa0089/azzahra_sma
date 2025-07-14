<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    BarangController,
    RusakController,
    PeminjamanController,
    PengembalianController,
    PemusnaanController,
    UserController,
    HistorieController,
    LaporanController,
    DashboardController,
    ElektronikController,
    MobilerController,
    LainnyaController,
    PerbaikanController
};
use App\Http\Controllers\Auth\LoginController;

// Redirect ke dashboard jika sudah login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->middleware('guest');

// Autentikasi: login dan logout
Route::get('login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Halaman utama setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:A,U,K,W'])
    ->name('dashboard');

// Grup route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Role A, K, W
    Route::middleware('checkRole:A,K,W')->group(function () {
        Route::resource('elektronik', ElektronikController::class);
        Route::resource('mobiler', MobilerController::class);
        Route::resource('lainnya', LainnyaController::class);
        Route::resource('laporan', LaporanController::class)->except(['show']);
        Route::get('/laporan/elektronik', [LaporanController::class, 'cetakElektronik']);
        Route::get('/laporan/mobiler', [LaporanController::class, 'cetakMobiler']);
        Route::get('/laporan/lainnya', [LaporanController::class, 'cetakLainnya']);
        Route::get('/laporan/barangKecil', [LaporanController::class, 'cetakBarangKecil']);
        Route::get('/laporan/peminjaman', [LaporanController::class, 'cetakPeminjaman']);
        Route::get('/laporan/pengembalian', [LaporanController::class, 'cetakPengembalian']);
        Route::get('/laporan/pemusnaan', [LaporanController::class, 'cetakPemusnaan']);
        Route::get('/laporan/rusak', [LaporanController::class, 'cetakBarangRusak']);
    });

    // Role A, U
    Route::middleware('checkRole:A,U')->group(function () {
        Route::resource('barang', BarangController::class);
        Route::resource('peminjaman', PeminjamanController::class);
        Route::resource('pengembalian', PengembalianController::class)->except(['create']);
        Route::get('/pengembalian/create/{id}', [PengembalianController::class, 'create'])->name('pengembalian.create.id');
        Route::patch('/peminjaman/{id}/batalkan', [PeminjamanController::class, 'batalkan'])->name('peminjaman.batalkan');
    });

    // Role A
    Route::middleware('checkRole:A')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('rusak', RusakController::class);
        Route::resource('pemusnaan', PemusnaanController::class);
        Route::resource('perbaikan', PerbaikanController::class);

        Route::patch('/peminjaman/{id}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::patch('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        Route::put('/pengembalian/setujui/{id}', [PengembalianController::class, 'setujui'])->name('pengembalian.setujui');
        Route::put('/rusak/{id}/ajukan-pemusnahan', [RusakController::class, 'ajukanPemusnahan'])->name('rusak.ajukanPemusnahan');
        Route::put('/pemusnaan/{id}/laksanakan', [PemusnaanController::class, 'laksanakan'])->name('pemusnaan.laksanakan');
        Route::get('/pemusnaan/create', [PemusnaanController::class, 'create'])->name('pemusnaan.create');
        Route::post('/pemusnaan/store', [PemusnaanController::class, 'store'])->name('pemusnaan.store');

        Route::get('/perbaikan/mulai/{rusak_id}', [PerbaikanController::class, 'ubahStatusDalamPerbaikan'])->name('perbaikan.mulai');
        Route::get('/perbaikan/selesaikan/{rusak_id}', [PerbaikanController::class, 'selesaikanPerbaikan'])->name('perbaikan.selesaikan');
        Route::post('/perbaikan/selesaikan/store', [PerbaikanController::class, 'selesaikanPerbaikanStore'])->name('perbaikan.selesaikan.store');
    });

    // Semua Role
    Route::middleware('checkRole:A,U,K,W')->group(function () {
        Route::resource('history', HistorieController::class);
    });
});
