<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiTransaksi;

class DokumenController extends Controller
{
    public function index()
    {
        $dokumen = DokumentasiTransaksi::with('transaksiMaterial.material')
            ->orderBy('id_dokumentasi', 'desc')
            ->get();

        return view('user.dokumen', compact('dokumen'));
    }
}