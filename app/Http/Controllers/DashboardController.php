<?php

namespace App\Http\Controllers;

use App\Models\CategoryProject;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\User;
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

        $projects = Project::whereMonth('deadline', $month)
            ->orderBy('deadline', 'asc')
            ->get();

        $totalProjects = $projects->count();
        $totalCompletedProjects = Project::where('status', 'Completed')->count();
        $totalEmployees = User::count();

        $projectsPerMonth = Project::selectRaw('MONTH(deadline) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $allMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $allMonths[] = $projectsPerMonth[$i] ?? 0;
        }

        // ✅ Filter project type berdasarkan bulan yang dipilih
        $projectTypes = ProjectType::withCount(['projects' => function ($query) use ($month) {
            $query->whereMonth('deadline', $month);
        }])->get();
        $chartLabels = $projectTypes->pluck('name');
        $chartData = $projectTypes->pluck('projects_count');
        $nextMonth = Carbon::now()->addMonth()->month;
        $upcomingProjectsCount = Project::whereMonth('deadline', $nextMonth)->count();

        return view('back-end.pages.dashboard', compact(
            'projects',
            'totalProjects',
            'totalCompletedProjects',
            'totalEmployees',
            'allMonths',
            'projectTypes',
            'chartData',
            'chartLabels',
            'nextMonth',
            'upcomingProjectsCount',
        ));
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
