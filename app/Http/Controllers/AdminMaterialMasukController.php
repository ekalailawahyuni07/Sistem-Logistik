<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\TransaksiMaterial;
use App\Models\DokumentasiTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Cluster;
use App\Models\Area;

class AdminMaterialMasukController extends Controller
{
    public function index()
    {
        $areas = Area::with([
            'transaksiMaterial' => function ($query) {

                $query->where('jenis_transaksi', 'masuk')
                    ->with('material')
                    ->orderBy('id_transaksi', 'asc');

            }
        ])
        ->orderBy('nama_area')
        ->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        return view(
            'admin.material-masuk',
            compact('areas', 'projects')
        );
    }

    public function create()
    {
        $materials = Material::orderBy('nama_material')->get();

        $areas = Area::orderBy('nama_area')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project','!=','')
            ->distinct()
            ->orderBy('project')
            ->get();

        return view(
            'admin.tambah-material-masuk',
            compact(
                'materials',
                'projects',
                'areas'
            )
        );
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
                'id_area' => $request->id_area,
                'jenis_transaksi' => 'masuk',
                'jumlah'          => $request->jumlah[$index],
                'tgl_transaksi'   => $request->tanggal,
                'no_bukti'        => $request->no_bukti,
                'project'         => $request->project,
                'nama_penerima'   => $request->nama_penerima,
                'keterangan'      => $request->keterangan[$index] ?? null,
            ]);

            if ($request->hasFile('foto_dokumentasi')) {
                foreach ($request->file('foto_dokumentasi') as $foto) {

                    $nama = $foto->getClientOriginalName();

                    $foto->storeAs('foto-dokumentasi', $nama, 'public');

                    DokumentasiTransaksi::create([
                        'id_transaksi' => $transaksi->id_transaksi,
                        'file_dokumentasi' => 'foto-dokumentasi/' . $nama,
                        'keterangan' => '',
                        'tgl_upload' => now(),
                    ]);
                }
            }

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $dokumen) {

                    $nama = $dokumen->getClientOriginalName();

                    $dokumen->storeAs('dokumen-transaksi', $nama, 'public');

                    DokumentasiTransaksi::create([
                        'id_transaksi' => $transaksi->id_transaksi,
                        'file_dokumentasi' => 'dokumen-transaksi/' . $nama,
                        'keterangan' => '',
                        'tgl_upload' => now(),
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.material.masuk')
            ->with('success', 'Material masuk berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = TransaksiMaterial::with([
            'material',
            'area',
            'dokumentasi'
        ])->findOrFail($id);

        $materials = Material::orderBy('kode_material')->get();

        $areas = Area::orderBy('nama_area')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        return view(
            'admin.edit-material-masuk',
            compact(
                'transaksi',
                'materials',
                'projects',
                'areas'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->update([
            'id_area'       => $request->id_area,
            'id_material'   => $request->id_material,
            'tgl_transaksi' => $request->tanggal,
            'no_bukti'      => $request->no_bukti,
            'project'       => $request->project,
            'jumlah'        => $request->jumlah,
            'keterangan'    => $request->keterangan,
        ]);

        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {

                $nama = $foto->getClientOriginalName();

                $foto->storeAs('foto-dokumentasi', $nama, 'public');

                DokumentasiTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'foto-dokumentasi/' . $nama,
                    'keterangan' => '',
                    'tgl_upload' => now(),
                ]);
            }
        }

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $dokumen) {

                $nama = $dokumen->getClientOriginalName();

                $dokumen->storeAs('dokumen-transaksi', $nama, 'public');

                DokumentasiTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'dokumen-transaksi/' . $nama,
                    'keterangan' => '',
                    'tgl_upload' => now(),
                ]);
            }
        }

        return redirect()
            ->route('admin.material.masuk')
            ->with('success', 'Data material masuk berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->delete();

        return redirect()
            ->route('admin.material.masuk')
            ->with('success', 'Data material masuk berhasil dihapus.');
    }
    public function destroyDokumen($id)
    {
        $dokumen = DokumentasiTransaksi::findOrFail($id);

        if (
            $dokumen->file_dokumentasi &&
            Storage::disk('public')->exists($dokumen->file_dokumentasi)
        ) {
            Storage::disk('public')->delete($dokumen->file_dokumentasi);
        }

        $dokumen->delete();

        return back()->with(
            'success',
            'Dokumen berhasil dihapus.'
        );
    }
    public function getMaterialProject($project)
    {
        $materials = Material::where('project', $project)
            ->orderBy('nama_material')
            ->get();

        return response()->json($materials);
    }
}