<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('id_material', 'asc')->get();

        return view('user.data-material', compact('materials'));
    }

    public function create()
    {
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

        return redirect()
            ->route('data.material')
            ->with('success', 'Data material berhasil disimpan.');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);

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

        return redirect()
            ->route('data.material')
            ->with('success', 'Data material berhasil diperbarui.');
    }
}