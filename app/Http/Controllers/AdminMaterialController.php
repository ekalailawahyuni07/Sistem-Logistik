<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('id_material', 'asc')->get();

        return view('admin.data-material', compact('materials'));
    }

    public function create()
    {
        return view('admin.tambah-material');
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
            ->route('admin.data.material')
            ->with('success', 'Data material berhasil ditambahkan oleh admin.');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);

        return view('admin.edit-material', compact('material'));
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
            ->route('admin.data.material')
            ->with('success', 'Data material berhasil diperbarui oleh admin.');
    }
    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        $material->delete();

        return redirect()
            ->route('admin.data.material')
            ->with('success', 'Data material berhasil dihapus.');
    }
}