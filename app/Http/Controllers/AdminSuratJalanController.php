<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;

class AdminSuratJalanController extends Controller
{
    public function index()
    {
        $suratJalan = TransaksiMaterial::with(['material', 'cluster'])
            ->where('jenis_transaksi', 'keluar')
            ->orderBy('id_transaksi', 'asc')
            ->get()
            ->unique('no_bukti');

        return view('admin.surat-jalan', compact('suratJalan'));
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

        return view('admin.surat-jalan-detail', compact('transaksi', 'items'));
    }
}