<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\Material;
use App\Models\TransaksiMaterial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | RINGKASAN LOGISTIK
        |--------------------------------------------------------------------------
        */

        $totalMasuk = TransaksiMaterial::where('jenis_transaksi', 'masuk')
            ->sum('jumlah');

        $totalKeluar = TransaksiMaterial::where('jenis_transaksi', 'keluar')
            ->sum('jumlah');

        $totalStock = $totalMasuk - $totalKeluar;


        /*
        |--------------------------------------------------------------------------
        | USER MENUNGGU VERIFIKASI
        |--------------------------------------------------------------------------
        | id_role 2 adalah petugas.
        */

        $userPending = User::where('status_validasi', 'pending')
            ->where('id_role', 2)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | STOK MATERIAL HAMPIR HABIS
        |--------------------------------------------------------------------------
        | Stok 1 sampai 10 dianggap hampir habis.
        */

        $stokMenipis = Material::where('stok', '>', 0)
            ->where('stok', '<=', 10)
            ->count();

        $materialMenipis = Material::where('stok', '>', 0)
            ->where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | GRAFIK MATERIAL BULAN INI
        |--------------------------------------------------------------------------
        | Minggu 1 = tanggal 1–7
        | Minggu 2 = tanggal 8–14
        | Minggu 3 = tanggal 15–21
        | Minggu 4 = tanggal 22–akhir bulan
        */

        $transaksiTerbaru = TransaksiMaterial::max('tgl_transaksi');

        $tanggalAcuan = $transaksiTerbaru
            ? Carbon::parse($transaksiTerbaru)
            : Carbon::now();

        $tahun = $tanggalAcuan->year;
        $bulan = $tanggalAcuan->month;
        $akhirBulan = $tanggalAcuan->copy()->endOfMonth()->day;

        $rentangMinggu = [
            [1, 7],
            [8, 14],
            [15, 21],
            [22, $akhirBulan],
        ];

        $grafikMasuk = [];
        $grafikKeluar = [];

        foreach ($rentangMinggu as [$tanggalAwal, $tanggalAkhir]) {
            $awal = Carbon::create($tahun, $bulan, $tanggalAwal)
                ->startOfDay();

            $akhir = Carbon::create($tahun, $bulan, $tanggalAkhir)
                ->endOfDay();

            $grafikMasuk[] = TransaksiMaterial::where(
                'jenis_transaksi',
                'masuk'
            )
                ->whereBetween('tgl_transaksi', [$awal, $akhir])
                ->sum('jumlah');

            $grafikKeluar[] = TransaksiMaterial::where(
                'jenis_transaksi',
                'keluar'
            )
                ->whereBetween('tgl_transaksi', [$awal, $akhir])
                ->sum('jumlah');
        }


        /*
        |--------------------------------------------------------------------------
        | MONITORING MATERIAL BERDASARKAN AREA
        |--------------------------------------------------------------------------
        */

        $monitoringArea = Area::orderBy('nama_area', 'asc')
        ->get()
        ->map(function ($area) {

            $totalMasukArea = TransaksiMaterial::where('jenis_transaksi', 'masuk')
                ->where('id_area', $area->id_area)
                ->sum('jumlah');

            $totalKeluarArea = TransaksiMaterial::where('jenis_transaksi', 'keluar')
                ->where('id_area', $area->id_area)
                ->sum('jumlah');

            return [

                'id_area'      => $area->id_area,
                'nama_area'    => $area->nama_area,

                'total_masuk'  => $totalMasukArea,
                'total_keluar' => $totalKeluarArea,
                'total_stok'   => $totalMasukArea - $totalKeluarArea,

            ];

        });

        // untuk kontrol material
        $totalMaterial = Material::count();

        $stokHabis = Material::where('stok', 0)->count();

        $materialMenipis = Material::where('stok', '>', 0)
            ->where('stok', '<=', 10)
            ->count();
        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalMasuk',
            'totalKeluar',
            'totalStock',
            'userPending',
            'stokMenipis',
            'materialMenipis',
            'monitoringArea',
            'grafikMasuk',
            'grafikKeluar',
            'totalMaterial',
            'stokHabis'
        ));
    }
}