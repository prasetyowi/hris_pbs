<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanDetailController;
use App\Http\Controllers\KaryawanDivisiController;
use App\Http\Controllers\KaryawanKeluargaController;
use App\Http\Controllers\KaryawanLevelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('Karyawan', KaryawanController::class);
Route::apiResource('KaryawanDetail', KaryawanDetailController::class);
Route::apiResource('KaryawanDivisi', KaryawanDivisiController::class);
Route::get('KaryawanDivisiAktif', [KaryawanDivisiController::class, 'getKaryawanDivisiAktif']);
Route::apiResource('KaryawanLevel', KaryawanLevelController::class);
Route::get('KaryawanLevelAktif', [KaryawanLevelController::class, 'getKaryawanLevelAktif']);
Route::apiResource('KaryawanKeluarga', KaryawanKeluargaController::class);
