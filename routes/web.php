<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardCategoryProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardDetailFiturController;
use App\Http\Controllers\DashboardFiturController;
use App\Http\Controllers\DashboardJobTypeController;
use App\Http\Controllers\DashboardPengerjaanController;
use App\Http\Controllers\DashboardPenugasanController;
use App\Http\Controllers\DashboardProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RolesController;
use App\Http\Middleware\CheckMenuAccess;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:admin', CheckMenuAccess::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/roles', RolesController::class);
    Route::resource('/register', RegisterController::class);
    Route::resource('/job-type', DashboardJobTypeController::class);
    Route::resource('/project', DashboardProjectController::class);
    Route::resource('/detail-fitur', DashboardDetailFiturController::class);
    Route::resource('/pengerjaan', DashboardPengerjaanController::class);
    Route::get('/penugasan/project/{project}', [DashboardPenugasanController::class, 'index'])->name('penugasan.index');
    Route::post('penugasan/{project}', [DashboardPenugasanController::class, 'store'])->name('penugasan.store');
    // Route::resource('/penugasan', DashboardPenugasanController::class);
    Route::resource('/category-project', DashboardCategoryProjectController::class);
    Route::get('/fitur/project/{project}', [DashboardFiturController::class, 'index'])->name('fitur.index');
    Route::post('fitur/{project}', [DashboardFiturController::class, 'store'])->name('fitur.store');
    Route::put('/fitur/{fitur}', [DashboardFiturController::class, 'update'])->name('fitur.update');
    Route::delete('/fitur/{id}', [DashboardFiturController::class, 'destroy'])->name('fitur.destroy');
    Route::get('pengerjaan/create/{project_id}', [DashboardPengerjaanController::class, 'create'])->name('pengerjaan.tambah');

});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/get-job-types-by-category/{id}', [DashboardProjectController::class, 'getJobTypesByCategory']);
Route::get('/get-job-types-by-category-edit/{categoryId}/{projectId}', [DashboardProjectController::class, 'getJobTypesByCategoryForEdit']);
