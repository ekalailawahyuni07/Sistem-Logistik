<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\TransaksiMaterial;
use App\Models\DokumentasiTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialMasukController extends Controller
{
    public function index()
    {
        $masuk = TransaksiMaterial::with('material')
            ->where('jenis_transaksi', 'masuk')
            ->orderBy('id_transaksi', 'asc')
            ->get();

        if (request()->is('admin/*')) {
            return view('admin.material-masuk', compact('masuk'));
        }

        return view('user.material-masuk', compact('masuk'));
    }

    public function create()
    {
        $materials = Material::orderBy('kode_material', 'asc')->get();

        if (request()->is('admin/*')) {
            return view('admin.tambah-material-masuk', compact('materials'));
        }

        return view('user.tambah-material-masuk', compact('materials'));
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
                'jenis_transaksi' => 'masuk',
                'jumlah'          => $request->jumlah[$index],
                'tgl_transaksi'   => $request->tanggal,
                'no_bukti'        => $request->no_bukti,
                'project'         => $request->project,
                'nama_penerima'   => $request->nama_penerima,
                'keterangan'      => $request->keterangan,
            ]);

            if ($request->hasFile('foto_dokumentasi')) {
                foreach ($request->file('foto_dokumentasi') as $foto) {
                    $namaAsli = $foto->getClientOriginalName();
                    $foto->storeAs('foto-dokumentasi', $namaAsli, 'public');

                    DokumentasiTransaksi::create([
                        'id_transaksi'     => $transaksi->id_transaksi,
                        'file_dokumentasi' => 'foto-dokumentasi/' . $namaAsli,
                        'keterangan'       => $request->keterangan,
                        'tgl_upload'       => now(),
                    ]);
                }
            }

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $dokumen) {
                    $namaAsli = $dokumen->getClientOriginalName();
                    $dokumen->storeAs('dokumen-transaksi', $namaAsli, 'public');

                    DokumentasiTransaksi::create([
                        'id_transaksi'     => $transaksi->id_transaksi,
                        'file_dokumentasi' => 'dokumen-transaksi/' . $namaAsli,
                        'keterangan'       => $request->keterangan,
                        'tgl_upload'       => now(),
                    ]);
                }
            }
        }

        if (request()->is('admin/*')) {
            return redirect()
                ->route('admin.material.masuk')
                ->with('success', 'Material masuk berhasil disimpan.');
        }

        return redirect()
            ->route('material.masuk')
            ->with('success', 'Material masuk berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = TransaksiMaterial::with('dokumentasiTransaksi')->findOrFail($id);
        $materials = Material::orderBy('kode_material', 'asc')->get();

        if (request()->is('admin/*')) {
            return view('admin.edit-material-masuk', compact('transaksi', 'materials'));
        }

        return view('user.edit-material-masuk', compact('transaksi', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->update([
            'id_material'    => $request->id_material,
            'tgl_transaksi'  => $request->tanggal,
            'no_bukti'       => $request->no_bukti,
            'jumlah'         => $request->jumlah,
            'keterangan'     => $request->keterangan,
        ]);

        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $namaAsli = $foto->getClientOriginalName();
                $foto->storeAs('foto-dokumentasi', $namaAsli, 'public');

                DokumentasiTransaksi::create([
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'foto-dokumentasi/' . $namaAsli,
                    'keterangan'       => $request->keterangan,
                    'tgl_upload'       => now(),
                ]);
            }
        }

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $dokumen) {
                $namaAsli = $dokumen->getClientOriginalName();
                $dokumen->storeAs('dokumen-transaksi', $namaAsli, 'public');

                DokumentasiTransaksi::create([
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'dokumen-transaksi/' . $namaAsli,
                    'keterangan'       => $request->keterangan,
                    'tgl_upload'       => now(),
                ]);
            }
        }

        if (request()->is('admin/*')) {
            return redirect()
                ->route('admin.material.masuk')
                ->with('success', 'Data material masuk berhasil diperbarui.');
        }

        return redirect()
            ->route('material.masuk')
            ->with('success', 'Data material masuk berhasil diperbarui.');
    }
}