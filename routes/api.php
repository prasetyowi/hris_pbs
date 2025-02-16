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
use App\Models\TransPayroll;

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

//route before login
Route::post('login', [AuthenticateController::class, 'login']);
Route::controller(VariableGlobalController::class)->group(function () {
    Route::get('GetNewId', 'get_newid');
    Route::post('attendance_timesheet', 'attendance_timesheet');
});


//route after login
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthenticateController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('Karyawan', KaryawanController::class);
    Route::post('GetPaginateKaryawan', [KaryawanController::class, 'Get_paginate_karyawan']);

    Route::apiResource('KaryawanDetail', KaryawanDetailController::class);

    Route::apiResource('KaryawanLevel', KaryawanLevelController::class);
    Route::get('KaryawanLevelAktif', [KaryawanLevelController::class, 'getKaryawanLevelAktif']);
    Route::get('getKaryawanLevelDivisi/{divisi}', [KaryawanLevelController::class, 'getKaryawanLevelDivisi']);
    Route::post('level-paginate', [KaryawanLevelController::class, 'paginate_level']);

    Route::apiResource('KaryawanKeluarga', KaryawanKeluargaController::class);

    Route::apiResource('KategoriTunjangan', KategoriTunjanganController::class);
    Route::get('KategoriTunjanganAktif', [KategoriTunjanganController::class, 'getKategoriTunjanganAktif']);
    Route::post('GetPaginateKategoriTunjangan', [KategoriTunjanganController::class, 'Get_paginate_kategori_tunjangan']);

    Route::apiResource('Tunjangan', TunjanganController::class);
    Route::get('getTunjanganAktif', [TunjanganController::class, 'Get_tunjangan_aktif']);
    Route::post('GetPaginateTunjangan', [TunjanganController::class, 'Get_paginate_tunjangan']);

    Route::apiResource('TunjanganDetail', TunjanganDetailController::class);

    Route::apiResource('LiburNasional', LiburNasionalController::class);
    Route::get('LiburNasionalAktif', [LiburNasionalController::class, 'getLiburNasionalAktif']);
    Route::post('GetPaginateLiburNasional', [LiburNasionalController::class, 'Get_paginate_libur_nasional']);
    Route::post('GetPaginateLiburNasionalIndex', [LiburNasionalController::class, 'Get_paginate_libur_nasional_index']);
    Route::delete('LiburNasional/{LiburNasional}/detail', [LiburNasionalController::class, 'destroyDetail'])->name('libur-nasional.destroy.detail');

    Route::apiResource('KategoriAbsensi', KategoriAbsensiController::class);
    Route::get('KategoriAbsensiAktif', [KategoriAbsensiController::class, 'getKategoriAbsensiAktif']);
    Route::post('GetPaginateKategoriAbsensi', [KategoriAbsensiController::class, 'Get_paginate_kategori_absensi']);

    Route::apiResource('Attendance', AttendanceController::class);
    Route::get('attendace_active', [AttendanceController::class, 'attendance_active']);
    Route::get('AttendanceDetail2byAttendanceId/{id}', [AttendanceController::class, 'Get_attendance_detail2_by_attendance_id']);
    Route::post('GetPaginateAttendance', [AttendanceController::class, 'Get_paginate_attendance']);

    Route::apiResource('TransPayroll', TransPayrollController::class);
    Route::post('ProsesHitungPayrollAsliKeTemp', [TransPayrollController::class, 'proses_hitung_payroll_asli_ke_temp']);
    Route::post('GetPaginateTransPayroll', [TransPayrollController::class, 'Get_paginate_trans_payroll']);
    Route::post('GetPaginateSummaryTransPayrollDetailTemp', [TransPayrollController::class, 'Get_paginate_summary_trans_payroll_detail_temp']);
    Route::post('GetPaginateSummaryTransPayrollDetail', [TransPayrollController::class, 'Get_paginate_summary_trans_payroll_detail']);
    Route::get('GetPeriodePayrollByPerusahaan', [TransPayrollController::class, 'Get_periode_payroll_by_perusahaan']);
    Route::get('GetPeriodePayrollByPerusahaanEdit/{id}', [TransPayrollController::class, 'Get_periode_payroll_by_perusahaan_edit']);
    Route::post('ProsesSimpanHasilHitungPayrollTemp', [TransPayrollController::class, 'Proses_simpan_hasil_hitung_payroll_temp']);
    Route::post('InsertTransPayrollDetail2Temp', [TransPayrollDetail2Controller::class, 'Insert_trans_payroll_detail2_temp']);
    Route::delete('DeleteTransPayrollDetail2TempByDtl2Id', [TransPayrollController::class, 'Delete_trans_payroll_detail2_temp_by_dtl2_id']);
    Route::post('GetListPayrollPaginate', [TransPayrollController::class, 'Get_list_payroll_paginate']);

    Route::apiResource('TransPayrollDetail', TransPayrollDetailController::class);
    Route::post('GetPaginateTransPayrollDetailById', [TransPayrollDetailController::class, 'Get_paginate_trans_payroll_detail_by_id']);

    Route::apiResource('TransPayrollDetail2', TransPayrollDetail2Controller::class);
    Route::get('TransPayrollDetail2TempByDtlId/{id}', [TransPayrollDetail2Controller::class, 'Get_trans_payroll_detail2_temp_by_dtl_id']);
    Route::post('GetPaginateTransPayrollDetail2TempByTransPayrollDetailId', [TransPayrollDetail2Controller::class, 'Get_paginate_trans_payroll_detail2_temp_by_trans_payroll_detail_id']);
    Route::post('GetTransPayrollDetail2TempByTransPayrollDetailId', [TransPayrollDetail2Controller::class, 'Get_trans_payroll_detail2_temp_by_trans_payroll_detail_id']);
    Route::post('GetTransPayrollDetail2ByTransPayrollDetailId', [TransPayrollDetail2Controller::class, 'Get_trans_payroll_detail2_by_trans_payroll_detail_id']);
    Route::post('UpdateTransPayrollDetail2TempByTransPayrollDetailId', [TransPayrollDetail2Controller::class, 'Update_transPayroll_detail2_temp_by_transPayroll_detail_id']);

    Route::get('GetTransPayrollAktif', [TransPayrollController::class, 'Get_trans_payroll_aktif']);
    Route::get('BankAktif', [TransPayrollController::class, 'Get_bank_aktif']);
    Route::apiResource('Absensi', AbsensiController::class);

    Route::post('GetPaginateSkemaTunjangan', [SkemaTunjanganController::class, 'Get_paginate_skema_tunjangan']);

    Route::apiResource('KaryawanDivisi', KaryawanDivisiController::class);
    Route::get('KaryawanDivisiAktif', [KaryawanDivisiController::class, 'getKaryawanDivisiAktif']);
    Route::post('divisi-paginate', [KaryawanDivisiController::class, 'paginate_disivi']);
});
