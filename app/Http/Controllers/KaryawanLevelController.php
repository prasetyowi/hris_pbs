<?php

namespace App\Http\Controllers;

use App\Models\KaryawanLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class KaryawanLevelController extends Controller
{
    public function index()
    {
        $data = KaryawanLevel::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = KaryawanLevel::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'karyawan_level_kode' => 'required|unique:karyawan_level,karyawan_level_kode|string|max:255',
                'karyawan_level_nama' => 'required|string|max:255',
            ]);

            $data = KaryawanLevel::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = KaryawanLevel::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'karyawan_level_kode' => 'required|string|max:255',
                'karyawan_level_nama' => 'required|string|max:255',
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $KaryawanLevel = KaryawanLevel::find($id);

        if (!$KaryawanLevel) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $KaryawanLevel->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKaryawanLevelAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('karyawan_level')
                ->where('karyawan_level_is_aktif', 1)
                ->orderBy('karyawan_level_nama', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            }

            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }
}
