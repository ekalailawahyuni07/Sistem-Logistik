<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\DokumentasiTransaksi;

class AdminDokumenController extends Controller
{
    public function index(Request $request)
    {
        $idArea = $request->id_area;

        // Untuk dropdown filter
        $allAreas = Area::orderBy('nama_area')->get();

        // Untuk data yang ditampilkan
        if ($idArea) {

            $areas = Area::where('id_area', $idArea)
                        ->orderBy('nama_area')
                        ->get();

        } else {

            $areas = Area::orderBy('nama_area')
                        ->get();

        }

        foreach ($areas as $area) {

            $area->dokumen = DokumentasiTransaksi::with([
                    'transaksiMaterial.material'
                ])
                ->whereHas('transaksiMaterial', function ($q) use ($area) {

                    $q->where('id_area', $area->id_area);

                })
                ->orderBy('tgl_upload', 'desc')
                ->get();

        }

        return view(
            'admin.dokumen',
            compact('areas', 'allAreas')
        );
    }
}