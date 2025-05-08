<?php

namespace App\Http\Controllers;

use App\Models\CategoryProject;
use App\Models\JobType;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::where('id_project_manager', Auth::id())->paginate(10);
        return view('back-end.pages.project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryProject = CategoryProject::all();
        $jobTypes = JobType::all();
        return view('back-end.pages.project.create', compact('categoryProject', 'jobTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'category_id' => 'required|exists:category_projects,id',
            'job_types' => 'required|array',
            'job_types.*' => 'exists:job_types,id',
        ]);

        $project = Project::create([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
            'id_project_manager' => Auth::id(),
        ]);

        foreach ($request->job_types as $jobTypeId) {
            DB::table('project_job_types')->insert([
                'project_id' => $project->id,
                'category_id' => $request->category_id,
                'job_id' => $jobTypeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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

        $project->load('jobTypes');

        $categoryProject = CategoryProject::all();
        $jobTypes = JobType::all();
        return view('back-end.pages.project.edit', compact('project', 'categoryProject', 'jobTypes'));
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
            'category_id' => 'required|exists:category_projects,id',
            'job_types' => 'required|array|min:1',
        ]);

        $project->update([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
        ]);

        $project->jobTypes()->sync(
            collect($request->job_types)->mapWithKeys(function ($jobTypeId) use ($request) {
                return [$jobTypeId => ['category_id' => $request->category_id]];
            })
        );

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

        $project->jobTypes()->detach();

        $project->delete();
        return redirect()->route('project.index')->with('success', 'Project berhasil dihapus.');
    }
}
