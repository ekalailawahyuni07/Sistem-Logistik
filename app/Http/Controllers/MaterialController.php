<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('id_material', 'asc')->get();

        if (request()->is('admin/*')) {
            return view('admin.data-material', compact('materials'));
        }

        return view('user.data-material', compact('materials'));
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
        $materials = Material::withSum(
            ['transaksiMaterial as total_masuk' => function ($q) {
                $q->where('jenis_transaksi', 'masuk');
            }],
            'jumlah'
        )
        ->withSum(
            ['transaksiMaterial as total_keluar' => function ($q) {
                $q->where('jenis_transaksi', 'keluar');
            }],
            'jumlah'
        )
        ->orderBy('kode_material')
        ->get();

        $totalMaterial = $materials->count();
        $totalMasuk = $materials->sum('total_masuk');
        $totalKeluar = $materials->sum('total_keluar');

        $totalStock = $materials->sum(function ($item) {
            return ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
        });

        $stokMenipis = $materials->filter(function ($item) {
            $stock = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
            return $stock > 0 && $stock <= 10;
        })->count();

        if (request()->is('admin/*')) {
            return view('admin.stok-material', compact(
                'materials',
                'totalMaterial',
                'totalMasuk',
                'totalKeluar',
                'totalStock',
                'stokMenipis'
            ));
        }

        return view('user.stok-material', compact(
            'materials',
            'totalMaterial',
            'totalMasuk',
            'totalKeluar',
            'totalStock',
            'stokMenipis'
        ));
    }
}