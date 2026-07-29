<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Cluster;
use Illuminate\Http\Request;

class AdminAreaController extends Controller
{
    public function index()
    {
        $areas = Area::with([
            'clusters' => function ($query) {
                $query->orderBy('id_cluster', 'asc');
            }
        ])
        ->orderBy('id_area', 'asc')
        ->get();

        return view('admin.kelola-area', compact('areas'));
    }

    public function storeArea(Request $request)
    {
        $request->validate([
            'nama_area' => [
                'required',
                'string',
                'max:100',
                'unique:area,nama_area',
            ],
        ], [
            'nama_area.required' => 'Nama area wajib diisi.',
            'nama_area.unique' => 'Nama area sudah digunakan.',
        ]);

        Area::create([
            'nama_area' => $request->nama_area,
        ]);

        return redirect()
            ->route('admin.kelola.area')
            ->with('success', 'Area berhasil ditambahkan.');
    }

    public function updateArea(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'nama_area' => [
                'required',
                'string',
                'max:100',
                'unique:area,nama_area,' . $area->id_area . ',id_area',
            ],
        ], [
            'nama_area.required' => 'Nama area wajib diisi.',
            'nama_area.unique' => 'Nama area sudah digunakan.',
        ]);

        $area->update([
            'nama_area' => $request->nama_area,
        ]);

        return redirect()
            ->route('admin.kelola.area')
            ->with('success', 'Area berhasil diperbarui.');
    }

    public function destroyArea($id)
    {
        $area = Area::findOrFail($id);

        if ($area->users()->exists()) {
            return redirect()
                ->route('admin.kelola.area')
                ->with('error', 'Area tidak dapat dihapus karena masih digunakan oleh user.');
        }

        if ($area->clusters()->exists()) {
            return redirect()
                ->route('admin.kelola.area')
                ->with('error', 'Area tidak dapat dihapus karena masih memiliki cluster.');
        }

        $area->delete();

        return redirect()
            ->route('admin.kelola.area')
            ->with('success', 'Area berhasil dihapus.');
    }

    public function storeCluster(Request $request, $idArea)
    {
        $area = Area::findOrFail($idArea);

        $request->validate([
            'kode_cluster' => [
                'required',
                'string',
                'max:50',
                'unique:cluster,kode_cluster',
            ],
            'nama_cluster' => [
                'required',
                'string',
                'max:100',
            ],
        ], [
            'kode_cluster.required' => 'Kode cluster wajib diisi.',
            'kode_cluster.unique' => 'Kode cluster sudah digunakan.',
            'nama_cluster.required' => 'Nama cluster wajib diisi.',
        ]);

        Cluster::create([
            'id_area' => $area->id_area,
            'kode_cluster' => $request->kode_cluster,
            'nama_cluster' => $request->nama_cluster,
        ]);

        return redirect()
            ->route('admin.kelola.area')
            ->with(
                'success',
                'Cluster berhasil ditambahkan ke area ' . $area->nama_area . '.'
            );
    }

    public function updateCluster(Request $request, $idCluster)
    {
        $cluster = Cluster::findOrFail($idCluster);

        $request->validate([
            'kode_cluster' => [
                'required',
                'string',
                'max:50',
                'unique:cluster,kode_cluster,' . $cluster->id_cluster . ',id_cluster',
            ],
            'nama_cluster' => [
                'required',
                'string',
                'max:100',
            ],
        ], [
            'kode_cluster.required' => 'Kode cluster wajib diisi.',
            'kode_cluster.unique' => 'Kode cluster sudah digunakan.',
            'nama_cluster.required' => 'Nama cluster wajib diisi.',
        ]);

        $cluster->update([
            'kode_cluster' => $request->kode_cluster,
            'nama_cluster' => $request->nama_cluster,
        ]);

        return redirect()
            ->route('admin.kelola.area')
            ->with('success', 'Cluster berhasil diperbarui.');
    }

    public function destroyCluster($idCluster)
    {
        $cluster = Cluster::findOrFail($idCluster);

        $memilikiTransaksi = \App\Models\TransaksiMaterial::where(
            'id_cluster',
            $cluster->id_cluster
        )->exists();

        if ($memilikiTransaksi) {
            return redirect()
                ->route('admin.kelola.area')
                ->with(
                    'error',
                    'Cluster tidak dapat dihapus karena masih memiliki transaksi material.'
                );
        }

        $cluster->delete();

        return redirect()
            ->route('admin.kelola.area')
            ->with('success', 'Cluster berhasil dihapus.');
    }
}