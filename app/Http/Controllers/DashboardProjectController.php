<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::where('id_project_manager', Auth::id())->paginate(10);
        return view('back-end.project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back-end.project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Project::create([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'id_project_manager' => Auth::id(),
        ]);

        return redirect()->route('project.index')->with('success', 'Project berhasil ditambahkan.');
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
    public function edit(Project $project)
    {
        if ($project->id_project_manager !== Auth::id()) {
            abort(403);
        }

        return view('back-end.project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->id_project_manager !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $project->update([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('project.index')->with('success', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->id_project_manager !== Auth::id()) {
            abort(403);
        }

        $project->delete();
        return redirect()->route('project.index')->with('success', 'Project berhasil dihapus.');
    }
}
