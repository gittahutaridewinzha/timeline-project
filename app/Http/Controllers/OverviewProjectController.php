<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class OverviewProjectController extends Controller
{
    public function index()
    {
        $project = Project::with(['fiturs.detailFiturs.pengerjaans.user'])
            ->get();

        $project->map(function ($proj) {
            $totalProgress = 0;
            $totalDetailFitur = 0;

            foreach ($proj->fiturs as $fitur) {
                foreach ($fitur->detailFiturs as $detail) {
                    $totalDetailFitur++;

                    $pengerjaans = $detail->pengerjaans;
                    $jumlahPengerjaan = $pengerjaans->sum('pengerjaan');
                    $jumlahOrang = $pengerjaans->count();
                    $rataRataProgress = $jumlahOrang > 0 ? $jumlahPengerjaan / $jumlahOrang : 0;
                    $totalProgress += $rataRataProgress;
                }
            }

            $proj->jumlah_pengerjaan = round($totalProgress, 2);
            $proj->persentase_pengerjaan = $totalDetailFitur > 0 ? round($totalProgress / $totalDetailFitur, 2) : 0;

            return $proj;
        });
        return view('back-end.pages.overview_project.index', compact('project'));
    }

    public function show($id)
    {
        $project = Project::with(['fiturs.detailFiturs.pengerjaans.user'])->findOrFail($id);

        $totalProgress = 0;
        $totalDetailFitur = 0;

        $fiturWithProgress = $project->fiturs->map(function ($fitur) use (&$totalProgress, &$totalDetailFitur) {
            $fiturProgress = 0;
            $fiturDetailCount = 0;

            foreach ($fitur->detailFiturs as $detail) {
                $pengerjaans = $detail->pengerjaans;
                $jumlahPengerjaan = $pengerjaans->sum('pengerjaan');
                $jumlahOrang = $pengerjaans->count();

                $rataRataProgress = $jumlahOrang > 0 ? $jumlahPengerjaan / $jumlahOrang : 0;

                $fiturProgress += $rataRataProgress;
                $fiturDetailCount++;

                $detail->rata_rata_progress = round($rataRataProgress, 2); // disimpan untuk view
            }

            $fitur->totalProgress = $fiturDetailCount > 0 ? round($fiturProgress / $fiturDetailCount, 2) : 0;

            $totalProgress += $fiturProgress;
            $totalDetailFitur += $fiturDetailCount;

            return $fitur;
        });

        $totalAll = $totalDetailFitur > 0 ? round($totalProgress / $totalDetailFitur, 2) : 0;

        return view('back-end.pages.overview_project.show', compact('project', 'fiturWithProgress', 'totalAll'));
    }
}
