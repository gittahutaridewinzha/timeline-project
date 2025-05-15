<?php

namespace App\Http\Controllers;

use App\Models\DetailFitur;
use App\Models\Fitur;
use App\Models\Project;
use App\Models\ProjectJobTypes;
use App\Models\RevisiProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardFiturController extends Controller
{
    public function index($project)
    {
        $project = Project::with('fiturs.detailFiturs')->findOrFail($project);
        $jobTypes = ProjectJobTypes::with('jobtype')->where('project_id', $project->id)->get();

        return view('back-end.pages.fitur.create', compact('project', 'jobTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:project,id',
            'name' => 'required|string',
            'detail_fiturs' => 'required|array|min:1',
            'detail_fiturs.*' => 'required|string',
        ]);

        // Simpan fitur
        $fitur = Fitur::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
        ]);

        // Simpan detail fiturs
        foreach ($request->detail_fiturs as $detailName) {
            DetailFitur::create([
                'fitur_id' => $fitur->id,
                'name' => $detailName,
            ]);
        }

        return redirect()->back()->with('success', 'Fitur dan detail berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $fitur = Fitur::findOrFail($id);
        Log::info('Memperbarui fitur:', ['id' => $fitur->id, 'nama_baru' => $request->name]);

        // Update nama fitur
        $fitur->update(['name' => $request->name]);

        // Update detail fitur yang ada
        if ($request->has('details')) {
            foreach ($request->details as $detailId => $name) {
                $updated = DetailFitur::where('id', $detailId)
                    ->where('fitur_id', $fitur->id)
                    ->update(['name' => $name]);

                Log::info('Mengupdate detail fitur', [
                    'id' => $detailId,
                    'fitur_id' => $fitur->id,
                    'nama_baru' => $name,
                    'status' => $updated ? 'berhasil' : 'gagal',
                ]);
            }
        }

        // Hapus detail yang dipilih
        if ($request->has('deleted_details')) {
            $deleted = DetailFitur::whereIn('id', $request->deleted_details)->where('fitur_id', $fitur->id)->delete();

            Log::info('Jumlah detail yang dihapus:', ['deleted_count' => $deleted]);
        }

        // Tambah detail baru
        if ($request->has('new_details')) {
            foreach ($request->new_details as $newDetailName) {
                if (!empty($newDetailName)) {
                    $baru = $fitur->detailFiturs()->create(['name' => $newDetailName]);
                    Log::info('Detail baru ditambahkan:', ['id' => $baru->id, 'name' => $baru->name]);
                }
            }
        }

        return redirect()->back()->with('success', 'Fitur berhasil diperbarui.');
    }

    public function revisiproject(Request $request)
    {
        Log::info('>>> MASUK KE revisiproject');

        $request->validate([
            'detailfitur_id' => 'required|exists:detail_fiturs,id',
            'project_job_type_id' => 'required|exists:project_job_types,id',
            'note' => 'required|string|max:1000',
        ]);

        Log::info('Revisi Project Data:', [
            'detailfitur_id' => $request->detailfitur_id,
            'project_job_type_id' => $request->project_job_type_id,
            'note' => $request->note,
        ]);

        $revisi = RevisiProject::create([
            'detailfitur_id' => $request->detailfitur_id,
            'project_job_type_id' => $request->project_job_type_id,
            'note' => $request->note,
        ]);

        Log::info('RevisiProject berhasil disimpan:', $revisi->toArray());

        return back()->with('success', 'Catatan revisi berhasil disimpan.');
    }

    public function destroy($id)
    {
        $fitur = Fitur::findOrFail($id);

        // Hapus semua detail fitur terkait
        $fitur->detailFiturs()->delete();

        // Hapus fitur-nya
        $fitur->delete();
        return redirect()->back()->with('success', 'fitur berhasil dihapus');
    }
}
