<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;

class DashboardProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ProjectType = ProjectType::paginate(10);
        return view('back-end.pages.project_types.index', compact('ProjectType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $ProjectType = ProjectType::all();
        // return view('back-end.pages.project_types.create', compact('ProjectType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

            ProjectType::create([
                'name' => $request->name,
            ]);

        return redirect()->route('project-type.index')->with('success', 'Tipe Project ditambahkan!');
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
        // $ProjectType = ProjectType::findOrFail($id);
        // return view('back-end.pages.project_types.edit', compact('ProjectType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ProjectType = ProjectType::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $ProjectType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('project-type.index')->with('success', 'Tipe Project berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ProjectType =ProjectType::findOrFail($id);
        $ProjectType->delete();

        return redirect()->route('project-type.index')->with('success', 'Tipe Projek berhasil dihapus!');
    }
}
