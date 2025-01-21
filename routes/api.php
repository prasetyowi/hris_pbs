<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanDetailController;
use App\Http\Controllers\KaryawanDivisiController;
use App\Http\Controllers\KaryawanKeluargaController;
use App\Http\Controllers\KaryawanLevelController;
use App\Http\Controllers\KategoriTunjanganController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\TunjanganDetailController;
use App\Http\Controllers\KategoriAbsensiController;
use App\Http\Controllers\LiburNasionalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TransPayrollController;
use App\Http\Controllers\TransPayrollDetailController;
use App\Http\Controllers\TransPayrollDetail2Controller;
use App\Http\Controllers\AuthenticateController;

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

Route::post('login', [AuthenticateController::class, 'login']);

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
Route::apiResource('KategoriTunjangan', KategoriTunjanganController::class);
Route::get('KategoriTunjanganAktif', [KategoriTunjanganController::class, 'getKategoriTunjanganAktif']);
Route::apiResource('Tunjangan', TunjanganController::class);
Route::apiResource('TunjanganDetail', TunjanganDetailController::class);
Route::apiResource('LiburNasional', LiburNasionalController::class);
Route::get('LiburNasionalAktif', [LiburNasionalController::class, 'getLiburNasionalAktif']);
Route::apiResource('KategoriAbsensi', KategoriAbsensiController::class);
Route::get('KategoriAbsensiAktif', [KategoriAbsensiController::class, 'getKategoriAbsensiAktif']);
Route::apiResource('Attendance', AttendanceController::class);
Route::apiResource('TransPayroll', TransPayrollController::class);
Route::apiResource('TransPayrollDetail', TransPayrollDetailController::class);
Route::apiResource('TransPayrollDetail2', TransPayrollDetail2Controller::class);
