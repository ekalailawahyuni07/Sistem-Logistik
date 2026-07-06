<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    public function index()
    {
        $clusters = Cluster::orderBy('id_cluster', 'asc')->get();

        return view('user.cluster', compact('clusters'));
    }

    public function create()
    {
        return view('user.tambah-cluster');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cluster' => 'required|unique:cluster,kode_cluster',
            'nama_cluster' => 'required',
        ]);

        Cluster::create([
            'id_area'       => 1,
            'kode_cluster'  => $request->kode_cluster,
            'nama_cluster'  => $request->nama_cluster,
        ]);

        return redirect()
            ->route('cluster')
            ->with('success', 'Cluster berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cluster = Cluster::findOrFail($id);

        return view('user.edit-cluster', compact('cluster'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_cluster' => 'required|unique:cluster,kode_cluster,' . $id . ',id_cluster',
            'nama_cluster' => 'required',
        ]);

        $cluster = Cluster::findOrFail($id);

        $cluster->update([
            'kode_cluster' => $request->kode_cluster,
            'nama_cluster' => $request->nama_cluster,
        ]);

        return redirect()
            ->route('cluster')
            ->with('success', 'Cluster berhasil diperbarui.');
    }

    public function show($id)
    {
        $cluster = Cluster::findOrFail($id);

        $materialKeluar = \App\Models\TransaksiMaterial::with('material')
            ->where('jenis_transaksi', 'keluar')
            ->where('id_cluster', $id)
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('user.view-cluster', compact('cluster', 'materialKeluar'));
    }
}