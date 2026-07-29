<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Cari user berdasarkan nama_user
        $user = \App\Models\User::where('nama_user', $request->nama_user)->first();

        // Cek apakah user ada
        if (!$user) {
            return back()->withErrors([
                'nama_user' => 'Nama user tidak ditemukan.'
            ])->onlyInput('nama_user');
        }

        // Cek status validasi
        if ($user->status_validasi == 'pending') {
            return back()->withErrors([
                'nama_user' => 'Akun Anda belum disetujui oleh Admin.'
            ])->onlyInput('nama_user');
        }

        // Login
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}