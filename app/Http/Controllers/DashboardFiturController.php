<?php

namespace App\Http\Controllers;

use App\Models\DetailFitur;
use App\Models\Fitur;
use App\Models\Project;
use Illuminate\Http\Request;

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
        $fitur->update(['name' => $request->name]);

        if ($request->has('details')) {
            foreach ($request->details as $detailId => $name) {
                DetailFitur::where('id', $detailId)->update(['name' => $name]);
            }
        }

        return redirect()->back()->with('success', 'Fitur berhasil diperbarui.');
    }

    public function destroy($id){
        $fitur = Fitur::findOrFail($id);
        $fitur->delete();
        return redirect()->back()->with('success','fitur berhasil dihapus');
    }
}
