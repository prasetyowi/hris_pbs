<?php

namespace App\Http\Controllers;

use App\Models\KaryawanDivisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

use function PHPUnit\Framework\isEmpty;

class KaryawanDivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = KaryawanDivisi::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'karyawan_divisi_kode' => 'required|unique:karyawan_divisi,karyawan_divisi_kode|string|max:255',
                'karyawan_divisi_nama' => 'required|string|max:255',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = KaryawanDivisi::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $data = KaryawanDivisi::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $divisi = KaryawanDivisi::find($id);
        if (!$divisi) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'karyawan_divisi_kode' => 'required|string|max:255',
                'karyawan_divisi_nama' => 'required|string|max:255',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $divisi->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $divisi], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $KaryawanDivisi = KaryawanDivisi::find($id);

        if (!$KaryawanDivisi) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $KaryawanDivisi->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKaryawanDivisiAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('karyawan_divisi')
                ->where('karyawan_divisi_is_aktif', 1)
                ->orderBy('karyawan_divisi_nama', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            }

            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_karyawan_divisi_by_perusahaan_id($perusahaan_id)
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::select("SELECT
									karyawan_divisi_id,
									karyawan_divisi_kode,
									karyawan_divisi_nama,
									CASE
										WHEN karyawan_divisi_level = 0 THEN client_wms_id
										ELSE karyawan_divisi_reff_id
									END AS karyawan_divisi_reff_id,
									karyawan_divisi_level,
									karyawan_divisi_is_aktif,
									karyawan_divisi_is_deleted,
									client_wms_id
									FROM karyawan_divisi
									WHERE karyawan_divisi_is_aktif = '1'
									AND karyawan_divisi_is_deleted = '0'
									AND client_wms_id = '$perusahaan_id'");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }
}
