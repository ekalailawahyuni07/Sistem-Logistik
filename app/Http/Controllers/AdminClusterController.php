<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;
use App\Models\Area;

class AdminClusterController extends Controller
{
    public function index()
    {
        $clusters = Cluster::with('area')
            ->orderBy('id_cluster')
            ->get();

        $areas = Area::orderBy('nama_area')->get();
        return view('admin.cluster', compact('clusters', 'areas'));
    }

    public function create()
    {
        return view('admin.tambah-cluster');
    }

    public function store(Request $request)
    {
        Cluster::create([
            'kode_cluster' => $request->kode_cluster,
            'nama_cluster' => $request->nama_cluster,
        ]);

        return redirect()
            ->route('admin.cluster')
            ->with('success', 'Cluster berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cluster = Cluster::findOrFail($id);

        return view('admin.edit-cluster', compact('cluster'));
    }

    public function update(Request $request, $id)
    {
        $cluster = Cluster::findOrFail($id);

        $cluster->update([
            'kode_cluster' => $request->kode_cluster,
            'nama_cluster' => $request->nama_cluster,
        ]);

        return redirect()
            ->route('admin.cluster')
            ->with('success', 'Cluster berhasil diperbarui.');
    }

    public function show($id)
    {
        $cluster = Cluster::with('area')->findOrFail($id);

        $materialKeluar = \App\Models\TransaksiMaterial::with('material')
            ->where('jenis_transaksi', 'keluar')
            ->where('id_cluster', $id)
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('admin.view-cluster', compact('cluster', 'materialKeluar'));
    }

    public function destroy($id)
    {
        $cluster = Cluster::findOrFail($id);

        $cluster->delete();

        return redirect()
            ->route('admin.cluster')
            ->with('success', 'Cluster berhasil dihapus.');
    }
}