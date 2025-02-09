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
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\VariableGlobalController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\SkemaTunjanganController;

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
Route::get('getKaryawanLevelDivisi/{divisi}', [KaryawanLevelController::class, 'getKaryawanLevelDivisi']);
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
Route::get('AttendanceDetail2byAttendanceId/{id}', [AttendanceController::class, 'Get_attendance_detail2_by_attendance_id']);
Route::post('ProsesSimpanHasilHitungPayrollTemp', [TransPayrollController::class, 'proses_simpan_hasil_hitung_payroll_temp']);
Route::apiResource('TransPayroll', TransPayrollController::class);
Route::apiResource('TransPayrollDetail', TransPayrollDetailController::class);
Route::apiResource('TransPayrollDetail2', TransPayrollDetail2Controller::class);
Route::apiResource('Absensi', AbsensiController::class);
Route::get('GetNewId', [VariableGlobalController::class, 'get_newid']);
Route::post('GetPaginateKaryawan', [KaryawanController::class, 'Get_paginate_karyawan']);
Route::post('GetPaginateAttendance', [AttendanceController::class, 'Get_paginate_attendance']);
Route::post('GetPaginateKategoriAbsensi', [KategoriAbsensiController::class, 'Get_paginate_kategori_absensi']);
Route::post('GetPaginateKategoriTunjangan', [KategoriTunjanganController::class, 'Get_paginate_kategori_tunjangan']);
Route::post('GetPaginateLiburNasional', [LiburNasionalController::class, 'Get_paginate_libur_nasional']);
Route::post('GetPaginateLiburNasionalIndex', [LiburNasionalController::class, 'Get_paginate_libur_nasional_index']);
Route::post('GetPaginateSkemaTunjangan', [SkemaTunjanganController::class, 'Get_paginate_skema_tunjangan']);
Route::post('GetPaginateTransPayroll', [TransPayrollController::class, 'Get_paginate_trans_payroll']);
Route::post('GetPaginateTunjangan', [TunjanganController::class, 'Get_paginate_tunjangan']);

Route::delete('LiburNasional/{LiburNasional}/detail', [LiburNasionalController::class, 'destroyDetail'])->name('libur-nasional.destroy.detail');

Route::post('divisi-paginate', [KaryawanDivisiController::class, 'paginate_disivi']);
Route::post('level-paginate', [KaryawanLevelController::class, 'paginate_level']);
