<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PenggunaController extends Controller
{
    public function index()
    {
        $data = Pengguna::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                // Tambahkan validasi lain sesuai kebutuhan
                'pengguna_id' => 'required|unique:pengguna,pengguna_id|string|max:255',
                'pengguna_kode' => 'required|unique:pengguna,pengguna_kode|string|max:255',
                'pengguna_nama' => 'required|string|max:255',
                'pengguna_alamat' => '',
                'pengguna_no_telpon' => '',
                'pengguna_email' => 'required|unique:pengguna,pengguna_email|string|max:255',
                'pengguna_username' => 'required|unique:pengguna,pengguna_username|string|max:255',
                'pengguna_password' => 'required|string|max:255',
                'pengguna_tmpt_lahir' => '',
                'pengguna_tgl_lahir' => '',
                'pengguna_grup_id' => 'required|string|max:255',
                'pengguna_is_aktif' => 'required',
                'pengguna_pic' => '',
                'pengguna_who_create' => 'required|string|max:255',
                'pengguna_who_create_id' => 'required|string|max:255',
                'pengguna_date_create' => 'required|datetime',
                'pengguna_who_update' => 'required|string|max:255',
                'pengguna_who_update_id' => 'required|string|max:255',
                'pengguna_date_update' => 'required|datetime',
                'region_id' => '',
                'karyawan_id' => 'required|string|max:255',
                'pengguna_default_bahasa' => '',
                'pengguna_fingerprint' => '',
                'pengguna_aplikasi' => ''
            ]);

            $data = Pengguna::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = Pengguna::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = Pengguna::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'pengguna_kode' => 'required|string|max:255',
                'pengguna_nama' => 'required|string|max:255',
                'pengguna_alamat' => '',
                'pengguna_no_telpon' => '',
                'pengguna_email' => 'required|string|max:255',
                'pengguna_username' => 'required|string|max:255',
                'pengguna_password' => 'required|string|max:255',
                'pengguna_tmpt_lahir' => '',
                'pengguna_tgl_lahir' => '',
                'pengguna_grup_id' => 'required|string|max:255',
                'pengguna_is_aktif' => 'required',
                'pengguna_pic' => '',
                'pengguna_who_create' => 'required|string|max:255',
                'pengguna_who_create_id' => 'required|string|max:255',
                'pengguna_date_create' => 'required|datetime',
                'pengguna_who_update' => 'required|string|max:255',
                'pengguna_who_update_id' => 'required|string|max:255',
                'pengguna_date_update' => 'required|datetime',
                'region_id' => '',
                'karyawan_id' => 'required|string|max:255',
                'pengguna_default_bahasa' => '',
                'pengguna_fingerprint' => '',
                'pengguna_aplikasi' => ''
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
        $Pengguna = Pengguna::find($id);

        if (!$Pengguna) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Pengguna->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPenggunaLogin($pengguna_username, $pengguna_password)
    {
        $data = DB::table('pengguna')
            ->where('pengguna_username', $pengguna_username)
            ->where('pengguna_password', $pengguna_password)
            ->get();

        if (!$data->isEmpty()) {
            return response()->json(['status' => '200', 'message' => 'Login successfully', 'data' => $data], 200);
        } else {
            return response()->json(['status' => '404', 'message' => 'Login failed, username and password is not correct', 'data' => $data], 200);
        }
    }
}
