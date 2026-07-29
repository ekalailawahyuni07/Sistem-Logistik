<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;

class AdminVerifikasiUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'area'])
            ->where('id_user', '!=', auth()->id());

        // Filter Area
        if ($request->filled('area')) {
            $query->where('id_area', $request->area);
        }

        // Pencarian Nama / Email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_user', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query
            ->orderByRaw("
                CASE
                    WHEN status_validasi='pending' THEN 1
                    WHEN status_validasi='disetujui' THEN 2
                    WHEN status_validasi='ditolak' THEN 3
                END
            ")
            ->orderBy('created_at','desc')
            ->get();

        $areas = Area::orderBy('nama_area')->get();

        return view('admin.verifikasi-user', compact('users','areas'));
    }

    public function setujui($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status_validasi' => 'disetujui',
        ]);

        return redirect()
            ->route('admin.verifikasi.user')
            ->with('success', 'Akun user berhasil disetujui.');
    }

    public function tolak($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('admin.verifikasi.user')
            ->with('success', 'Pengajuan akun ditolak dan langsung dihapus.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('admin.verifikasi.user')
            ->with('success', 'Akun karyawan berhasil dihapus.');
    }
}