<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('id_material', 'asc')->get();
        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        if (request()->is('admin/*')) {
            return view('admin.data-material', compact('materials', 'projects'));
        }

        return view('user.data-material', compact('materials', 'projects'));
    }

    public function create()
    {
        if (request()->is('admin/*')) {
            return view('admin.tambah-material');
        }

        return view('user.tambah-material');
    }

    public function store(Request $request)
    {
        Material::create([
            'id_cluster'     => 1,
            'kode_material'  => $request->kode_material,
            'nama_material'  => $request->nama_material,
            'project'        => $request->project,
            'jenis_material' => $request->jenis_material,
            'satuan'         => $request->satuan,
            'keterangan'     => $request->keterangan,
        ]);

        if (request()->is('admin/*')) {
            return redirect()
                ->route('admin.data.material')
                ->with('success', 'Data material berhasil disimpan.');
        }

        return redirect()
            ->route('data.material')
            ->with('success', 'Data material berhasil disimpan.');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);

        if (request()->is('admin/*')) {
            return view('admin.edit-material', compact('material'));
        }

        return view('user.edit-material', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $material->update([
            'kode_material'  => $request->kode_material,
            'nama_material'  => $request->nama_material,
            'project'        => $request->project,
            'jenis_material' => $request->jenis_material,
            'satuan'         => $request->satuan,
            'keterangan'     => $request->keterangan,
        ]);

        if (request()->is('admin/*')) {
            return redirect()
                ->route('admin.data.material')
                ->with('success', 'Data material berhasil diperbarui.');
        }

        return redirect()
            ->route('data.material')
            ->with('success', 'Data material berhasil diperbarui.');
    }

    public function stok()
    {
        $user = auth()->user();

        $materials = Material::withSum(
            ['transaksiMaterial as total_masuk' => function ($q) use ($user) {
                $q->where('jenis_transaksi', 'masuk');
                if ($user && $user->id_role != 1) {
                    $q->where(function ($query) use ($user) {
                        $query->where('id_area', $user->id_area)
                            ->orWhereHas('cluster', function ($c) use ($user) {
                                $c->where('id_area', $user->id_area);
                            });
                    });
                }
            }],
            'jumlah'
        )
        ->withSum(
            ['transaksiMaterial as total_keluar' => function ($q) use ($user) {
                $q->where('jenis_transaksi', 'keluar');
                if ($user && $user->id_role != 1) {
                    $q->where(function ($query) use ($user) {
                        $query->where('id_area', $user->id_area)
                            ->orWhereHas('cluster', function ($c) use ($user) {
                                $c->where('id_area', $user->id_area);
                            });
                    });
                }
            }],
            'jumlah'
        )
        ->orderBy('kode_material')
        ->get();

        // Hanya sertakan material yang memiliki stok tersedia (> 0)
        $materials = $materials->filter(function ($item) {
            $stock = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
            return $stock > 0;
        });

        $totalMaterial = $materials->count();
        $totalMasuk    = $materials->sum('total_masuk');
        $totalKeluar   = $materials->sum('total_keluar');

        $totalStock = $materials->sum(function ($item) {
            return ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
        });

        $stokMenipis = $materials->filter(function ($item) {
            $stock = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
            return $stock > 0 && $stock <= 10;
        })->count();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        if (request()->is('admin/*')) {
            return view('admin.stok-material', compact(
                'materials',
                'totalMaterial',
                'totalMasuk',
                'totalKeluar',
                'totalStock',
                'stokMenipis',
                'projects'
            ));
        }

        return view('user.stok-material', compact(
            'materials',
            'totalMaterial',
            'totalMasuk',
            'totalKeluar',
            'totalStock',
            'stokMenipis',
            'projects'
        ));
    }

    public function exportStokPdf(Request $request)
    {
        $user = auth()->user();
        $selectedProject = $request->project;

        $materialsQuery = Material::withSum(
            ['transaksiMaterial as total_masuk' => function ($q) use ($user) {
                $q->where('jenis_transaksi', 'masuk');
                if ($user && $user->id_role != 1) {
                    $q->where(function ($query) use ($user) {
                        $query->where('id_area', $user->id_area)
                            ->orWhereHas('cluster', function ($c) use ($user) {
                                $c->where('id_area', $user->id_area);
                            });
                    });
                }
            }],
            'jumlah'
        )
        ->withSum(
            ['transaksiMaterial as total_keluar' => function ($q) use ($user) {
                $q->where('jenis_transaksi', 'keluar');
                if ($user && $user->id_role != 1) {
                    $q->where(function ($query) use ($user) {
                        $query->where('id_area', $user->id_area)
                            ->orWhereHas('cluster', function ($c) use ($user) {
                                $c->where('id_area', $user->id_area);
                            });
                    });
                }
            }],
            'jumlah'
        );

        if ($selectedProject) {
            $materialsQuery->where('project', $selectedProject);
        }

        $materials = $materialsQuery->orderBy('kode_material')->get();

        $materials = $materials->filter(function ($item) {
            $stock = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
            return $stock > 0;
        });

        $namaArea = $user->area->nama_area ?? 'Area';

        $pdf = Pdf::loadView('user.pdf-stok-material', compact('user', 'materials', 'selectedProject', 'namaArea'));
        return $pdf->download("Laporan_Stok_Material_{$namaArea}.pdf");
    }
}