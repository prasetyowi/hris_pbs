<?php

namespace App\Http\Controllers;

use App\Models\Tunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TunjanganController extends Controller
{
    public function index()
    {
        $data = Tunjangan::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = Tunjangan::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'tunjangan_id' => 'required|unique:tunjangan,tunjangan_id|string|max:255',
                'kategori_tunjangan_id' => 'required|string|max:255',
                'tunjangan_kode' => 'required|unique:tunjangan,tunjangan_kode|string|max:255',
                'tunjangan_nama' => 'required|unique:tunjangan,tunjangan_nama|string|max:255',
                'tunjangan_jenistunjangan' => 'required|string|max:255',
                'tunjangan_dasarbayar' => 'required|string|max:255',
                'tunjangan_dibayar_oleh' => 'required|string|max:255',
                'tunjangan_dibayar_kepada' => 'required|string|max:255'
            ]);

            $data = Tunjangan::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = Tunjangan::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'kategori_tunjangan_id' => 'required|string|max:255',
                'tunjangan_kode' => 'required|string|max:255',
                'tunjangan_nama' => 'required|string|max:255',
                'tunjangan_jenistunjangan' => 'required|string|max:255',
                'tunjangan_dasarbayar' => 'required|string|max:255',
                'tunjangan_dibayar_oleh' => 'required|string|max:255',
                'tunjangan_dibayar_kepada' => 'required|string|max:255'
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $Tunjangan = Tunjangan::find($id);

        if (!$Tunjangan) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Tunjangan->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
