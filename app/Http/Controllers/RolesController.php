<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::all();
        return view('back-end.pages.otoritas.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::all(); // Ambil semua menu untuk checkbox akses
        return view('back-end.pages.otoritas.role.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'menus' => 'array',
        ]);

        $role = Roles::create([
            'name' => $request->name,
        ]);

        $role->menus()->sync($request->menus ?? []);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
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
        $menus = Menu::all();
        $role = Roles::FindorFail($id);
        $roleMenuIds = $role->menus->pluck('id')->toArray();
        return view('back-end.pages.otoritas.role.edit', compact('role', 'menus', 'roleMenuIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Roles $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'menus' => 'array',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        // Perbarui akses menu
        $role->menus()->sync($request->menus ?? []);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
