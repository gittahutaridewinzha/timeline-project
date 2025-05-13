<?php

namespace App\Http\Controllers;

use App\Models\CategoryProject;
use App\Models\JobType;
use Illuminate\Http\Request;

class DashboardCategoryProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = CategoryProject::all();
        $jobTypes = JobType::all();
        return view("back-end.pages.kategori-project.index" , compact("kategori","jobTypes"));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'job_types' => 'required|array'
        ]);

        $category = CategoryProject::create([
            'name' => $request->name
        ]);

        // Simpan ke pivot
        $category->jobTypes()->attach($request->job_types);

        return redirect()->route('category-project.index')->with('success', 'Kategori berhasil ditambahkan.');
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'job_types' => 'array', // validasi job_types agar aman
            'job_types.*' => 'exists:job_types,id', // pastikan setiap id ada di tabel job_types
        ]);

        $kategori = CategoryProject::findOrFail($id);
        $kategori->name = $request->name;
        $kategori->save();

        // Sinkronisasi job_types ke tabel pivot category_projects_detail
        $kategori->jobTypes()->sync($request->job_types ?? []); // gunakan [] jika null

        return redirect()->route('category-project.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = CategoryProject::findOrFail($id);
        $kategori->delete();

        return redirect()->route("category-project.index")->with("success","data berhasil dihapus");
    }
}
