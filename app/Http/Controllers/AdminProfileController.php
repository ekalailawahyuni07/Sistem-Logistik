<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        
    }

    public function updatePassword(Request $request)
    {
        
    }
}