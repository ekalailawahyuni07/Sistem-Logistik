<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\TransaksiMaterial;

class AdminSuratJalanController extends Controller
{
    public function index(Request $request)
    {
        $idArea = $request->id_area;

        // untuk dropdown
        $allAreas = Area::orderBy('nama_area')->get();

        // area yang ditampilkan
        if ($idArea) {

            $areas = Area::where('id_area', $idArea)->get();

        } else {

            $areas = Area::orderBy('nama_area')->get();

        }

        foreach ($areas as $area) {

            $area->suratJalan = TransaksiMaterial::with([
                    'material',
                    'cluster',
                    'user'
                ])
                ->where('jenis_transaksi', 'keluar')
                ->where('id_area', $area->id_area)
                ->orderBy('id_transaksi')
                ->get()
                ->unique(function ($item) {
                    return $item->id_area.'-'.$item->no_bukti;
                })
                ->values();

        }

        return view(
            'admin.surat-jalan',
            compact('areas','allAreas')
        );
    }
    public function show($id)
    {
        $transaksi = TransaksiMaterial::with([
                'material',
                'cluster',
                'user'
            ])
            ->findOrFail($id);

        $items = TransaksiMaterial::with([
            'material',
            'cluster',
            'user'
        ])
        ->where('jenis_transaksi', 'keluar')
        ->where('id_area', $transaksi->id_area)
        ->where('no_bukti', $transaksi->no_bukti)
        ->orderBy('id_transaksi', 'asc')
        ->get();

        return view('admin.surat-jalan-detail', compact('transaksi', 'items'));
    }
}