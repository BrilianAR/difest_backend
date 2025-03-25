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
Route::apiResource('pembayaran', PembayaranController::class);
Route::apiResource('pendaftaran', PendaftaranController::class);
Route::apiResource('karya', KaryaController::class);
Route::apiResource('kriteria', KriteriaController::class);
Route::apiResource('daftarKriteria', DaftarKriteriaController::class);
Route::apiResource('juri', JuriController::class);
Route::apiResource('penilaian', PenilaianController::class);
