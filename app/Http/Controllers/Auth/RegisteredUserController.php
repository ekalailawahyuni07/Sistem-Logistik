<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Role;
use App\Models\Area;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman register.
     */
    public function create(): View
    {
        $roles = Role::all();
        $areas = Area::all();

        return view('auth.register', compact('roles', 'areas'));
    }

    /**
     * Menyimpan data user baru.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_user' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:100',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
            'id_role' => [
                'required',
                'exists:roles,id_role',
            ],
            'id_area' => [
                'required',
                'exists:area,id_area',
            ],
        ]);

        $user = User::create([
            'nama_user'        => $request->nama_user,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'id_role'          => $request->id_role,
            'id_area'          => $request->id_area,
            'status_validasi'  => 'pending',
        ]);

        event(new Registered($user));

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Registrasi berhasil. Akun Anda sedang menunggu verifikasi Admin. Silakan login kembali setelah akun disetujui.'
            );
    }
}