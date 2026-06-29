    <?php

    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\MaterialController;
    use App\Http\Controllers\MaterialMasukController;
    use App\Http\Controllers\ClusterController;
    use App\Http\Controllers\MaterialKeluarController;

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

        // Data Material
        Route::get('/data-material', [MaterialController::class, 'index'])->name('data.material');
        Route::get('/data-material/tambah', [MaterialController::class, 'create'])->name('data.material.create');
        Route::post('/data-material/simpan', [MaterialController::class, 'store'])->name('data.material.store');
        Route::get('/data-material/{id}/edit', [MaterialController::class, 'edit'])->name('data.material.edit');
        Route::put('/data-material/{id}/update', [MaterialController::class, 'update'])->name('data.material.update');

        // Material Masuk
        Route::get('/material-masuk', [MaterialMasukController::class, 'index'])->name('material.masuk');
        Route::get('/material-masuk/tambah', [MaterialMasukController::class, 'create'])->name('material.masuk.create');
        Route::post('/material-masuk/simpan', [MaterialMasukController::class, 'store'])->name('material.masuk.store');

        Route::get('/material-masuk/{id}/edit', [MaterialMasukController::class, 'edit'])
        ->name('material.masuk.edit');
        Route::put('/material-masuk/{id}', [MaterialMasukController::class, 'update'])
        ->name('material.masuk.update');

        // Material Keluar
        Route::get('/material-keluar', [MaterialKeluarController::class, 'index'])
            ->name('material.keluar');

        Route::get('/material-keluar/tambah', [MaterialKeluarController::class, 'create'])
            ->name('material.keluar.create');

        Route::post('/material-keluar/simpan', [MaterialKeluarController::class, 'store'])
            ->name('material.keluar.store');

        // Cluster
        Route::get('/cluster', [ClusterController::class, 'index'])->name('cluster');
        Route::get('/cluster/tambah', [ClusterController::class, 'create'])->name('cluster.create');
        Route::post('/cluster/simpan', [ClusterController::class, 'store'])->name('cluster.store');
        Route::get('/cluster/{id}/edit', [ClusterController::class, 'edit'])
            ->name('cluster.edit');

        Route::put('/cluster/{id}', [ClusterController::class, 'update'])
            ->name('cluster.update');

        Route::get('/cluster/{id}/view', [ClusterController::class, 'show'])
            ->name('cluster.show');    

        // Surat Jalan
        Route::get('/surat-jalan', function () {
            return view('user.surat-jalan');
        })->name('surat.jalan');

        // Stok Material
        Route::get('/stok-material', function () {
            return view('user.stok-material');
        })->name('stok.material');

    });

    require __DIR__.'/auth.php';