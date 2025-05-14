<?php

namespace App\Http\Controllers;

use App\Models\CategoryProject;
use App\Models\CategoryProjectsDetail;
use App\Models\JobType;
use App\Models\Project;
use App\Models\ProjectJobTypes;
use App\Models\ValueProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardProjectController extends Controller
{
    // Menampilkan daftar project (index)
    public function index()
    {
        // Ambil proyek dengan fiturnya dan detail fitur termasuk pengerjaan
        $project = Project::where('id_project_manager', Auth::id())
            ->with(['fiturs.detailFiturs.pengerjaans.user'])
            ->paginate(10);

        // Mapping untuk menghitung progres total per project
        $project->map(function ($proj) {
            $totalProgress = 0;
            $totalDetailFitur = 0;

            foreach ($proj->fiturs as $fitur) {
                foreach ($fitur->detailFiturs as $detail) {
                    $totalDetailFitur++;

                    // Ambil semua pengerjaan untuk detail fitur ini
                    $pengerjaans = $detail->pengerjaans;
                    $jumlahPengerjaan = $pengerjaans->sum('pengerjaan');
                    $jumlahOrang = $pengerjaans->count();

                    // Hitung rata-rata progress per detail fitur
                    $rataRataProgress = $jumlahOrang > 0 ? $jumlahPengerjaan / $jumlahOrang : 0;
                    $totalProgress += $rataRataProgress;
                }
            }

            // Nilai akhir: rata-rata progress semua detail fitur
            $proj->jumlah_pengerjaan = round($totalProgress, 2);
            $proj->persentase_pengerjaan = $totalDetailFitur > 0 ? round($totalProgress / $totalDetailFitur, 2) : 0;

            return $proj;
        });

        // Kirim data project beserta progresnya ke view
        return view('back-end.pages.project.index', compact('project')); // 'project' passed to the view
    }

    // Menampilkan form tambah project
    public function create()
    {
        // Ambil semua kategori proyek
        $categoryProject = CategoryProject::all();

        // Ambil semua pekerjaan terkait kategori proyek, jika ada (dapat disesuaikan sesuai logika)
        $jobTypes = CategoryProjectsDetail::with('jobType')->get();

        // Kirim data ke view
        return view('back-end.pages.project.create', compact('categoryProject', 'jobTypes'));
    }

    // Menyimpan project baru
    public function store(Request $request)
    {
        // Tambahkan log untuk melihat data yang dikirimkan melalui form
        Log::debug('Request Data:', ['data' => $request->all()]);

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required',
            'category_id' => 'required|exists:category_projects,id',
            'job_type_ids' => 'array',
            'job_type_ids.*' => 'exists:job_types,id',
            'value_project' => 'nullable|numeric', // validasi opsional
        ]);

        // Proses value_project untuk menghapus simbol "Rp" dan tanda koma
        if ($request->filled('value_project')) {
            // Hapus simbol "Rp", spasi, dan tanda koma
            $valueProject = preg_replace('/[^\d,]/', '', $request->value_project); // Hapus semua karakter non-numeric dan "Rp"
            // Ganti tanda koma dengan titik untuk konversi angka
            $valueProject = str_replace(',', '.', $valueProject);
            // Ubah menjadi numerik
            $valueProject = (float) $valueProject;
        }

        // Simpan project utama
        $project = Project::create([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
            'id_project_manager' => Auth::id(),
        ]);

        Log::debug('Project saved:', ['project' => $project->toArray()]);

        // Simpan relasi pekerjaan
        foreach ($request->job_type_ids ?? [] as $jobTypeId) {
            DB::table('project_job_types')->insert([
                'project_id' => $project->id,
                'category_id' => $request->category_id,
                'job_id' => $jobTypeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Cek apakah user adalah Marketing dan apakah value_project ada
        if (Auth::user()->role && strtolower(Auth::user()->role->name) === 'marketing' && isset($valueProject)) {
            // Log nilai value_project yang diterima
            Log::debug('Value Project to be saved:', ['value_project' => $valueProject]);

            // Simpan ke tabel ValueProject
            $valueProjectModel = ValueProject::create([
                'project_id' => $project->id,
                'value_project' => $valueProject, // Simpan dalam format numerik
            ]);

            // Log data setelah berhasil disimpan
            Log::debug('Value Project saved:', ['value_project' => $valueProjectModel->toArray()]);
        }

        return redirect()->route('project.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function getJobTypesByCategory($categoryId)
    {
        $category = CategoryProject::find($categoryId);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $jobTypes = $category->jobTypes;
        return response()->json(['jobTypes' => $jobTypes]);
    }

    public function getJobTypesByCategoryForEdit($categoryId, $projectId)
    {
        $category = CategoryProject::with('jobTypes')->find($categoryId);
        $project = Project::with('jobTypes')->find($projectId);

        if (!$category) {
            return response()->json(['jobTypes' => []]);
        }

        $jobTypes = $category->jobTypes->map(function ($jobType) use ($project) {
            return [
                'id' => $jobType->id,
                'name' => $jobType->name,
                'selected' => $project->jobTypes->contains('id', $jobType->id),
            ];
        });

        return response()->json(['jobTypes' => $jobTypes]);
    }

    public function show(string $id)
    {
        $project = Project::with(['fiturs.detailFiturs.pengerjaans.user'])->findOrFail($id);

        $fiturWithProgress = $project->fiturs->map(function ($fitur) {
            foreach ($fitur->detailFiturs as $detail) {
                $progressMap = [];

                foreach ($detail->pengerjaans as $pengerjaan) {
                    $key = $pengerjaan->user_id . '-' . $pengerjaan->job_type;
                    $progressMap[$key] = $pengerjaan->pengerjaan;
                }

                $detail->progress_by_user = $progressMap;
            }

            return $fitur;
        });

        // Ambil semua user dan job_type unik dari pengerjaan
        $allUsers = collect();
        foreach ($fiturWithProgress as $fitur) {
            foreach ($fitur->detailFiturs as $detail) {
                foreach ($detail->pengerjaans as $pengerjaan) {
                    $allUsers->push([
                        'id' => $pengerjaan->user->id,
                        'name' => $pengerjaan->user->name,
                        'job_type' => $pengerjaan->job_type,
                    ]);
                }
            }
        }

        // Kelompokkan user berdasarkan job_type (tanpa duplikat)
        $groupedUsers = $allUsers->unique(fn($user) => $user['id'] . '-' . $user['job_type'])->groupBy('job_type');

        // Hitung total progress berdasarkan slot yang seharusnya diisi
        $totalProgress = 0;
        $totalSlot = 0;

        foreach ($fiturWithProgress as $fitur) {
            foreach ($fitur->detailFiturs as $detail) {
                foreach ($groupedUsers as $users) {
                    foreach ($users as $user) {
                        $key = $user['id'] . '-' . $user['job_type'];
                        $progress = $detail->progress_by_user[$key] ?? 0;

                        $totalProgress += $progress;
                        $totalSlot++;
                    }
                }
            }
        }

        $totalAll = $totalSlot > 0 ? $totalProgress / $totalSlot : 0;

        return view('back-end.pages.project.show', compact('project', 'fiturWithProgress', 'groupedUsers', 'totalAll'));
    }

    // Menampilkan form untuk edit project
    public function edit($id)
    {
        $project = Project::with('jobTypes')->find($id); // Menggunakan $id yang diterima dari parameter
        $categoryProject = CategoryProject::all(); // Ambil kategori project
        $jobTypes = CategoryProjectsDetail::with('jobType')->get();

        return view('back-end.pages.project.edit', compact('project', 'categoryProject', 'jobTypes'));
    }

    // Menyimpan pembaruan project
    public function update(Request $request, Project $project)
    {
        if ($project->id_project_manager !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'category_id' => 'required|exists:category_projects,id',
            'job_types' => 'nullable|array',
            'job_types.*' => 'exists:job_types,id',
        ]);

        // Update informasi proyek
        $project->update([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
        ]);

        // Menyinkronkan job types ke proyek di tabel project_job_types
        $project->jobTypes()->sync(
            collect($request->job_types ?? [])->mapWithKeys(function ($jobTypeId) use ($request) {
                return [$jobTypeId => ['category_id' => $request->category_id]];
            }),
        );

        return redirect()->route('project.index')->with('success', 'Project berhasil diperbarui.');
    }

    // Menghapus project
    public function destroy(Project $project)
    {
        if ($project->id_project_manager !== Auth::id()) {
            abort(403);
        }

        // Hapus relasi pekerjaan
        $project->jobTypes()->detach();

        // Hapus value_project jika ada
        if ($project->valueProject) {
            $project->valueProject->delete();
        }

        // Hapus project
        $project->delete();

        return redirect()->route('project.index')->with('success', 'Project berhasil dihapus.');
    }
}
