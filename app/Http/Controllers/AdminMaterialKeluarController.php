<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Cluster;
use App\Models\TransaksiMaterial;
use App\Models\DokumentasiTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMaterialKeluarController extends Controller
{
    public function index()
    {
        $keluar = TransaksiMaterial::with(['material', 'cluster'])
            ->where('jenis_transaksi', 'keluar')
            ->orderBy('id_transaksi', 'asc')
            ->get();

        return view('admin.material-keluar', compact('keluar'));
    }

    public function create()
    {
        $materials = Material::orderBy('kode_material', 'asc')->get();
        $clusters = Cluster::orderBy('kode_cluster', 'asc')->get();

        return view('admin.tambah-material-keluar', compact('materials', 'clusters'));
    }

    public function store(Request $request)
    {
        foreach ($request->id_material as $index => $id_material) {
            if ($id_material == null) {
                continue;
            }

            $transaksi = TransaksiMaterial::create([
                'id_user'         => Auth::user()->id_user,
                'id_material'     => $id_material,
                'id_cluster'      => $request->id_cluster,
                'jenis_transaksi' => 'keluar',
                'jumlah'          => $request->jumlah[$index],
                'tgl_transaksi'   => $request->tanggal,
                'no_bukti'        => $request->no_bukti,
                'project'         => $request->project,
                'nama_penerima'   => $request->nama_penerima,
                'keterangan'      => $request->keterangan,
            ]);

            if ($request->hasFile('foto_dokumentasi')) {
                foreach ($request->file('foto_dokumentasi') as $foto) {
                    $nama = $foto->getClientOriginalName();
                    $foto->storeAs('foto-dokumentasi', $nama, 'public');

                    DokumentasiTransaksi::create([
                        'id_transaksi'     => $transaksi->id_transaksi,
                        'file_dokumentasi' => 'foto-dokumentasi/' . $nama,
                        'keterangan'       => $request->keterangan,
                        'tgl_upload'       => now(),
                    ]);
                }
            }

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $dokumen) {
                    $nama = $dokumen->getClientOriginalName();
                    $dokumen->storeAs('dokumen-transaksi', $nama, 'public');

                    DokumentasiTransaksi::create([
                        'id_transaksi'     => $transaksi->id_transaksi,
                        'file_dokumentasi' => 'dokumen-transaksi/' . $nama,
                        'keterangan'       => $request->keterangan,
                        'tgl_upload'       => now(),
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.material.keluar')
            ->with('success', 'Material keluar berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = TransaksiMaterial::with('dokumentasiTransaksi')
            ->findOrFail($id);

        $materials = Material::orderBy('kode_material', 'asc')->get();
        $clusters = Cluster::orderBy('kode_cluster', 'asc')->get();

        return view('admin.edit-material-keluar', compact(
            'transaksi',
            'materials',
            'clusters'
        ));
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->update([
            'id_material'    => $request->id_material,
            'id_cluster'     => $request->id_cluster,
            'tgl_transaksi'  => $request->tanggal,
            'no_bukti'       => $request->no_bukti,
            'jumlah'         => $request->jumlah,
            'project'        => $request->project,
            'nama_penerima'  => $request->nama_penerima,
            'keterangan'     => $request->keterangan,
        ]);

        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $nama = $foto->getClientOriginalName();
                $foto->storeAs('foto-dokumentasi', $nama, 'public');

                DokumentasiTransaksi::create([
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'foto-dokumentasi/' . $nama,
                    'keterangan'       => $request->keterangan,
                    'tgl_upload'       => now(),
                ]);
            }
        }

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $dokumen) {
                $nama = $dokumen->getClientOriginalName();
                $dokumen->storeAs('dokumen-transaksi', $nama, 'public');

                DokumentasiTransaksi::create([
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'dokumen-transaksi/' . $nama,
                    'keterangan'       => $request->keterangan,
                    'tgl_upload'       => now(),
                ]);
            }
        }

        return redirect()
            ->route('admin.material.keluar')
            ->with('success', 'Data material keluar berhasil diperbarui.');
    }
}