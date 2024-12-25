<?php

namespace App\Http\Controllers;

use App\Models\TunjanganDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TunjanganDetailController extends Controller
{
    public function index()
    {
        $data = TunjanganDetail::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = TunjanganDetail::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'tunjangan_detail_id' => 'required|unique:tunjangan_detail,tunjangan_detail_id|string|max:255',
                'tunjangan_id' => 'required|string|max:255',
                'kategori_absensi_id' => 'required|string|max:255'
            ]);

            $data = TunjanganDetail::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = TunjanganDetail::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'tunjangan_id' => 'required|string|max:255',
                'kategori_absensi_id' => 'required|string|max:255'
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $TunjanganDetail = TunjanganDetail::find($id);

        if (!$TunjanganDetail) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $TunjanganDetail->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
