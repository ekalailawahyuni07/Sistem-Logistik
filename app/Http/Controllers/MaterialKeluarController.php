<?php

namespace App\Http\Controllers;

use App\Mail\MaterialKeluarMail;
use App\Mail\StokMenipisMail;
use App\Models\DokumentasiTransaksi;
use App\Models\Material;
use App\Models\TransaksiMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Models\Cluster;

class MaterialKeluarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project', 'asc')
            ->get();

        if ($user->id_role == 1 || request()->is('admin/*')) {
            $keluar = TransaksiMaterial::with([
                'material',
                'cluster',
                'user',
            ])
                ->where('jenis_transaksi', 'keluar')
                ->orderBy('id_transaksi', 'asc')
                ->get();

            return view('admin.material-keluar', compact('keluar', 'projects'));
        }

        $keluar = TransaksiMaterial::with([
            'material',
            'cluster',
            'user',
        ])
            ->where('jenis_transaksi', 'keluar')
            ->where(function ($query) use ($user) {
                $query->where('id_area', $user->id_area)
                    ->orWhereHas('cluster', function ($q) use ($user) {
                        $q->where('id_area', $user->id_area);
                    });
            })
            ->orderBy('id_transaksi', 'asc')
            ->get();

        return view('user.material-keluar', compact('keluar', 'projects'));
    }

    public function create()
    {
        $materials = Material::withSum(
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
        ->orderBy('kode_material', 'asc')
        ->get();

        foreach ($materials as $material) {
            $material->stok =
                ($material->total_masuk ?? 0) -
                ($material->total_keluar ?? 0);
        }

        $clusters = Cluster::orderBy('kode_cluster', 'asc')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        if (request()->is('admin/*')) {
            return view(
                'admin.tambah-material-keluar',
                compact('materials', 'clusters', 'projects')
            );
        }

        return view(
            'user.tambah-material-keluar',
            compact('materials', 'clusters', 'projects')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_material' => [
                'required',
                'array',
            ],

            'id_material.*' => [
                'nullable',
                'exists:material,id_material',
            ],

            'jumlah' => [
                'required',
                'array',
            ],

            'jumlah.*' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'no_bukti' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_penerima' => [
                'required',
                'string',
                'max:100',
            ],

            'no_hp' => [
                'required',
                'digits_between:11,13',
            ],

            'perusahaan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nama_p' => [
                'nullable',
                'string',
                'max:100',
            ],

            'kendaraan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'plat_nomor' => [
                'nullable',
                'string',
                'max:30',
            ],

        ], [
            'id_material.required' => 'Material wajib dipilih.',
            'jumlah.required' => 'Jumlah material wajib diisi.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'no_bukti.required' => 'Nomor bukti atau surat jalan wajib diisi.',
        ]);

        foreach ($request->id_material as $index => $idMaterial) {

            if (empty($idMaterial)) {
                continue;
            }

            $jumlahKeluar = (int) ($request->jumlah[$index] ?? 0);

            if ($jumlahKeluar <= 0) {
                continue;
            }

            $material = Material::findOrFail($idMaterial);

            $stokSebelum = (int) $material->stok;
            $stokSesudah = $stokSebelum - $jumlahKeluar;

            if ($stokSesudah < 0) {
                throw ValidationException::withMessages([
                    'jumlah' =>
                        'Stok material "' .
                        $material->nama_material .
                        '" tidak mencukupi. Stok tersedia hanya ' .
                        $stokSebelum . ' ' .
                        ($material->satuan ?? '') . '.',
                ]);
            }

            $transaksi = TransaksiMaterial::create([
                'id_user'         => Auth::id(),
                'id_area'         => Auth::user()->id_area,
                'id_material'     => $idMaterial,
                'id_cluster'      => $request->id_cluster ?? $material->id_cluster,
                'jenis_transaksi' => 'keluar',
                'jumlah'          => $jumlahKeluar,
                'tgl_transaksi'   => $request->tanggal,
                'no_bukti'        => $request->no_bukti,
                'project'         => $request->project,

                'nama_penerima'   => $request->nama_penerima,
                'nama_sopir'      => $request->nama_sopir,
                'no_hp'           => $request->no_hp,
                'perusahaan'      => $request->perusahaan,
                'kendaraan'       => $request->kendaraan,
                'plat_nomor'      => $request->plat_nomor,

                'keterangan'      => $request->keterangan,
            ]);

            $material->update([
                'stok' => $stokSesudah,
            ]);

            $this->simpanDokumentasi($request, $transaksi);

            $transaksi->load([
                'material.cluster.area',
                'cluster',
                'user',
            ]);

            $this->kirimEmailKeAdmin(
                new MaterialKeluarMail($transaksi)
            );

            if ($stokSebelum > 10 && $stokSesudah <= 10) {
                $material->load('cluster.area');

                $this->kirimEmailKeAdmin(
                    new StokMenipisMail($material)
                );
            }
        }

        $route = request()->is('admin/*')
            ? 'admin.material.keluar'
            : 'material.keluar';

        return redirect()
            ->route($route)
            ->with(
                'success',
                'Material keluar berhasil disimpan dan email notifikasi telah diproses.'
            );
    }

    public function edit($id)
    {
        $transaksi = TransaksiMaterial::with([
            'material',
            'cluster',
            'dokumentasi'
        ])->findOrFail($id);

        $materials = Material::orderBy('kode_material')->get();

        $clusters = Cluster::orderBy('nama_cluster')->get();

        $projects = Material::select('project')
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->distinct()
            ->orderBy('project')
            ->get();

        if (request()->is('admin/*')) {

            return view(
                'admin.edit-material-keluar',
                compact(
                    'transaksi',
                    'materials',
                    'clusters',
                    'projects'
                )
            );
        }

        return view(
            'user.edit-material-keluar',
            compact(
                'transaksi',
                'materials',
                'clusters',
                'projects'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_material' => [
                'required',
                'exists:material,id_material',
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'no_bukti' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->update([
            'id_material'   => $request->id_material,
            'id_cluster'    => $request->id_cluster,
            'tgl_transaksi' => $request->tanggal,
            'no_bukti'      => $request->no_bukti,
            'jumlah'        => $request->jumlah,
            'project'       => $request->project,
            'nama_penerima' => $request->nama_penerima,
            'nama_sopir'    => $request->nama_sopir,
            'keterangan'    => $request->keterangan,
        ]);

        $this->simpanDokumentasi(
            $request,
            $transaksi
        );

        $route = request()->is('admin/*')
            ? 'admin.material.keluar'
            : 'material.keluar';

        return redirect()
            ->route($route)
            ->with(
                'success',
                'Data material keluar berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DOKUMENTASI
    |--------------------------------------------------------------------------
    */

    private function simpanDokumentasi(
        Request $request,
        TransaksiMaterial $transaksi
    ): void {
        if ($request->hasFile('foto_dokumentasi')) {
            foreach (
                $request->file('foto_dokumentasi') as $foto
            ) {
                $namaFile =
                    time() . '_' .
                    uniqid() . '_' .
                    $foto->getClientOriginalName();

                $foto->storeAs(
                    'foto-dokumentasi',
                    $namaFile,
                    'public'
                );

                DokumentasiTransaksi::create([
                    'id_transaksi' =>
                        $transaksi->id_transaksi,

                    'file_dokumentasi' =>
                        'foto-dokumentasi/' . $namaFile,

                    'keterangan' =>
                        $request->keterangan,

                    'tgl_upload' =>
                        now(),
                ]);
            }
        }

        if ($request->hasFile('dokumen')) {
            foreach (
                $request->file('dokumen') as $dokumen
            ) {
                $namaFile =
                    time() . '_' .
                    uniqid() . '_' .
                    $dokumen->getClientOriginalName();

                $dokumen->storeAs(
                    'dokumen-transaksi',
                    $namaFile,
                    'public'
                );

                DokumentasiTransaksi::create([
                    'id_transaksi' =>
                        $transaksi->id_transaksi,

                    'file_dokumentasi' =>
                        'dokumen-transaksi/' . $namaFile,

                    'keterangan' =>
                        $request->keterangan,

                    'tgl_upload' =>
                        now(),
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | KIRIM EMAIL KE SEMUA ADMIN AKTIF
    |--------------------------------------------------------------------------
    */

    private function kirimEmailKeAdmin(
        object $emailNotifikasi
    ): void {
        $emailAdmin = User::where('id_role', 1)
            ->where('status_validasi', 'disetujui')
            ->whereNotNull('email')
            ->pluck('email')
            ->unique();

        foreach ($emailAdmin as $email) {
            try {
                Mail::to($email)
                    ->send(clone $emailNotifikasi);
            } catch (\Throwable $error) {
                Log::error(
                    'Email notifikasi gagal dikirim.',
                    [
                        'email' => $email,
                        'error' => $error->getMessage(),
                    ]
                );
            }
        }
    }

    public function destroyDokumen($id)
    {
        $dokumen = DokumentasiTransaksi::findOrFail($id);
        if ($dokumen->file_dokumentasi && Storage::disk('public')->exists($dokumen->file_dokumentasi)) {
            Storage::disk('public')->delete($dokumen->file_dokumentasi);
        }
        $dokumen->delete();
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
    }
}