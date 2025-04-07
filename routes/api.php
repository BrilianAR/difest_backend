<?php

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
Route::get('/dashboard', [AuthController::class, 'dashboard']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::apiResource('informasi', InformasiController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('lomba', LombaController::class);

// Route API Pembayaran
Route::prefix('pembayaran')->group(function () {
    Route::apiResource('/', PembayaranController::class);

    Route::get('/{Pembayaran}', [PembayaranController::class, 'show']);
    Route::post('/{Pembayaran}', [PembayaranController::class, 'update']);
    Route::delete('/{Pembayaran}', [PembayaranController::class, 'destroy']);

});

// Route API Pendaftaran
Route::prefix('pendaftaran')->group(function () {
    Route::apiResource('/', PendaftaranController::class);

    Route::post('/registrasi-1', [PendaftaranController::class, 'registrasiKetua']);
    Route::post('/registrasi-2', [PendaftaranController::class, 'registrasiAnggota']);
    Route::post('/registrasi-3', [PendaftaranController::class, 'registrasiPembayaran']);
    Route::post('/registrasi-4', [PendaftaranController::class, 'registrasiBuktiFollow']);
    
    Route::get('/{id}', [PendaftaranController::class, 'show']);
    Route::post('/{id}', [PendaftaranController::class, 'update']);
    Route::delete('/{id}', [PendaftaranController::class, 'destroy']);

});

Route::apiResource('karya', KaryaController::class);
Route::apiResource('kriteria', KriteriaController::class);
Route::apiResource('daftarKriteria', DaftarKriteriaController::class);
Route::apiResource('juri', JuriController::class);
Route::apiResource('penilaian', PenilaianController::class);
