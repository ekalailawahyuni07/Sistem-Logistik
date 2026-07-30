<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Area;
use App\Models\Cluster;
use App\Models\TransaksiMaterial;
use App\Models\DokumentasiTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminMaterialKeluarController extends Controller
{
    public function index()
    {
        $areas = Area::with([
            'transaksiMaterial' => function ($query) {

                $query->where('jenis_transaksi', 'keluar')
                    ->with(['material', 'cluster'])
                    ->orderBy('id_transaksi', 'asc');

            }
        ])->orderBy('nama_area')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        return view('admin.material-keluar', compact('areas', 'projects'));
    }

    public function create()
    {
        $materials = Material::orderBy('kode_material', 'asc')->get();

        $areas = Area::orderBy('nama_area', 'asc')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        return view(
            'admin.tambah-material-keluar',
            compact(
                'materials',
                'areas',
                'projects'
            )
        );
    }

    public function getCluster($id_area)
    {
        $clusters = Cluster::where('id_area', $id_area)
            ->orderBy('nama_cluster', 'asc')
            ->get();

        return response()->json($clusters);
    }

    public function getProject($id_area)
    {
        $projects = TransaksiMaterial::where('jenis_transaksi', 'masuk')
            ->where('id_area', $id_area)
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->select('project')
            ->distinct()
            ->orderBy('project')
            ->get();

        return response()->json($projects);
    }

    public function getMaterial($id_area, $project)
    {
        $materials = TransaksiMaterial::with('material')
            ->where('jenis_transaksi', 'masuk')
            ->where('id_area', $id_area)
            ->where('project', $project)
            ->get()
            ->pluck('material')
            ->unique('id_material')
            ->values();

        return response()->json($materials);
    }

    public function store(Request $request)
    {
        foreach ($request->id_material as $index => $id_material) {

            if ($id_material == null) {
                continue;
            }

            // ===========================
            // AMBIL DATA MATERIAL
            // ===========================

            $material = Material::withSum(
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
            ->findOrFail($id_material);

            $jumlahKeluar = (int) $request->jumlah[$index];

            $stokTersedia =
                ($material->total_masuk ?? 0)
                -
                ($material->total_keluar ?? 0);

            if ($jumlahKeluar > $stokTersedia) {

                return back()
                ->withInput()
                ->with('error', 'Material "' . $material->nama_material . '" hanya memiliki stok ' . $stokTersedia . ' ' . $material->satuan . '.')
                ->with('reset_jumlah', true);
            }

            // ===========================
            // SIMPAN TRANSAKSI
            // ===========================

            $transaksi = TransaksiMaterial::create([

                'id_user'           => Auth::user()->id_user,
                'id_material'       => $id_material,
                'id_area'           => $request->id_area,
                'id_cluster'        => $request->id_cluster,
                'jenis_transaksi'   => 'keluar',

                'jumlah'            => $jumlahKeluar,
                'tgl_transaksi'     => $request->tanggal,
                'no_bukti'          => $request->no_bukti,

                'project'           => $request->project,

                'nama_penerima'     => $request->nama_penerima,
                'no_hp'             => $request->no_hp,
                'perusahaan'        => $request->perusahaan,
                'nama_sopir'        => $request->nama_sopir,
                'kendaraan'         => $request->kendaraan,
                'plat_nomor'        => $request->plat_nomor,

                'keterangan'        => $request->keterangan,

            ]);

            // ===========================
            // UPLOAD FOTO
            // ===========================

            if ($request->hasFile('foto_dokumentasi')) {

                foreach ($request->file('foto_dokumentasi') as $foto) {

                    $nama = time() . '_' . $foto->getClientOriginalName();

                    $foto->storeAs(
                        'foto-dokumentasi',
                        $nama,
                        'public'
                    );

                    DokumentasiTransaksi::create([

                        'id_transaksi'      => $transaksi->id_transaksi,
                        'file_dokumentasi'  => 'foto-dokumentasi/' . $nama,
                        'keterangan'        => $request->keterangan,
                        'tgl_upload'        => now(),

                    ]);

                }

            }

        }

        return redirect()
            ->route('admin.material.keluar')
            ->with(
                'success',
                'Material keluar berhasil disimpan.'
            );
    }

    public function edit($id)
    {
        $transaksi = TransaksiMaterial::with([
            'material',
            'cluster',
            'dokumentasiTransaksi'
        ])->findOrFail($id);

        $materials = Material::orderBy('kode_material')->get();
        $areas     = Area::orderBy('nama_area')->get();
        $clusters  = Cluster::orderBy('nama_cluster')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        return view(
            'admin.edit-material-keluar',
            compact(
                'transaksi',
                'areas',
                'materials',
                'clusters',
                'projects'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $materialBaru = Material::withSum(
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
        ->findOrFail($request->id_material);

        $stokTersedia =
            ($materialBaru->total_masuk ?? 0)
            -
            ($materialBaru->total_keluar ?? 0)
            +
            $transaksi->jumlah;

        if ($request->jumlah > $stokTersedia) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Stok material tidak mencukupi.'
                );
        }
            
        $transaksi->update([

            'id_material'=>$request->id_material,
            'id_cluster'=>$request->id_cluster,

            'tgl_transaksi'=>$request->tanggal,
            'no_bukti'=>$request->no_bukti,
            'jumlah'=>$request->jumlah,

            'project'=>$request->project,

            'nama_penerima'=>$request->nama_penerima,
            'no_hp'=>$request->no_hp,
            'perusahaan'=>$request->perusahaan,
            'nama_sopir'=>$request->nama_sopir,
            'kendaraan'=>$request->kendaraan,
            'plat_nomor'=>$request->plat_nomor,

            'keterangan'=>$request->keterangan,

        ]);

        if ($request->hasFile('foto_dokumentasi')) {

            foreach ($request->file('foto_dokumentasi') as $foto) {

                $nama = time() . '_' . $foto->getClientOriginalName();

                $foto->storeAs(
                    'foto-dokumentasi',
                    $nama,
                    'public'
                );

                DokumentasiTransaksi::create([

                    'id_transaksi'      => $transaksi->id_transaksi,
                    'file_dokumentasi'  => 'foto-dokumentasi/' . $nama,
                    'keterangan'        => $request->keterangan,
                    'tgl_upload'        => now(),

                ]);
            }
        }

        return redirect()
            ->route('admin.material.keluar')
            ->with(
                'success',
                'Data material keluar berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->delete();

        return redirect()
            ->route('admin.material.keluar')
            ->with(
                'success',
                'Data material keluar berhasil dihapus.'
            );
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
}