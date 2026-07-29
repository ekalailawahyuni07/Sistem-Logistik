<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;

class AdminNotifikasiController extends Controller
{
    public function index()
    {
        $query = TransaksiMaterial::with('material')
            ->orderBy('created_at', 'desc');

        if (request()->filled('tanggal')) {
            $query->whereDate('created_at', request('tanggal'));
        }

        $notifikasi = $query->take(30)->get();

        return view('admin.notifikasi', compact('notifikasi'));
    }
}