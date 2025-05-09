<?php

namespace App\Http\Controllers;

use App\Models\DetailFitur;
use App\Models\Fitur;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardFiturController extends Controller
{
    public function index($project)
    {
        $project = Project::with('fiturs.detailFiturs')->findOrFail($project);
        return view('back-end.pages.fitur.create', compact('project'));
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
