<?php

namespace App\Http\Controllers;

use App\Models\KaryawanDetail;
use Illuminate\Http\Request;
use Exception;

class KaryawanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = KaryawanDetail::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'karyawan_id' => 'required|exists:karyawan,karyawan_id',
                'karyawan_detail_alamat' => 'required|string',
                'karyawan_detail_kelurahan' => 'required|string',
                'karyawan_detail_kecamatan' => 'required|string',
                'karyawan_detail_kota' => 'required|string',
                'karyawan_detail_propinsi' => 'required|string',
                'karyawan_detail_kodepos' => 'required|string',
                'karyawan_detail_phone' => 'required|string',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = KaryawanDetail::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = KaryawanDetail::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = KaryawanDetail::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        try {
            $validated = $request->validate([
                'karyawan_id' => 'required|exists:karyawan,karyawan_id',
                'karyawan_detail_alamat' => 'required|string',
                'karyawan_detail_kelurahan' => 'required|string',
                'karyawan_detail_kecamatan' => 'required|string',
                'karyawan_detail_kota' => 'required|string',
                'karyawan_detail_propinsi' => 'required|string',
                'karyawan_detail_kodepos' => 'required|string',
                'karyawan_detail_phone' => 'required|string',
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

        $Karyawan = KaryawanDetail::find($id);

        if (!$Karyawan) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Karyawan->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
