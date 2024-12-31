<?php

namespace App\Http\Controllers;

use App\Models\TransPayrollDetail2;
use Illuminate\Http\Request;
use Exception;

class TransPayrollDetail2Controller extends Controller
{
    public function index()
    {
        $data = TransPayrollDetail2::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'trans_payroll_detail2_id' => 'required|unique:trans_payroll_detail2,trans_payroll_detail2_id|string|max:255',
                'trans_payroll_detail_id' => 'required|string|max:255',
                'trans_payroll_id' => 'required|string|max:255',
                'tunjangan_id' => '',
                'tunjangan_nama' => '',
                'trans_payroll_detail2_multiplier' => 'required|',
                'trans_payroll_detail2_value' => 'required|numeric',
                'trans_payroll_detail2_totalvalue' => 'required|numeric',
                'trans_payroll_detail2_urut' => 'required|numeric',
                'trans_payroll_detail2_autogen' => 'required',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayrollDetail2::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = TransPayrollDetail2::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = TransPayrollDetail2::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'trans_payroll_detail_id' => 'required|string|max:255',
                'trans_payroll_id' => 'required|string|max:255',
                'tunjangan_id' => '',
                'tunjangan_nama' => '',
                'trans_payroll_detail2_multiplier' => 'required|',
                'trans_payroll_detail2_value' => 'required|numeric',
                'trans_payroll_detail2_totalvalue' => 'required|numeric',
                'trans_payroll_detail2_urut' => 'required|numeric',
                'trans_payroll_detail2_autogen' => 'required',
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
        $TransPayroll = TransPayrollDetail2::find($id);

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
