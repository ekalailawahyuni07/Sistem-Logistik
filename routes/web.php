<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialMasukController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\MaterialKeluarController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminMaterialMasukController;
use App\Http\Controllers\AdminMaterialKeluarController;
use App\Http\Controllers\AdminStokMaterialController;
use App\Http\Controllers\AdminClusterController;
use App\Http\Controllers\AdminDokumenController;
use App\Http\Controllers\AdminSuratJalanController;
use App\Http\Controllers\AdminNotifikasiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $totalMasuk = \App\Models\TransaksiMaterial::where('jenis_transaksi', 'masuk')->sum('jumlah');
    $totalKeluar = \App\Models\TransaksiMaterial::where('jenis_transaksi', 'keluar')->sum('jumlah');
    $totalStock = $totalMasuk - $totalKeluar;

    return view('user.dashboard', compact('totalMasuk', 'totalKeluar', 'totalStock'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/data-material', [MaterialController::class, 'index'])->name('data.material');
    Route::get('/data-material/tambah', [MaterialController::class, 'create'])->name('data.material.create');
    Route::post('/data-material/simpan', [MaterialController::class, 'store'])->name('data.material.store');
    Route::get('/data-material/{id}/edit', [MaterialController::class, 'edit'])->name('data.material.edit');
    Route::put('/data-material/{id}/update', [MaterialController::class, 'update'])->name('data.material.update');

    Route::get('/material-masuk', [MaterialMasukController::class, 'index'])->name('material.masuk');
    Route::get('/material-masuk/tambah', [MaterialMasukController::class, 'create'])->name('material.masuk.create');
    Route::post('/material-masuk/simpan', [MaterialMasukController::class, 'store'])->name('material.masuk.store');
    Route::get('/material-masuk/{id}/edit', [MaterialMasukController::class, 'edit'])->name('material.masuk.edit');
    Route::put('/material-masuk/{id}', [MaterialMasukController::class, 'update'])->name('material.masuk.update');

    Route::get('/material-keluar', [MaterialKeluarController::class, 'index'])->name('material.keluar');
    Route::get('/material-keluar/tambah', [MaterialKeluarController::class, 'create'])->name('material.keluar.create');
    Route::post('/material-keluar/simpan', [MaterialKeluarController::class, 'store'])->name('material.keluar.store');
    Route::get('/material-keluar/{id}/edit', [MaterialKeluarController::class, 'edit'])->name('material.keluar.edit');
    Route::put('/material-keluar/{id}', [MaterialKeluarController::class, 'update'])->name('material.keluar.update');

    Route::get('/cluster', [ClusterController::class, 'index'])->name('cluster');
    Route::get('/cluster/tambah', [ClusterController::class, 'create'])->name('cluster.create');
    Route::post('/cluster/simpan', [ClusterController::class, 'store'])->name('cluster.store');
    Route::get('/cluster/{id}/edit', [ClusterController::class, 'edit'])->name('cluster.edit');
    Route::put('/cluster/{id}', [ClusterController::class, 'update'])->name('cluster.update');
    Route::get('/cluster/{id}/view', [ClusterController::class, 'show'])->name('cluster.show');

    Route::get('/surat-jalan', [SuratJalanController::class, 'index'])->name('surat.jalan');
    Route::get('/surat-jalan/{id}/view', [SuratJalanController::class, 'show'])->name('surat.jalan.show');

    Route::get('/stok-material', [MaterialController::class, 'stok'])->name('stok.material');
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen');
});

// ================= ADMIN =================

Route::middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Verifikasi User
    Route::get('/admin/verifikasi-user', function () {
        return view('admin.verifikasi-user');
    })->name('admin.verifikasi.user');

    // Kelola Area
    Route::get('/admin/kelola-area', function () {
        return view('admin.kelola-area');
    })->name('admin.kelola.area');

    // =========================
    // DATA MATERIAL ADMIN
    // =========================

    Route::get('/admin/data-material', [AdminMaterialController::class, 'index'])
        ->name('admin.data.material');

    Route::get('/admin/data-material/tambah', [AdminMaterialController::class, 'create'])
        ->name('admin.data.material.create');

    Route::post('/admin/data-material/simpan', [AdminMaterialController::class, 'store'])
        ->name('admin.data.material.store');

    Route::get('/admin/data-material/{id}/edit', [AdminMaterialController::class, 'edit'])
        ->name('admin.data.material.edit');

    Route::put('/admin/data-material/{id}', [AdminMaterialController::class, 'update'])
        ->name('admin.data.material.update');

    Route::delete('/admin/data-material/{id}', [AdminMaterialController::class, 'destroy'])
        ->name('admin.data.material.destroy');

    // MATERIAL MASUK ADMIN
    Route::get('/admin/material-masuk', [AdminMaterialMasukController::class, 'index'])
        ->name('admin.material.masuk');

    Route::get('/admin/material-masuk/tambah', [AdminMaterialMasukController::class, 'create'])
        ->name('admin.material.masuk.create');

    Route::post('/admin/material-masuk/simpan', [AdminMaterialMasukController::class, 'store'])
        ->name('admin.material.masuk.store');

    Route::get('/admin/material-masuk/{id}/edit', [AdminMaterialMasukController::class, 'edit'])
        ->name('admin.material.masuk.edit');

    Route::put('/admin/material-masuk/{id}', [AdminMaterialMasukController::class, 'update'])
        ->name('admin.material.masuk.update');
    
    Route::delete('/admin/material-masuk/{id}', [AdminMaterialMasukController::class, 'destroy'])
        ->name('admin.material.masuk.destroy');

    // MATERIAL KELUAR ADMIN
    Route::get('/admin/material-keluar', [AdminMaterialKeluarController::class, 'index'])
        ->name('admin.material.keluar');

    Route::get('/admin/material-keluar/tambah', [AdminMaterialKeluarController::class, 'create'])
        ->name('admin.material.keluar.create');

    Route::post('/admin/material-keluar/simpan', [AdminMaterialKeluarController::class, 'store'])
        ->name('admin.material.keluar.store');

    Route::get('/admin/material-keluar/{id}/edit', [AdminMaterialKeluarController::class, 'edit'])
        ->name('admin.material.keluar.edit');

    Route::put('/admin/material-keluar/{id}', [AdminMaterialKeluarController::class, 'update'])
        ->name('admin.material.keluar.update');

    // STOK MATERIAL ADMIN
    Route::get('/admin/stok-material', [AdminStokMaterialController::class, 'index'])
        ->name('admin.stok.material');

    // CLUSTER ADMIN
    Route::get('/admin/cluster', [AdminClusterController::class, 'index'])
        ->name('admin.cluster');

    Route::get('/admin/cluster/tambah', [AdminClusterController::class, 'create'])
        ->name('admin.cluster.create');

    Route::post('/admin/cluster/simpan', [AdminClusterController::class, 'store'])
        ->name('admin.cluster.store');

    Route::get('/admin/cluster/{id}/edit', [AdminClusterController::class, 'edit'])
        ->name('admin.cluster.edit');

    Route::put('/admin/cluster/{id}', [AdminClusterController::class, 'update'])
        ->name('admin.cluster.update');

    Route::get('/admin/cluster/{id}/view', [AdminClusterController::class, 'show'])
        ->name('admin.cluster.show');

    Route::get('/admin/dokumen', [AdminDokumenController::class, 'index'])
        ->name('admin.dokumen');

    // SURAT JALAN ADMIN
    Route::get('/admin/surat-jalan', [AdminSuratJalanController::class, 'index'])
        ->name('admin.surat.jalan');

    Route::get('/admin/surat-jalan/{id}/view', [AdminSuratJalanController::class, 'show'])
        ->name('admin.surat.jalan.show');

    // Notifikasi
    Route::get('/admin/notifikasi', function () {
        return view('admin.notifikasi');
    })->name('admin.notifikasi');

    Route::get('/admin/notifikasi', [AdminNotifikasiController::class, 'index'])
    ->name('admin.notifikasi');

});


require __DIR__.'/auth.php';