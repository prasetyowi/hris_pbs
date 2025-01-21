<?php

namespace App\Http\Controllers;

use App\Models\SkemaTunjanganDetail;
use Illuminate\Http\Request;
use Exception;

class SkemaTunjanganDetailController extends Controller
{
    public function index()
    {
        $data = SkemaTunjanganDetail::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'skema_tunjangan_detail_id' => 'required|unique:skema_tunjangan_detail,skema_tunjangan_detail_id|string|max:255',
                'skema_tunjangan_id' => 'required|string|max:255',
                'tunjangan_id' => 'required|string|max:255',
                'skema_tunjangan_jenis' => '',
                'skema_tunjangan_detail_value' => '',
                'skema_tunjangan_detail_flag_autogen' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = SkemaTunjanganDetail::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = SkemaTunjanganDetail::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = SkemaTunjanganDetail::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'skema_tunjangan_id' => 'required|string|max:255',
                'tunjangan_id' => 'required|string|max:255',
                'skema_tunjangan_jenis' => '',
                'skema_tunjangan_detail_value' => '',
                'skema_tunjangan_detail_flag_autogen' => '',
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
        $SkemaTunjangan = SkemaTunjanganDetail::find($id);

        if (!$SkemaTunjangan) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $SkemaTunjangan->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
