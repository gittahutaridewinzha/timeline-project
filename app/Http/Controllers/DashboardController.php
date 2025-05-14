<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('month') ?? Carbon::now()->month;

        // Ambil proyek berdasarkan bulan, urutkan berdasarkan deadline terdekat
        $projects = Project::whereMonth('deadline', $month)
            ->orderBy('deadline', 'asc')
            ->get();

        $totalProjects = $projects->count();

        // Format tanggal deadline (opsional, kalau kamu pakai formatted_deadline di view)
        foreach ($projects as $project) {
            $project->formatted_deadline = Carbon::parse($project->deadline)->format('d M Y');
        }

        return view('back-end.pages.dashboard', compact('projects', 'totalProjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
