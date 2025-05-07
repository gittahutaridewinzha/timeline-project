<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RolesController;
use App\Http\Middleware\CheckMenuAccess;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:admin', CheckMenuAccess::class])->group(function () {
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('/roles', RolesController::class );
Route::resource('/register', RegisterController::class);
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
