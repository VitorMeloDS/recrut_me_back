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

Route::middleware(['auth.sanctum.custom'])->group(function () {
    Route::post('/user', [AuthController::class, 'createUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/is-auth', [AuthController::class, 'isAuth']);


    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy']);

    Route::get('/menu', [MenuController::class, 'index']);

    Route::get('/invite', [InviteController::class, 'index']);
    Route::post('/invite', [InviteController::class, 'sendEmail']);

    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::get('/employee/export', [EmployeeController::class, 'exportToExcel']);
    Route::get('/employee/{id}', [EmployeeController::class, 'show']);
    Route::put('/employee/{id}/profile', [EmployeeController::class, 'updateProfile']);
});