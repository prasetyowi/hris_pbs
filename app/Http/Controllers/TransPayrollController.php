<?php

namespace App\Http\Controllers;

use App\Models\TransPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TransPayrollController extends Controller
{
    public function index()
    {
        $data = TransPayroll::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'trans_payroll_id' => 'required|unique:trans_payroll,trans_payroll_id|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_id' => 'required|string|max:255',
                'trans_payroll_status' => 'required|string|max:255',
                'trans_payroll_periode_bln' => 'required|numeric',
                'trans_payroll_periode_thn' => 'required|numeric',
                'trans_payrolle_who_create' => '',
                'trans_payroll_tgl_create' => '',
                'trans_payroll_who_update' => '',
                'trans_payroll_tgl_update' => '',
                'jenis_pajak' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayroll::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = TransPayroll::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = TransPayroll::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_id' => 'required|string|max:255',
                'trans_payroll_status' => 'required|string|max:255',
                'trans_payroll_periode_bln' => 'required|numeric',
                'trans_payroll_periode_thn' => 'required|numeric',
                'trans_payrolle_who_create' => '',
                'trans_payroll_tgl_create' => '',
                'trans_payroll_who_update' => '',
                'trans_payroll_tgl_update' => '',
                'jenis_pajak' => '',
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
        $TransPayroll = TransPayroll::find($id);

        if (!$TransPayroll) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $TransPayroll->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function proses_simpan_hasil_hitung_payroll_temp(Request $request)
    {
        // dd('Route berhasil diakses');

        $attendance_id = $request['attendance_id'];
        $pengguna_username = $request['pengguna_username'];

        try {
            $data = DB::select("exec proses_simpan_hasil_hitung_payroll_temp '$attendance_id','$pengguna_username'");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }
}
