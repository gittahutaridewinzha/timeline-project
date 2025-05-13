<?php

namespace App\Http\Controllers;

use App\Models\CategoryProject;
use App\Models\CategoryProjectsDetail;
use App\Models\JobType;
use App\Models\Project;
use App\Models\ProjectJobTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardProjectController extends Controller
{
    // Menampilkan daftar project (index)
    public function index()
    {
        $project = Project::where('id_project_manager', Auth::id())->paginate(10);
        return view('back-end.pages.project.index', compact('project'));
    }

    // Menampilkan form tambah project
    public function create()
    {
        // Ambil semua kategori proyek
    $categoryProject = CategoryProject::all();

    // Ambil semua pekerjaan terkait kategori proyek, jika ada (dapat disesuaikan sesuai logika)
    $jobTypes = CategoryProjectsDetail::with('jobType')->get();

    // Kirim data ke view
    return view('back-end.pages.project.create', compact('categoryProject', 'jobTypes'));
    }

    // Menyimpan project baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required',
            'category_id' => 'required|exists:category_projects,id',
            'job_type_ids' => 'array',
            'job_type_ids.*' => 'exists:job_types,id',
        ]);

        $project = Project::create([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
            'id_project_manager' => Auth::id(),
        ]);

        // Gunakan job_type_ids dari form
        foreach ($request->job_type_ids ?? [] as $jobTypeId) {
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

    public function getJobTypesByCategory($categoryId)
    {
        $category = CategoryProject::find($categoryId);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $jobTypes = $category->jobTypes;
        return response()->json(['jobTypes' => $jobTypes]);
    }

    public function getJobTypesByCategoryForEdit($categoryId, $projectId)
    {
        $category = CategoryProject::with('jobTypes')->find($categoryId);
        $project = Project::with('jobTypes')->find($projectId);

        if (!$category) {
            return response()->json(['jobTypes' => []]);
        }

        $jobTypes = $category->jobTypes->map(function ($jobType) use ($project) {
            return [
                'id' => $jobType->id,
                'name' => $jobType->name,
                'selected' => $project->jobTypes->contains('id', $jobType->id),
            ];
        });

        return response()->json(['jobTypes' => $jobTypes]);
    }


    public function show(string $id)
    {
        //
    }

    // Menampilkan form untuk edit project
    public function edit($id)
    {
        $project = Project::with('jobTypes')->find($id); // Menggunakan $id yang diterima dari parameter
        $categoryProject = CategoryProject::all(); // Ambil kategori project
        $jobTypes = CategoryProjectsDetail::with('jobType')->get();

        return view('back-end.pages.project.edit', compact('project', 'categoryProject', 'jobTypes'));
    }

    // Menyimpan pembaruan project
    public function update(Request $request, Project $project)
    {
        if ($project->id_project_manager !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'category_id' => 'required|exists:category_projects,id',
            'job_types' => 'nullable|array',
            'job_types.*' => 'exists:job_types,id',
        ]);

        // Update informasi proyek
        $project->update([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
        ]);

        // Menyinkronkan job types ke proyek di tabel project_job_types
        $project->jobTypes()->sync(
            collect($request->job_types ?? [])->mapWithKeys(function ($jobTypeId) use ($request) {
                return [$jobTypeId => ['category_id' => $request->category_id]];
            })
        );


        return redirect()->route('project.index')->with('success', 'Project berhasil diperbarui.');
    }

    // Menghapus project
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
