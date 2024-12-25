<?php

namespace App\Http\Controllers;

use App\Models\LiburNasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class LiburNasionalController extends Controller
{
    public function index()
    {
        $data = LiburNasional::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = LiburNasional::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'libur_nasional_id' => 'required|unique:libur_nasional,libur_nasional_id|string|max:255',
                'libur_nasional_tahun' => 'required|numeric',
                'libur_nasional_tanggal' => 'required|date',
                'libur_nasional_nama' => 'required|string|max:255'
            ]);

            $data = LiburNasional::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = LiburNasional::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'libur_nasional_tahun' => 'required|numeric',
                'libur_nasional_tanggal' => 'required|date',
                'libur_nasional_nama' => 'required|string|max:255'
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $LiburNasional = LiburNasional::find($id);

        if (!$LiburNasional) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $LiburNasional->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getLiburNasionalAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('libur_nasional')
                ->where('libur_nasional_is_aktif', 1)
                ->orderBy('libur_nasional_tanggal', 'asc')
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
