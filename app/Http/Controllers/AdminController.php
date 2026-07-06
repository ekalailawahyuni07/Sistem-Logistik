<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMasuk = TransaksiMaterial::where('jenis_transaksi', 'masuk')->sum('jumlah');

        $totalKeluar = TransaksiMaterial::where('jenis_transaksi', 'keluar')->sum('jumlah');

        $totalStock = $totalMasuk - $totalKeluar;

        return view('admin.dashboard', compact(
            'totalMasuk',
            'totalKeluar',
            'totalStock'
        ));
    }
}