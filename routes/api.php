<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarKriteriaController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\JuriController;
use App\Http\Controllers\KaryaController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('lomba', LombaController::class);
Route::apiResource('informasi', InformasiController::class);
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    Route::apiResource('kriteria', KriteriaController::class);
    Route::apiResource('daftarKriteria', DaftarKriteriaController::class);
    Route::apiResource('users', UserController::class);

    Route::apiResource('penilaian', PenilaianController::class);
    Route::apiResource('pembayaran', PembayaranController::class);
    Route::prefix('pendaftaran')->group(function () {
        Route::apiResource('/', PendaftaranController::class);
    
        Route::post('/registrasi-1', [PendaftaranController::class, 'registrasiKetua']);
        Route::post('/registrasi-2', [PendaftaranController::class, 'registrasiAnggota']);
        Route::post('/registrasi-3', [PendaftaranController::class, 'registrasiPembayaran']);
        Route::post('/registrasi-4', [PendaftaranController::class, 'registrasiBuktiFollow']);
    
    
        Route::get('/{id}', [PendaftaranController::class, 'show']);
        Route::post('/{id}', [PendaftaranController::class, 'update']);
        Route::delete('/{id}', [PendaftaranController::class, 'destroy']);
        Route::get('/user/{user_id}', [PendaftaranController::class, 'getByUserId']);
    });
});
Route::apiResource('karya', KaryaController::class);
// Route::prefix('pembayaran')->group(function () {

//     Route::get('/{Pembayaran}', [PembayaranController::class, 'show']);
//     Route::put('/{Pembayaran}', [PembayaranController::class, 'update']);
//     Route::put('/{Pembayaran}', [PembayaranController::class, 'update']);
//     Route::delete('/{Pembayaran}', [PembayaranController::class, 'destroy']);
// });
// Route::middleware(['jwt.auth', 'role:user'])->group(function () {
//     Route::get('/user/dashboard', [UserController::class, 'index']);
// });

// Route::middleware(['jwt.auth', 'role:juri'])->group(function () {
//     Route::get('/juri/dashboard', [JuriController::class, 'index']);
// });


Route::get('/dashboard', [AuthController::class, 'dashboard']);