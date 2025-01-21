<?php

namespace App\Http\Controllers;

use App\Models\SkemaTunjangan;
use Illuminate\Http\Request;
use Exception;

class SkemaTunjanganController extends Controller
{
    public function index()
    {
        $data = SkemaTunjangan::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'skema_tunjangan_id' => 'required|unique:skema_tunjangan,skema_tunjangan_id|string|max:250',
                'client_wms_id' => '',
                'depo_id' => '',
                'karyawan_divisi_id' => '',
                'karyawan_level_id' => '',
                'skema_tunjangan_kode' => 'required|unique:skema_tunjangan,skema_tunjangan_kode|string|max:250',
                'skema_tunjangan_nama' => 'required|string|max:250',
                'skema_tunjangan_keterangan' => '',
                'skema_tunjangan_is_aktif' => 'required|string',
                'skema_tunjangan_who_create' => 'required|string|max:250',
                'skema_tunjangan_tgl_create' => 'required|string|max:250',
                'skema_tunjangan_who_update' => 'required|string|max:250',
                'skema_tunjangan_tgl_update' => 'required|string|max:250',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = SkemaTunjangan::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = SkemaTunjangan::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = SkemaTunjangan::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'skema_tunjangan_id' => 'required|unique:skema_tunjangan,skema_tunjangan_id|string|max:250',
                'client_wms_id' => '',
                'depo_id' => '',
                'karyawan_divisi_id' => '',
                'karyawan_level_id' => '',
                'skema_tunjangan_kode' => 'required|unique:skema_tunjangan,skema_tunjangan_kode|string|max:250',
                'skema_tunjangan_nama' => 'required|string|max:250',
                'skema_tunjangan_keterangan' => '',
                'skema_tunjangan_is_aktif' => 'required|string',
                'skema_tunjangan_who_create' => 'required|string|max:250',
                'skema_tunjangan_tgl_create' => 'required|string|max:250',
                'skema_tunjangan_who_update' => 'required|string|max:250',
                'skema_tunjangan_tgl_update' => 'required|string|max:250',
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
        $SkemaTunjangan = SkemaTunjangan::find($id);

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
