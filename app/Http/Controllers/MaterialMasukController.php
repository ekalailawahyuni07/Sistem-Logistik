<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\TransaksiMaterial;
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

        return view('user.material-masuk', compact('masuk'));
    }

    public function create()
    {
        $materials = Material::orderBy('kode_material', 'asc')->get();

        return view('user.tambah-material-masuk', compact('materials'));
    }

    public function store(Request $request)
    {
        foreach ($request->id_material as $index => $id_material) {

            if ($id_material == null) {
                continue;
            }

            TransaksiMaterial::create([
                'id_user'         => Auth::user()->id_user,
                'id_material'     => $id_material,
                'jenis_transaksi' => 'masuk',
                'jumlah'          => $request->jumlah[$index],
                'tgl_transaksi'   => $request->tanggal,
                'no_bukti'        => $request->no_bukti,
                'keterangan'      => $request->keterangan[$index] ?? null,
            ]);
        }

        return redirect()
            ->route('material.masuk')
            ->with('success', 'Data material masuk berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        $materials = Material::orderBy('kode_material', 'asc')->get();

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

        return redirect()
            ->route('material.masuk')
            ->with('success', 'Data material masuk berhasil diperbarui.');
    }
}