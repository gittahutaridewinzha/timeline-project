<?php

namespace App\Http\Controllers;

use App\Models\DetailFitur;
use App\Models\Pengerjaan;
use App\Models\Project;
use App\Models\ProjectJobTypes;
use App\Models\RevisiProject;
use App\Models\TaskDistribution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPengerjaanController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $pengerjaans = Pengerjaan::with(['taskDistribution.jobType', 'project'])
            ->get();

            $projects = Project::with(['taskDistributions.jobType', 'CategoryProject']) // ← tambahkan 'category'
            ->whereIn('id', function ($query) use ($userId) {
                $query->select('project_id')->from('task_distributions')->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();


        return view('back-end.pages.pengerjaan.index', compact('projects', 'pengerjaans'));
    }

    public function create($project_id = null)
    {
        if (!$project_id) {
            abort(404, 'Project ID tidak ditemukan');
        }

        $users = User::all();

        // Ambil project beserta taskDistributions user yang login
        $project = Project::with([
            'taskDistributions' => function ($query) {
                $query->where('user_id', Auth::id())->with('jobType');
            },
        ])->findOrFail($project_id);

        $detailFiturs = DetailFitur::whereHas('fitur', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })
            ->with('fitur')
            ->get();

        $progress = Pengerjaan::where('user_id', Auth::id())->whereIn('detail_fiturs_id', $detailFiturs->pluck('id'))->get()->keyBy('detail_fiturs_id');

        // Step 1: ambil job_types_id user
        $userJobTypeIds = $project->taskDistributions->pluck('job_types_id')->toArray();

        // Step 2 & 3: cari project_job_types sesuai project & job_types user
        $projectJobTypeIds = ProjectJobTypes::where('project_id', $project_id)->whereIn('job_id', $userJobTypeIds)->pluck('id')->toArray();

        // Step 4: ambil revisi yang sesuai
        $detailFiturIds = $detailFiturs->pluck('id')->toArray();

        $catatanRevisi = RevisiProject::whereIn('project_job_type_id', $projectJobTypeIds)->whereIn('detailfitur_id', $detailFiturIds)->get()->groupBy('detailfitur_id');

        return view('back-end.pages.pengerjaan.create', compact('detailFiturs', 'project', 'users', 'progress', 'catatanRevisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'detail_fiturs_id' => 'required|array',
            'pengerjaan' => 'required|array',
            'detail_fiturs_id.*' => 'exists:detail_fiturs,id',
            'pengerjaan.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $firstDetailFitur = DetailFitur::with('fitur')->find($request->detail_fiturs_id[0]);
        if (!$firstDetailFitur || !$firstDetailFitur->fitur) {
            return redirect()->back()->withErrors('Fitur tidak ditemukan.');
        }

        $projectId = $firstDetailFitur->fitur->project_id;
        $project = Project::find($projectId);
        if (!$project) {
            return redirect()->back()->withErrors('Project tidak ditemukan.');
        }

        if ($project->status === 'completed') {
            return redirect()
                ->route('pengerjaan.tambah', ['project_id' => $projectId])
                ->withErrors('Project sudah selesai 100% dan tidak dapat diubah lagi.');
        }

        $userId = Auth::id();
        // Simpan/update pengerjaan
        foreach ($request->detail_fiturs_id as $index => $detailFiturId) {
            $progress = $request->pengerjaan[$index] ?? 0;  // Kalau null, pakai 0

            // Bisa juga cek kalau kosong string atau null
            if ($progress === null || $progress === '') {
                $progress = 0;
            }

            $pengerjaan = Pengerjaan::where('user_id', $userId)
                ->where('detail_fiturs_id', $detailFiturId)
                ->first();

            if ($pengerjaan) {
                $pengerjaan->pengerjaan = $progress;
                $pengerjaan->save();
            } else {
                // Cari project_job_type_id dulu
                $detailFitur = DetailFitur::with('fitur')->find($detailFiturId);
                $pjProjectId = $detailFitur->fitur->project_id;

                $jobTypeId = TaskDistribution::where('project_id', $pjProjectId)
                    ->where('user_id', $userId)
                    ->value('job_types_id');

                $projectJobTypeId = ProjectJobTypes::where('project_id', $pjProjectId)
                    ->where('job_id', $jobTypeId)
                    ->value('id');

                Pengerjaan::create([
                    'user_id' => $userId,
                    'detail_fiturs_id' => $detailFiturId,
                    'pengerjaan' => $progress,
                    'project_job_type_id' => $projectJobTypeId,
                ]);
            }
        }



        // Ambil semua project_job_types untuk project ini
        $jobTypes = ProjectJobTypes::where('project_id', $projectId)->pluck('job_id');

        // Ambil semua detail fitur dari project ini
        $detailFiturIds = DetailFitur::whereHas('fitur', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->pluck('id');

        $isComplete = true;

        foreach ($jobTypes as $jobId) {
            foreach ($detailFiturIds as $detailFiturId) {
                $progress = Pengerjaan::where('detail_fiturs_id', $detailFiturId)
                    ->whereHas('projectJobType', function ($q) use ($jobId) {
                        $q->where('job_id', $jobId);
                    })
                    ->max('pengerjaan');

                if ($progress < 100) {
                    $isComplete = false;
                    break 2;
                }
            }
        }
        // Update status project
        if ($isComplete) {
            if ($project->status !== 'completed') {
                $project->status = 'completed';
                $project->save();
            }
        } else {
            if ($project->status === 'completed') {
                $project->status = 'on progress';
                $project->save();
            }
        }

        return redirect()
            ->route('pengerjaan.tambah', ['project_id' => $projectId])
            ->with('success', 'Pengerjaan berhasil diubah.');
    }

}
