<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;

class AdminNotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = TransaksiMaterial::with(['material'])
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        return view('admin.notifikasi', compact('notifikasi'));
    }
}