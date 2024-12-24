<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Exception;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = Karyawan::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'karyawan_nip' => 'required|unique:karyawan,karyawan_nip|string|max:255',
                'karyawan_nik' => 'required|unique:karyawan,karyawan_nik|string|max:255',
                'karyawan_telepon' => 'required|string|max:255',
                'karyawan_nama' => 'required|string',
                'karyawan_email' => 'required|email|unique:karyawan,karyawan_email',
                'karyawan_basic_salary' => 'required|numeric',
                'karyawan_basic_bpjs' => 'required|numeric',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = Karyawan::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = Karyawan::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        try {
            $validated = $request->validate([
                'karyawan_nip' => 'required|string|max:255',
                'karyawan_nik' => 'required|string|max:255',
                'karyawan_telepon' => 'required|string|max:255',
                'karyawan_nama' => 'required|string',
                'karyawan_email' => 'required|email',
                'karyawan_basic_salary' => 'required|numeric',
                'karyawan_basic_bpjs' => 'required|numeric',
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
        $Karyawan = Karyawan::find($id);

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
