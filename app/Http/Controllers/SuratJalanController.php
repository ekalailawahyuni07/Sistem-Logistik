<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;

class SuratJalanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $suratJalan = TransaksiMaterial::with([
                'material',
                'cluster',
                'user'
            ])
            ->where('jenis_transaksi', 'keluar')
            ->where(function ($query) use ($user) {
                $query->where('id_area', $user->id_area)
                    ->orWhereHas('cluster', function ($q) use ($user) {
                        $q->where('id_area', $user->id_area);
                    });
            })
            ->orderBy('id_transaksi', 'asc')
            ->get()
            ->unique('no_bukti');

        $projects = \App\Models\Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        return view('user.surat-jalan', compact('suratJalan', 'projects'));
    }

    public function show($id)
    {
        $transaksi = TransaksiMaterial::with([
                'material',
                'cluster',
                'user'
            ])
            ->findOrFail($id);

        $areaId = $transaksi->id_area ?? auth()->user()->id_area;

        $items = TransaksiMaterial::with([
                'material',
                'cluster',
                'user'
            ])
            ->where('jenis_transaksi', 'keluar')
            ->where(function ($query) use ($areaId) {
                $query->where('id_area', $areaId)
                    ->orWhereHas('cluster', function ($q) use ($areaId) {
                        $q->where('id_area', $areaId);
                    });
            })
            ->where('no_bukti', $transaksi->no_bukti)
            ->orderBy('id_transaksi', 'asc')
            ->get();

        return view('user.detail-surat-jalan', compact('transaksi', 'items'));
    }
}