<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/cep/{cep}', [RegisterController::class, 'getAddressByCep']);
Route::get('/invite/verify-token', [InviteController::class, 'verifyToken']);

Route::middleware(['auth.sanctum.custom', 'auth:sanctum'])->group(function () {

    Route::middleware(['check.user.access'])->prefix('/profile')->group(function() {
        Route::get('', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('', [ProfileController::class, 'store'])->name('profile.store');
        Route::get('/{id}', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/{id}', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/user', [AuthController::class, 'user'])->name('user');
    Route::middleware(['check.user.access'])->post('/user', [AuthController::class, 'createUser'])->name('user');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/is-auth', [AuthController::class, 'isAuth'])->name('is-auth');

    Route::middleware(['check.user.access'])->get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::middleware(['check.user.access'])->prefix('/invite')->group(function() {
        Route::get('', [InviteController::class, 'index'])->name('invite.index');
        Route::post('', [InviteController::class, 'sendEmail'])->name('invite.sendEmail');
    });

    Route::middleware(['check.user.access'])->prefix('/employee')->group(function() {
        Route::get('', [EmployeeController::class, 'index'])->name('employee.index');
        Route::get('/export', [EmployeeController::class, 'exportToExcel'])->name('employee.exportToExcel');
        Route::get('/{id}', [EmployeeController::class, 'show'])->name('employee.show');
        Route::put('/{id}/profile', [EmployeeController::class, 'updateProfile'])->name('employee.updateProfile');
    });

});