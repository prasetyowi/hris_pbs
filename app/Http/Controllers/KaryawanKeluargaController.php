<?php

namespace App\Http\Controllers;

use App\Models\KaryawanKeluarga;
use Illuminate\Http\Request;
use Exception;

class KaryawanKeluargaController extends Controller
{
    public function index()
    {
        $data = KaryawanKeluarga::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        return KaryawanKeluarga::findOrFail($id);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'karyawan_id' => 'required|exists:karyawan,karyawan_id',
                'karyawan_keluarga_nama' => 'required|string|max:255',
                'karyawan_keluarga_tanggal_lahir' => 'required|date',
                'karyawan_keluarga_hub_keluarga' => 'required|string|max:255',
                'karyawan_keluarga_jenis_kelamin' => 'required|string|max:255',
                'karyawan_keluarga_agama' => 'required|string|max:255',
                'karyawan_keluarga_pendidikan' => 'required|string|max:255',
                'karyawan_keluarga_is_aktif' => 'required|boolean',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = KaryawanKeluarga::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {

        $karyawanKeluarga = karyawanKeluarga::find($id);
        if (!$karyawanKeluarga) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'karyawan_id' => 'required|exists:karyawan,karyawan_id',
                'karyawan_keluarga_nama' => 'required|string|max:255',
                'karyawan_keluarga_tanggal_lahir' => 'required|date',
                'karyawan_keluarga_hub_keluarga' => 'required|string|max:255',
                'karyawan_keluarga_jenis_kelamin' => 'required|string|max:255',
                'karyawan_keluarga_agama' => 'required|string|max:255',
                'karyawan_keluarga_pendidikan' => 'required|string|max:255',
                'karyawan_keluarga_is_aktif' => 'required|boolean',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $karyawanKeluarga->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $karyawanKeluarga], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $karyawanKeluarga = karyawanKeluarga::find($id);

        if (!$karyawanKeluarga) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $karyawanKeluarga->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
