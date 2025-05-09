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
    Route::resource('/fitur', DashboardFiturController::class);
    Route::resource('/detail-fitur', DashboardDetailFiturController::class);
    Route::resource('/pengerjaan', DashboardPengerjaanController::class);
    Route::get('/penugasan/project/{project}', [DashboardPenugasanController::class, 'index'])->name('penugasan.index');
    Route::post('penugasan/{project}', [DashboardPenugasanController::class, 'store'])->name('penugasan.store');
    // Route::resource('/penugasan', DashboardPenugasanController::class);
    Route::resource('/category-project', DashboardCategoryProjectController::class);
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
