<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use Illuminate\Http\Request;

class DashboardJobTypeController extends Controller
{
    public function index()
    {
        $jobType = JobType::all();
        return view('back-end.pages.job_types.index', compact('jobType'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|string|max:255',
        ]);

        $jobType = JobType::create($validatedData);

        return redirect()->back()->with('success', 'Berhasil disimpan!');
    }

    public function update(Request $request, string $id)
    {
        $jobType = JobType::findOrFail($id);

        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
        ]);

        $jobType->name = $request->name;
        $jobType->save();

        return redirect()->back()->with('success', 'Berhasil Diperbarui');
    }

    public function destroy(string $id)
    {
        $jobType = JobType::findOrFail($id);

        $jobType->delete();

        return redirect()->back()->with('success', 'Berhasil dihapus!');
    }
    public function getJobTypesByCategory($categoryId)
    {
        $jobTypes = JobType::where('category_id', $categoryId)->get();
        return response()->json(['jobTypes' => $jobTypes]);
    }
}
