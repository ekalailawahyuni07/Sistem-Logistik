<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profile.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update data profile.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | DATA PROFILE
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();

        // Tambahkan field profile tambahan
        $data['nama_user'] = $request->nama_user;
        $data['email'] = $request->email;
        $data['no_telp'] = $request->no_telp;
        $data['alamat'] = $request->alamat;


        /*
        |--------------------------------------------------------------------------
        | FOTO PROFILE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto_profile')) {

            // Hapus foto lama jika ada
            if (
                $user->foto_profile &&
                Storage::disk('public')->exists($user->foto_profile)
            ) {
                Storage::disk('public')->delete($user->foto_profile);
            }

            // Simpan foto baru
            $data['foto_profile'] = $request
                ->file('foto_profile')
                ->store('foto_profile', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $user->fill($data);

        // Jika email berubah, reset verifikasi email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE PROFILE
        |--------------------------------------------------------------------------
        */

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }


    /**
     * Hapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO PROFILE
        |--------------------------------------------------------------------------
        */

        if (
            $user->foto_profile &&
            Storage::disk('public')->exists($user->foto_profile)
        ) {
            Storage::disk('public')->delete($user->foto_profile);
        }


        /*
        |--------------------------------------------------------------------------
        | LOGOUT & HAPUS USER
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}