<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'karyawan_id' => 'required|unique:karyawan,karyawan_id|string|max:255',
                'perusahaan_id' => '',
                'unit_mandiri_id' => '',
                'depo_id' => '',
                'karyawan_nama' => 'required|string|max:255',
                'karyawan_telepon' => 'required|string|max:255',
                'karyawan_email' => 'required|email|unique:karyawan,karyawan_email',
                'karyawan_tanggal_lahir' => '',
                'karyawan_divisi_id' => 'required|string|max:255',
                'karyawan_level_id' => 'required|string|max:255',
                'karyawan_supervisor_id' => '',
                'karyawan_is_perusahaan' => '',
                'karyawan_foto' => '',
                'karyawan_digital_signature' => '',
                'karyawan_is_deleted' => '',
                'karyawan_is_aktif' => 'required|numeric',
                'karyawan_is_dewa' => '',
                'karyawan_quote' => '',
                'karyawan_nip' => 'required|unique:karyawan,karyawan_nip|string|max:255',
                'karyawan_nik' => 'required|unique:karyawan,karyawan_nik|string|max:255',
                'karyawan_tempat_lahir' => '',
                'karyawan_jenis_kelamin' => '',
                'karyawan_agama' => '',
                'karyawan_basic_salary' => 'required|numeric',
                'karyawan_basic_bpjs' => 'required|numeric',
                'karyawan_bank' => '',
                'karyawan_no_rek' => '',
                'karyawan_nama_rek' => '',
                'karyawan_npwp15' => '',
                'karyawan_npwp16' => '',
                'kategori_ptkp_id' => '',
                'tarif_efektif_id' => '',
                'karyawan_beginning_netto' => '',
                'karyawan_pph21paid' => '',
                'kategori_karyawan_kode' => '',
                'karyawan_status_kewajiban' => '',
                'karyawan_jml_tanggungan' => '',
                'karyawan_jml_extra_tanggungan_for_bpjskes' => '',
                'karyawan_tgl_resign' => '',
                'karyawan_is_resign' => 'required|numeric',
                'karyawan_tgl_aktif' => '',
                'karyawan_metodetax' => '',
                'karyawan_jenispajak' => '',
                'karyawan_header_id' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ], [
                'karyawan_is_dewa.default' => '0',
                'karyawan_is_deleted.default' => '0',
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
                'perusahaan_id' => '',
                'unit_mandiri_id' => '',
                'depo_id' => '',
                'karyawan_nama' => 'required|string|max:255',
                'karyawan_telepon' => 'required|string|max:255',
                'karyawan_email' => 'required|email',
                'karyawan_tanggal_lahir' => '',
                'karyawan_divisi_id' => 'required|string|max:255',
                'karyawan_level_id' => 'required|string|max:255',
                'karyawan_supervisor_id' => '',
                'karyawan_is_perusahaan' => '',
                'karyawan_foto' => '',
                'karyawan_digital_signature' => '',
                'karyawan_is_deleted' => '',
                'karyawan_is_aktif' => 'required|numeric',
                'karyawan_is_dewa' => '',
                'karyawan_quote' => '',
                'karyawan_nip' => 'required|string|max:255',
                'karyawan_nik' => 'required|string|max:255',
                'karyawan_tempat_lahir' => '',
                'karyawan_jenis_kelamin' => '',
                'karyawan_agama' => '',
                'karyawan_basic_salary' => 'required|numeric',
                'karyawan_basic_bpjs' => 'required|numeric',
                'karyawan_bank' => '',
                'karyawan_no_rek' => '',
                'karyawan_nama_rek' => '',
                'karyawan_npwp15' => '',
                'karyawan_npwp16' => '',
                'kategori_ptkp_id' => '',
                'tarif_efektif_id' => '',
                'karyawan_beginning_netto' => '',
                'karyawan_pph21paid' => '',
                'kategori_karyawan_kode' => '',
                'karyawan_status_kewajiban' => '',
                'karyawan_jml_tanggungan' => '',
                'karyawan_jml_extra_tanggungan_for_bpjskes' => '',
                'karyawan_tgl_resign' => '',
                'karyawan_is_resign' => 'required|numeric',
                'karyawan_tgl_aktif' => '',
                'karyawan_metodetax' => '',
                'karyawan_jenispajak' => '',
                'karyawan_header_id' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ], [
                'karyawan_is_dewa.default' => '0',
                'karyawan_is_deleted.default' => '0',
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
