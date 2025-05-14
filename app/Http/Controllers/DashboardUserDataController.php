<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardUserDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return view('back-end.pages.otoritas.data-admin.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Roles::all();
        return view('back-end.pages.otoritas.data-admin.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Abaikan email sendiri
            'password' => 'nullable|min:8|confirmed', // Hanya validasi jika diisi
            'role_id' => 'required|exists:roles,id',
        ]);

        // Update data admin
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role_id'];

        // Update password hanya jika diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('data-user.index')->with('success', 'Data admin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('data-user.index')->with('success','data berhasil dihapus');
    }
}
