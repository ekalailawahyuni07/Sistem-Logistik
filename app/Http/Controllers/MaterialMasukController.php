<?php

namespace App\Http\Controllers;

use App\Mail\MaterialMasukMail;
use App\Models\DokumentasiTransaksi;
use App\Models\Material;
use App\Models\TransaksiMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Cluster;


class MaterialMasukController extends Controller
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

        // ADMIN = lihat semua area
        if ($user->id_role == 1) {

            $masuk = TransaksiMaterial::with('material')
                ->where('jenis_transaksi', 'masuk')
                ->orderBy('id_transaksi', 'asc')
                ->get();

            return view('admin.material-masuk', compact('masuk', 'projects'));
        }

        // PETUGAS = hanya area miliknya
        $masuk = TransaksiMaterial::with('material')
            ->where('jenis_transaksi', 'masuk')
            ->where('id_area', $user->id_area)
            ->orderBy('id_transaksi', 'asc')
            ->get();

        return view('user.material-masuk', compact('masuk', 'projects'));
    }

    public function create()
    {
        $user = Auth::user();

        $materials = Material::orderBy('nama_material')->get();

        if ($user->id_role != 1) {
            $projects = TransaksiMaterial::where('id_area', $user->id_area)
                ->whereNotNull('project')
                ->where('project', '!=', '')
                ->select('project')
                ->distinct()
                ->orderBy('project', 'asc')
                ->get();

            if ($projects->isEmpty()) {
                $projects = Material::select('project')
                    ->whereNotNull('project')
                    ->where('project', '!=', '')
                    ->distinct()
                    ->orderBy('project')
                    ->get();
            }
        } else {
            $projects = Material::select('project')
                ->whereNotNull('project')
                ->where('project', '!=', '')
                ->distinct()
                ->orderBy('project')
                ->get();
        }

        if (request()->is('admin/*')) {

            return view(
                'admin.tambah-material-masuk',
                compact('materials', 'projects')
            );

        }

        return view(
            'user.tambah-material-masuk',
            compact('materials', 'projects')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_material'   => ['required', 'array'],
            'id_material.*' => ['nullable', 'exists:material,id_material'],
            'jumlah'        => ['required', 'array'],
            'jumlah.*'      => ['nullable', 'integer', 'min:1'],
            'tanggal'       => ['required', 'date'],
            'no_bukti'      => ['required', 'string', 'max:255'],
            'project'       => ['required', 'string', 'max:255'],
        ]);

        foreach ($request->id_material as $index => $idMaterial) {
            if (empty($idMaterial)) {
                continue;
            }

            $jumlah = (int) ($request->jumlah[$index] ?? 0);

            if ($jumlah <= 0) {
                continue;
            }

            $transaksi = TransaksiMaterial::create([
                'id_user'         => Auth::user()->id_user,
                'id_area'         => Auth::user()->id_area,
                'id_material'     => $idMaterial,
                'id_cluster'      => $request->id_cluster,
                'jenis_transaksi' => 'masuk',
                'jumlah'          => $jumlah,
                'tgl_transaksi'   => $request->tanggal,
                'no_bukti'        => $request->no_bukti,
                'project'         => $request->project,
                'nama_penerima'   => $request->nama_penerima,
                'keterangan' => $request->keterangan[$index] ?? null,
            ]);

            $material = Material::findOrFail($idMaterial);

            $material->stok = (int) $material->stok + $jumlah;
            $material->save();

            $this->simpanDokumentasi($request, $transaksi, $index);

            $transaksi->load([
                'material.cluster.area',
                'cluster',
                'user',
            ]);

            $this->kirimEmailKeAdmin(
                new MaterialMasukMail($transaksi)
            );
        }

        $route = request()->is('admin/*')
            ? 'admin.material.masuk'
            : 'material.masuk';

        return redirect()
            ->route($route)
            ->with('success', 'Material masuk berhasil disimpan dan notifikasi email telah diproses.');
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
                'admin.edit-material-masuk',
                compact(
                    'transaksi',
                    'materials',
                    'clusters',
                    'projects'
                )
            );
        }

        return view(
            'user.edit-material-masuk',
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
        $transaksi = TransaksiMaterial::findOrFail($id);

        $transaksi->update([
            'id_material'   => $request->id_material,
            'tgl_transaksi' => $request->tanggal,
            'no_bukti'      => $request->no_bukti,
            'project'       => $request->project,
            'jumlah'        => $request->jumlah,
            'keterangan'    => $request->keterangan,
        ]);

        $this->simpanDokumentasi($request, $transaksi, 0);

        $route = request()->is('admin/*')
            ? 'admin.material.masuk'
            : 'material.masuk';

        return redirect()
            ->route($route)
            ->with('success', 'Data material masuk berhasil diperbarui.');
    }

    private function simpanDokumentasi(
        Request $request,
        TransaksiMaterial $transaksi,
        int $index
    ): void {

        if ($request->hasFile('foto_dokumentasi')) {

            foreach ($request->file('foto_dokumentasi') as $foto) {

                $namaFile = time().'_'.uniqid().'_'.$foto->getClientOriginalName();

                $foto->storeAs(
                    'foto-dokumentasi',
                    $namaFile,
                    'public'
                );

                DokumentasiTransaksi::create([
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'foto-dokumentasi/'.$namaFile,
                    'keterangan'       => $request->keterangan[$index] ?? null,
                    'tgl_upload'       => now(),
                ]);

            }

        }

        if ($request->hasFile('dokumen')) {

            foreach ($request->file('dokumen') as $dokumen) {

                $namaFile = time().'_'.uniqid().'_'.$dokumen->getClientOriginalName();

                $dokumen->storeAs(
                    'dokumen-transaksi',
                    $namaFile,
                    'public'
                );

                DokumentasiTransaksi::create([
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'file_dokumentasi' => 'dokumen-transaksi/'.$namaFile,
                    'keterangan'       => $request->keterangan[$index] ?? null,
                    'tgl_upload'       => now(),
                ]);

            }

        }

    }

    private function kirimEmailKeAdmin(object $mail): void
    {
        $emailAdmin = User::where('id_role', 1)
            ->where('status_validasi', 'disetujui')
            ->whereNotNull('email')
            ->pluck('email')
            ->unique();

        foreach ($emailAdmin as $email) {
            try {
                Mail::to($email)->send($mail);
            } catch (\Throwable $error) {
                Log::error('Email material masuk gagal dikirim.', [
                    'email' => $email,
                    'error' => $error->getMessage(),
                ]);
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

    public function destroy($id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        if ($transaksi->dokumentasiTransaksi) {
            foreach ($transaksi->dokumentasiTransaksi as $dokumen) {
                if ($dokumen->file_dokumentasi && Storage::disk('public')->exists($dokumen->file_dokumentasi)) {
                    Storage::disk('public')->delete($dokumen->file_dokumentasi);
                }
                $dokumen->delete();
            }
        }

        $transaksi->delete();

        $route = request()->is('admin/*') ? 'admin.material.masuk' : 'material.masuk';

        return redirect()
            ->route($route)
            ->with('success', 'Data material masuk berhasil dihapus.');
    }
}