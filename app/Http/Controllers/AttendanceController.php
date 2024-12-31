<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Exception;

class AttendanceController extends Controller
{
    public function index()
    {
        $data = Attendance::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'attendance_id' => 'required|unique:attendance,attendance_id|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_kode' => 'required|unique:attendance,attendance_kode|string|max:255',
                'attendance_thn_awal' => 'required|numeric',
                'attendance_bln_awal' => 'required|numeric',
                'attendance_tgl_awal' => 'required|date',
                'attendance_thn_akhir' => 'required|numeric',
                'attendance_bln_akhir' => 'required|numeric',
                'attendance_tgl_akhir' => 'required|date',
                'attendance_who_create' => '',
                'attendance_tgl_create' => '',
                'attendance_who_update' => '',
                'attendance_tgl_update' => '',
                'attendance_is_aktif' => 'required',
                'attendance_is_generate_pph21' => '',
                'attendance_periode_bln' => 'required|numeric',
                'attendance_periode_thn' => 'required|numeric',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = Attendance::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = Attendance::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = Attendance::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        try {
            $validated = $request->validate([
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_kode' => 'required|unique:attendance,attendance_kode|string|max:255',
                'attendance_thn_awal' => 'required|numeric',
                'attendance_bln_awal' => 'required|numeric',
                'attendance_tgl_awal' => 'required|date',
                'attendance_thn_akhir' => 'required|numeric',
                'attendance_bln_akhir' => 'required|numeric',
                'attendance_tgl_akhir' => 'required|date',
                'attendance_who_create' => '',
                'attendance_tgl_create' => '',
                'attendance_who_update' => '',
                'attendance_tgl_update' => '',
                'attendance_is_aktif' => 'required',
                'attendance_is_generate_pph21' => '',
                'attendance_periode_bln' => 'required|numeric',
                'attendance_periode_thn' => 'required|numeric',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $karyawan->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $karyawan], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $Attendance = Attendance::find($id);

        if (!$Attendance) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Attendance->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
