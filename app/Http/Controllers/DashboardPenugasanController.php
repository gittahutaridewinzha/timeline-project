<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardPenugasanController extends Controller
{
    public function index($projectId)
{
    $project = Project::with('jobTypes', 'jobTypeAssignments')->findOrFail($projectId);
    $users = User::all();
    return view('back-end.pages.job_distributions.index', compact('project', 'users'));
}


    public function store(Request $request, Project $project)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*' => 'exists:users,id',
        ]);

        foreach ($request->assignments as $jobTypeId => $userId) {
            DB::table('task_distributions')->updateOrInsert(
                [
                    'project_id' => $project->id,
                    'job_types_id' => $jobTypeId,
                ],
                [
                    'user_id' => $userId,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Penugasan berhasil disimpan!');
    }
}
