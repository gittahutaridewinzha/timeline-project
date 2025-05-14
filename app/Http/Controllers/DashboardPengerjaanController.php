<?php

namespace App\Http\Controllers;

use App\Models\DetailFitur;
use App\Models\Pengerjaan;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardPengerjaanController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $pengerjaans = Pengerjaan::with(['taskDistribution.jobType', 'project']) // Pastikan relasi dimuat
                                ->get();

        $projects = Project::with(['taskDistributions.jobType'])
        ->whereIn('id', function ($query) use ($userId) {
            $query->select('project_id')
                ->from('task_distributions')
                ->where('user_id', $userId);
        })->get();


        return view('back-end.pages.pengerjaan.index', compact('projects','pengerjaans'));
    }

    public function create($project_id = null)
    {
        if (!$project_id) {
            abort(404, 'Project ID tidak ditemukan');
        }

        $users = User::all();
        $project = Project::with(['taskDistributions' => function ($query) {
            $query->where('user_id', Auth::id())->with('jobType');
        }])->findOrFail($project_id);


        $detailFiturs = DetailFitur::whereHas('fitur', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->with('fitur')->get();

        $progress = Pengerjaan::where('user_id', Auth::id())
                    ->whereIn('detail_fiturs_id', $detailFiturs->pluck('id'))
                    ->get()
                    ->keyBy('detail_fiturs_id');

        return view('back-end.pages.pengerjaan.create', compact('detailFiturs', 'project', 'users', 'progress'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'detail_fiturs_id' => 'required|array',
            'pengerjaan' => 'required|array',
            'detail_fiturs_id.*' => 'exists:detail_fiturs,id',
            'pengerjaan.*' => 'numeric|min:0|max:100',
        ]);

        foreach ($request->detail_fiturs_id as $index => $detailFiturId) {
            $existing = Pengerjaan::where('user_id', Auth::id())
                                  ->where('detail_fiturs_id', $detailFiturId)
                                  ->first();

            if ($existing) {
                Log::info("Updating pengerjaan ID {$existing->id} to " . $request->pengerjaan[$index]);
            } else {
                Log::info("Creating pengerjaan baru untuk detail_fitur_id {$detailFiturId}");
            }

            Pengerjaan::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'detail_fiturs_id' => $detailFiturId,
                ],
                [
                    'pengerjaan' => $request->pengerjaan[$index],
                ]
            );
        }

        return redirect()->route('pengerjaan.tambah', ['project_id' => $request->project_id])
        return redirect()->route('pengerjaan.create', ['project_id' => $request->project_id])
        ->with('success', 'Pengerjaan berhasil ditambahkan.');
    }

}
}
