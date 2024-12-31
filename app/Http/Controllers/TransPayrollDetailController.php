<?php

namespace App\Http\Controllers;

use App\Models\TransPayrollDetail;
use Illuminate\Http\Request;
use Exception;

class TransPayrollDetailController extends Controller
{
    public function index()
    {
        $data = TransPayrollDetail::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'trans_payroll_detail_id' => 'required|unique:trans_payroll_detail,trans_payroll_detail_id|string|max:255',
                'trans_payroll_id' => 'required|string|max:255',
                'karyawan_id' => 'required|string|max:255',
                'trans_payroll_detail_status' => 'required|string|max:255',
                'trans_payroll_detail_keterangan' => '',
                'trans_payroll_detail_tgl_transfer' => 'required|date',
                'trans_payroll_detail_is_generate_pph21' => '',
                'trans_payroll_detail_tgl_kirim_payslip' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayrollDetail::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = TransPayrollDetail::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = TransPayrollDetail::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'trans_payroll_id' => 'required|string|max:255',
                'karyawan_id' => 'required|string|max:255',
                'trans_payroll_detail_status' => 'required|string|max:255',
                'trans_payroll_detail_keterangan' => '',
                'trans_payroll_detail_tgl_transfer' => 'required|date',
                'trans_payroll_detail_is_generate_pph21' => '',
                'trans_payroll_detail_tgl_kirim_payslip' => '',
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
        $TransPayroll = TransPayrollDetail::find($id);

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
}
