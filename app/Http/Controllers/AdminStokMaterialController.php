<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Area;
use Illuminate\Http\Request;

class AdminStokMaterialController extends Controller
{
    public function index(Request $request)
    {
        $idArea = $request->id_area;
        $allAreas = Area::orderBy('nama_area')->get();
        if ($idArea) {
            $areas = Area::where('id_area', $idArea)
                        ->orderBy('nama_area')
                        ->get();
        } else {
            $areas = Area::orderBy('nama_area')
                        ->get();
        }

        foreach ($areas as $area) {

            $materials = Material::withSum([
                'transaksiMaterial as total_masuk' => function ($q) use ($area) {
                    $q->where('jenis_transaksi', 'masuk')
                    ->where('id_area', $area->id_area);
                }
            ], 'jumlah')

            ->withSum([
                'transaksiMaterial as total_keluar' => function ($q) use ($area) {
                    $q->where('jenis_transaksi', 'keluar')
                    ->where('id_area', $area->id_area);
                }
            ], 'jumlah')

            ->orderBy('kode_material')
            ->get()

            ->filter(function ($item) {

                return ($item->total_masuk ?? 0) > 0
                    || ($item->total_keluar ?? 0) > 0;

            })

            ->values();

            foreach ($materials as $m) {
                $transaksiProject = \App\Models\TransaksiMaterial::where('id_material', $m->id_material)
                    ->where('jenis_transaksi', 'masuk')
                    ->where('id_area', $area->id_area)
                    ->whereNotNull('project')
                    ->where('project', '!=', '')
                    ->orderBy('id_transaksi', 'desc')
                    ->value('project');

                $m->project_display = $transaksiProject ?: $m->project;
            }

            $area->materials = $materials;

            $area->total_stock = $materials->sum(function ($m) {

                return ($m->total_masuk ?? 0) - ($m->total_keluar ?? 0);

            });

        }

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        return view(
            'admin.stok-material',
            compact(
                'areas',
                'allAreas',
                'projects'
            )
        );
    }

    public function exportPdf(Request $request)
    {
        $idArea = $request->id_area;

        if ($idArea) {

            $areas = Area::where('id_area', $idArea)
                        ->orderBy('nama_area')
                        ->get();

        } else {

            $areas = Area::orderBy('nama_area')->get();

        }

        foreach ($areas as $area) {

            $materials = Material::withSum([
                'transaksiMaterial as total_masuk' => function ($q) use ($area) {

                    $q->where('jenis_transaksi','masuk')
                    ->where('id_area',$area->id_area);

                }
            ],'jumlah')

            ->withSum([
                'transaksiMaterial as total_keluar' => function ($q) use ($area) {

                    $q->where('jenis_transaksi','keluar')
                    ->where('id_area',$area->id_area);

                }
            ],'jumlah')

            ->orderBy('kode_material')
            ->get()

            ->filter(function($m){

                return ($m->total_masuk ?? 0) > 0
                    || ($m->total_keluar ?? 0) > 0;

            })

            ->values();

            $area->materials = $materials;

            $area->total_stock = $materials->sum(function($m){

                return ($m->total_masuk ?? 0)
                    - ($m->total_keluar ?? 0);

            });

        }

        $pdf = Pdf::loadView(
            'admin.pdf.stok-material',
            compact('areas')
        );

        return $pdf->download('Laporan_Stok_Material.pdf');
    }
}