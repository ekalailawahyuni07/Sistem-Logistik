<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;

class SuratJalanController extends Controller
{
    public function index()
    {
        $suratJalan = TransaksiMaterial::with(['material', 'cluster'])
            ->where('jenis_transaksi', 'keluar')
            ->orderBy('id_transaksi', 'asc')
            ->get()
            ->unique('no_bukti');

        return view('user.surat-jalan', compact('suratJalan'));
    }

    public function show($id)
    {
        $transaksi = TransaksiMaterial::with(['material', 'cluster'])
            ->findOrFail($id);

        $items = TransaksiMaterial::with(['material', 'cluster'])
            ->where('jenis_transaksi', 'keluar')
            ->where('no_bukti', $transaksi->no_bukti)
            ->orderBy('id_transaksi', 'asc')
            ->get();

        return view('user.detail-surat-jalan', compact('transaksi', 'items'));
    }
}