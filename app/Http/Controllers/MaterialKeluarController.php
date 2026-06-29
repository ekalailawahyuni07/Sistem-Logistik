<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\TransaksiMaterial;
use Illuminate\Http\Request;

class MaterialKeluarController extends Controller
{
    public function index()
    {
        $keluar = TransaksiMaterial::with('material')
            ->where('jenis_transaksi', 'keluar')
            ->orderBy('id_transaksi', 'desc')
            ->get();

        return view('user.material-keluar', compact('keluar'));
    }

    public function create()
    {
        $materials = Material::orderBy('kode_material', 'asc')->get();

        return view('user.tambah-material-keluar', compact('materials'));
    }

    public function store(Request $request)
    {
        // Nanti kita isi di langkah berikutnya
    }
}