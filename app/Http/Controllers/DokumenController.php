<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiTransaksi;

class DokumenController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $dokumen = DokumentasiTransaksi::with(['transaksiMaterial.material', 'transaksiMaterial.cluster'])
            ->whereHas('transaksiMaterial', function ($query) use ($user) {
                $query->where('id_area', $user->id_area)
                    ->orWhereHas('cluster', function ($q) use ($user) {
                        $q->where('id_area', $user->id_area);
                    });
            })
            ->orderBy('id_dokumentasi', 'desc')
            ->get();

        return view('user.dokumen', compact('dokumen'));
    }
}