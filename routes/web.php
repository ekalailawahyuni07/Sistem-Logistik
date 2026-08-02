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
use App\Http\Controllers\AdminVerifikasiUserController;
use App\Http\Controllers\AdminAreaController;
use App\Http\Controllers\AdminProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->id_role == 1) {
        return redirect()->route('admin.dashboard');
    }

    $area = \App\Models\Area::find($user->id_area);

    $queryArea = \App\Models\TransaksiMaterial::where(
        'id_area',
        $user->id_area
    );

    $totalMasuk = (clone $queryArea)
        ->where('transaksi_material.jenis_transaksi', 'masuk')
        ->sum('transaksi_material.jumlah');

    $totalKeluar = (clone $queryArea)
        ->where('transaksi_material.jenis_transaksi', 'keluar')
        ->sum('transaksi_material.jumlah');

    $totalStock = $totalMasuk - $totalKeluar;

    $materialMenipis = \App\Models\Material::query()
        ->join('cluster', 'material.id_cluster', '=', 'cluster.id_cluster')
        ->where('cluster.id_area', $user->id_area)
        ->where('material.stok', '>', 0)
        ->where('material.stok', '<=', 10)
        ->count();

    $totalMaterial = \App\Models\Material::query()
        ->join('cluster', 'material.id_cluster', '=', 'cluster.id_cluster')
        ->where('cluster.id_area', $user->id_area)
        ->count();

    $stokHabis = \App\Models\Material::query()
        ->join('cluster', 'material.id_cluster', '=', 'cluster.id_cluster')
        ->where('cluster.id_area', $user->id_area)
        ->where('material.stok', 0)
        ->count();

    $grafikMasuk = [];
    $grafikKeluar = [];

    $transaksiTerbaru = \App\Models\TransaksiMaterial::where(
        'id_area',
        $user->id_area
    )->max('tgl_transaksi');

    $tanggalAcuan = $transaksiTerbaru
        ? \Carbon\Carbon::parse($transaksiTerbaru)
        : now();

    $tahun = $tanggalAcuan->year;
    $bulan = $tanggalAcuan->month;
    $akhirBulan = $tanggalAcuan->copy()->endOfMonth()->day;

    $rentangMinggu = [
        [1, 7],
        [8, 14],
        [15, 21],
        [22, $akhirBulan],
    ];

    foreach ($rentangMinggu as [$tanggalAwal, $tanggalAkhir]) {
        $awal = \Carbon\Carbon::create(
            $tahun,
            $bulan,
            $tanggalAwal
        )->startOfDay();

        $akhir = \Carbon\Carbon::create(
            $tahun,
            $bulan,
            $tanggalAkhir
        )->endOfDay();

        $grafikMasuk[] = \App\Models\TransaksiMaterial::query()
        ->where('id_area', $user->id_area)
        ->where('jenis_transaksi', 'masuk')
        ->whereBetween('tgl_transaksi', [$awal, $akhir])
        ->sum('jumlah');

        $grafikKeluar[] = \App\Models\TransaksiMaterial::query()
        ->where('id_area', $user->id_area)
        ->where('jenis_transaksi', 'keluar')
        ->whereBetween('tgl_transaksi', [$awal, $akhir])
        ->sum('jumlah');
        }

    return view('user.dashboard', compact(
        'area',
        'totalMasuk',
        'totalKeluar',
        'totalStock',
        'materialMenipis',
        'totalMaterial',
        'stokHabis',
        'grafikMasuk',
        'grafikKeluar'
    ));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/foto', [ProfileController::class, 'deletePhoto'])->name('profile.photo.destroy');
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
    Route::delete('/material-masuk/dokumen/{id}', [MaterialMasukController::class, 'destroyDokumen'])->name('material.masuk.dokumen.destroy');
    Route::delete('/material-masuk/{id}', [MaterialMasukController::class, 'destroy'])->name('material.masuk.destroy');

    Route::get('/material-keluar', [MaterialKeluarController::class, 'index'])->name('material.keluar');
    Route::get('/material-keluar/tambah', [MaterialKeluarController::class, 'create'])->name('material.keluar.create');
    Route::post('/material-keluar/simpan', [MaterialKeluarController::class, 'store'])->name('material.keluar.store');
    Route::get('/material-keluar/{id}/edit', [MaterialKeluarController::class, 'edit'])->name('material.keluar.edit');
    Route::put('/material-keluar/{id}', [MaterialKeluarController::class, 'update'])->name('material.keluar.update');
    Route::delete('/material-keluar/dokumen/{id}', [MaterialKeluarController::class, 'destroyDokumen'])->name('material.keluar.dokumen.destroy');
    Route::delete('/material-keluar/{id}', [MaterialKeluarController::class, 'destroy'])->name('material.keluar.destroy');

    Route::get('/cluster', [ClusterController::class, 'index'])->name('cluster');
    Route::get('/cluster/tambah', [ClusterController::class, 'create'])->name('cluster.create');
    Route::post('/cluster/simpan', [ClusterController::class, 'store'])->name('cluster.store');
    Route::get('/cluster/{id}/edit', [ClusterController::class, 'edit'])->name('cluster.edit');
    Route::put('/cluster/{id}', [ClusterController::class, 'update'])->name('cluster.update');
    Route::get('/cluster/{id}/view', [ClusterController::class, 'show'])->name('cluster.show');

    Route::get('/surat-jalan', [SuratJalanController::class, 'index'])->name('surat.jalan');
    Route::get('/surat-jalan/{id}/view', [SuratJalanController::class, 'show'])->name('surat.jalan.show');

    Route::get('/stok-material', [MaterialController::class, 'stok'])->name('stok.material');
    Route::get('/stok-material/pdf', [MaterialController::class, 'exportStokPdf'])->name('stok.material.pdf');
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
    
    Route::delete(
        '/admin/material-masuk/dokumen/{id}',
        [AdminMaterialMasukController::class, 'destroyDokumen']
    )->name('admin.material.masuk.dokumen.destroy');

    Route::delete('/admin/material-masuk/{id}', [AdminMaterialMasukController::class, 'destroy'])
        ->name('admin.material.masuk.destroy');

    Route::get('/material/project/{project}', [AdminMaterialMasukController::class, 'getMaterialProject']);

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

    Route::delete('/admin/material-keluar/{id}', [AdminMaterialKeluarController::class, 'destroy'])
        ->name('admin.material.keluar.destroy');
    
    Route::delete('/admin/material-keluar/dokumen/{id}',[AdminMaterialKeluarController::class, 'destroyDokumen'])
        ->name('admin.material.keluar.dokumen.destroy');

    Route::get('/admin/get-cluster/{id_area}', [AdminMaterialKeluarController::class, 'getCluster'])
        ->name('admin.get.cluster');
    
    Route::get('/admin/get-project/{id_area}',[AdminMaterialKeluarController::class, 'getProject'])
        ->name('admin.get.project');

    Route::get('/admin/get-material/{id_area}/{project}',[AdminMaterialKeluarController::class, 'getMaterial'])
        ->name('admin.get.material');

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

    Route::delete('/admin/cluster/{id}', [AdminClusterController::class, 'destroy'])
    ->name('admin.cluster.destroy');

    Route::get('/admin/dokumen', [AdminDokumenController::class, 'index'])
        ->name('admin.dokumen');

    // SURAT JALAN ADMIN
    Route::get('/admin/surat-jalan', [AdminSuratJalanController::class, 'index'])
        ->name('admin.surat.jalan');

    Route::get('/admin/surat-jalan/{id}/view', [AdminSuratJalanController::class, 'show'])
        ->name('admin.surat.jalan.show');

        // VERIFIKASI USER ADMIN
    Route::get('/admin/verifikasi-user', [AdminVerifikasiUserController::class, 'index'])
    ->name('admin.verifikasi.user');

    Route::patch('/admin/verifikasi-user/{id}/setujui', [AdminVerifikasiUserController::class, 'setujui'])
    ->name('admin.verifikasi.user.setujui');

    Route::patch('/admin/verifikasi-user/{id}/tolak', [AdminVerifikasiUserController::class, 'tolak'])
    ->name('admin.verifikasi.user.tolak');

    Route::delete('/admin/verifikasi-user/{id}', [AdminVerifikasiUserController::class, 'destroy'])
    ->name('admin.verifikasi.user.destroy');

    // =============================
    // KELOLA AREA
    // =============================

    Route::get('/admin/kelola-area', [AdminAreaController::class, 'index'])
    ->name('admin.kelola.area');

    Route::post('/admin/kelola-area/store', [AdminAreaController::class, 'storeArea'])
    ->name('admin.kelola.area.store');

    Route::put('/admin/kelola-area/{id}/update', [AdminAreaController::class, 'updateArea'])
    ->name('admin.kelola.area.update');

    Route::delete('/admin/kelola-area/{id}/delete', [AdminAreaController::class, 'destroyArea'])
    ->name('admin.kelola.area.destroy');

    Route::post('/admin/kelola-area/{idArea}/cluster/store', [AdminAreaController::class, 'storeCluster'])
    ->name('admin.kelola.area.cluster.store');

    Route::put('/admin/kelola-area/cluster/{idCluster}/update', [AdminAreaController::class, 'updateCluster'])
    ->name('admin.kelola.area.cluster.update');

    Route::delete('/admin/kelola-area/cluster/{idCluster}/delete', [AdminAreaController::class, 'destroyCluster'])
    ->name('admin.kelola.area.cluster.destroy');

    // Notifikasi
    Route::get('/admin/notifikasi', function () {
        return view('admin.notifikasi');
    })->name('admin.notifikasi');

    Route::get('/admin/notifikasi', [AdminNotifikasiController::class, 'index'])
    ->name('admin.notifikasi');

    //Profile Admin
    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])
        ->name('admin.profile.edit');

    Route::patch('/admin/profile', [AdminProfileController::class, 'update'])
        ->name('admin.profile.update');

    Route::put('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])
        ->name('admin.profile.password');

    Route::get('/admin/stok-material/pdf', [AdminStokMaterialController::class, 'exportPdf'])
        ->name('admin.stok.material.pdf');
});


require __DIR__.'/auth.php';