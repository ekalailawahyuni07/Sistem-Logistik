<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiTransaksi;

class AdminDokumenController extends Controller
{
    public function index()
    {
        $dokumen = DokumentasiTransaksi::with([
            'transaksiMaterial.material'
        ])
        ->orderBy('tgl_upload', 'desc')
        ->get();

        return view('admin.dokumen', compact('dokumen'));
    }
}