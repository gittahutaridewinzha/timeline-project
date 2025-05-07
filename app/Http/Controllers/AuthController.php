<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('back-end.pages.otoritas.login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt:', ['email' => $request->email]);

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = \App\Models\User::where('email', $request->email)->first();
        if (!$admin) {
            Log::warning('Login failed: Admin not found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Email tidak ditemukan!']);
        }

        Log::info('Stored password hash:', ['hash' => $admin->password]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            Log::info('Login success', ['email' => $request->email, 'user_id' => $admin->id]);

            return redirect()->route('dashboard');
        } else {
            Log::warning('Login failed: Invalid password', ['email' => $request->email]);
        }

        return back()->withErrors(['email' => 'Email atau password salah!']);
    }

    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('back-end.pages.profile.profile', compact('admin'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
