<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Exception;

class AbsensiController extends Controller
{
    public function index()
    {
        $data = Absensi::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'absensi_id' => 'required|unique:absensi.absensi_id|string|max:255',
                'attendance_id' => 'required|string|max:255',
                'karyawan_id' => 'required|string|max:255',
                'tgl_check_in' => 'required|datetime',
                'tgl_check_out' => 'required|datetime',
                'absensi_who_create' => 'required|string|max:255',
                'absensi_tgl_create' => 'required',
                'absensi_who_update' => 'required|string|max:255',
                'absensi_tgl_update' => 'required',
                'perusahaan_id' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = Absensi::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = Absensi::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = Absensi::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Absensi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'absensi_id' => 'required|unique:absensi.absensi_id|string|max:255',
                'attendance_id' => 'required|string|max:255',
                'karyawan_id' => 'required|string|max:255',
                'tgl_check_in' => 'required|datetime',
                'tgl_check_out' => 'required|datetime',
                'absensi_who_create' => 'required|string|max:255',
                'absensi_tgl_create' => 'required',
                'absensi_who_update' => 'required|string|max:255',
                'absensi_tgl_update' => 'required',
                'perusahaan_id' => '',
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
        $Absensi = Absensi::find($id);

        if (!$Absensi) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Absensi->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
