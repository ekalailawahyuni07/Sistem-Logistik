<?php

namespace App\Http\Controllers;

use App\Models\Material;

class AdminStokMaterialController extends Controller
{
    public function index()
    {
        $materials = Material::withSum(
            ['transaksiMaterial as total_masuk' => function ($q) {
                $q->where('jenis_transaksi', 'masuk');
            }],
            'jumlah'
        )
        ->withSum(
            ['transaksiMaterial as total_keluar' => function ($q) {
                $q->where('jenis_transaksi', 'keluar');
            }],
            'jumlah'
        )
        ->orderBy('kode_material')
        ->get();

        $totalMaterial = $materials->count();

        $totalMasuk = $materials->sum('total_masuk');

        $totalKeluar = $materials->sum('total_keluar');

        $totalStock = $materials->sum(function ($item) {
            return ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
        });

        $stokMenipis = $materials->filter(function ($item) {
            $stock = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);

            return $stock > 0 && $stock <= 10;
        })->count();

        return view('admin.stok-material', compact(
            'materials',
            'totalMaterial',
            'totalMasuk',
            'totalKeluar',
            'totalStock',
            'stokMenipis'
        ));
    }
}