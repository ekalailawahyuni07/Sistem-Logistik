<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller
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

        return view('admin.data-material', compact('materials', 'projects'));
    }

    public function create()
    {
        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        return view(
            'admin.tambah-material',
            compact('projects')
        );
    }

    public function store(Request $request)
    {
        Material::create([
            'kode_material'  => $request->kode_material,
            'nama_material'  => $request->nama_material,
            'project'        => $request->project,
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

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        return view(
            'admin.edit-material',
            compact(
                'material',
                'projects'
            )
        );
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