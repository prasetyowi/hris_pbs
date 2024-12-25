<?php

namespace App\Http\Controllers;

use App\Models\KategoriTunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class KategoriTunjanganController extends Controller
{
    public function index()
    {
        $data = KategoriTunjangan::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = KategoriTunjangan::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'kategori_tunjangan_kode' => 'required|unique:kategori_tunjangan,kategori_tunjangan_kode|string|max:255',
                'kategori_tunjangan_nama' => 'required|string|max:255',
            ]);

            $data = KategoriTunjangan::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = KategoriTunjangan::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'kategori_tunjangan_kode' => 'required|unique:kategori_tunjangan,kategori_tunjangan_kode|string|max:255',
                'kategori_tunjangan_nama' => 'required|string|max:255',
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $KategoriTunjangan = KategoriTunjangan::find($id);

        if (!$KategoriTunjangan) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $KategoriTunjangan->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKategoriTunjanganAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('kategori_tunjangan')
                ->where('kategori_tunjangan_is_aktif', 1)
                ->orderBy('kategori_tunjangan_nama', 'asc')
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
