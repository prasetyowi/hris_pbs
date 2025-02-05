<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\KaryawanDetail;
use App\Models\KaryawanKeluarga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
                'karyawan_email' => 'required|unique:karyawan,karyawan_email',
                'karyawan_tanggal_lahir' => '',
                'karyawan_divisi_id' => 'required|string|max:255',
                'karyawan_level_id' => 'required|string|max:255',
                'karyawan_supervisor_id' => '',
                'karyawan_is_perusahaan' => '',
                'karyawan_foto' => '',
                'karyawan_digital_signature' => '',
                'karyawan_is_deleted' => '',
                'karyawan_is_aktif' => 'required',
                'karyawan_is_dewa' => '',
                'karyawan_quote' => '',
                'karyawan_nip' => 'required|unique:karyawan,karyawan_nip|string|max:255',
                'karyawan_nik' => 'required|unique:karyawan,karyawan_nik|string|max:255',
                'karyawan_tempat_lahir' => '',
                'karyawan_jenis_kelamin' => '',
                'karyawan_agama' => '',
                'karyawan_basic_salary' => 'required',
                'karyawan_basic_bpjs' => 'required',
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
                'karyawan_is_resign' => 'required',
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
                'karyawan_email' => 'required',
                'karyawan_tanggal_lahir' => '',
                'karyawan_divisi_id' => 'required|string|max:255',
                'karyawan_level_id' => 'required|string|max:255',
                'karyawan_supervisor_id' => '',
                'karyawan_is_perusahaan' => '',
                'karyawan_foto' => '',
                'karyawan_digital_signature' => '',
                'karyawan_is_deleted' => '',
                'karyawan_is_aktif' => 'required',
                'karyawan_is_dewa' => '',
                'karyawan_quote' => '',
                'karyawan_nip' => 'required|string|max:255',
                'karyawan_nik' => 'required|string|max:255',
                'karyawan_tempat_lahir' => '',
                'karyawan_jenis_kelamin' => '',
                'karyawan_agama' => '',
                'karyawan_basic_salary' => 'required',
                'karyawan_basic_bpjs' => 'required',
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
                'karyawan_is_resign' => 'required',
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

            KaryawanDetail::where('karyawan_id', $id)->delete();
            KaryawanKeluarga::where('karyawan_id', $id)->delete();

            $Karyawan->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_karyawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_is_aktif' => 'nullable|string|max:255',
            'karyawan_is_resign' => 'nullable|string|max:255',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
            'status' => 'nullable',
            'status_type' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        $query = DB::table('karyawan')
            ->leftJoin('karyawan_divisi as divisi', 'divisi.karyawan_divisi_id', '=', 'karyawan.karyawan_divisi_id')
            ->leftJoin('karyawan_level as level', 'level.karyawan_level_id', '=', 'karyawan.karyawan_level_id')
            ->select([
                DB::raw('ROW_NUMBER() OVER (ORDER BY karyawan_nip) AS RowNum'),
                'karyawan.karyawan_id',
                'karyawan.karyawan_nip',
                'karyawan.karyawan_nik',
                'karyawan.karyawan_nama',
                'karyawan.karyawan_divisi_id',
                DB::raw('ISNULL(divisi.karyawan_divisi_nama, \'\') as karyawan_divisi_nama'),
                'karyawan.karyawan_level_id',
                DB::raw('ISNULL(level.karyawan_level_nama, \'\') as karyawan_level_nama'),
                'karyawan.karyawan_is_aktif',
                'karyawan.karyawan_tgl_aktif',
                DB::raw('ISNULL(karyawan.karyawan_is_resign, \'\') as karyawan_is_resign'),
                DB::raw('ISNULL(karyawan.karyawan_tgl_resign, \'\') as karyawan_tgl_resign'),
                'karyawan.karyawan_foto'
            ]);

        $query->whereNotNull('karyawan.karyawan_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('karyawan.karyawan_nip', 'like', "%{$search}%")
                    ->orWhere('karyawan.karyawan_nik', 'like', "%{$search}%")
                    ->orWhere('karyawan.karyawan_nama', 'like', "%{$search}%")
                    ->orWhere('karyawan_divisi_nama', 'like', "%{$search}%")
                    ->orWhere('karyawan_level_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('karyawan_is_aktif')) {
            $karyawan_is_aktif = $request->input('karyawan_is_aktif');
            $query->where(function ($q) use ($karyawan_is_aktif) {
                $q->whereRaw("ISNULL(karyawan.karyawan_is_aktif, 0) = ?", [$karyawan_is_aktif]);
            });
        }

        if ($request->filled('karyawan_is_resign')) {
            $karyawan_is_resign = $request->input('karyawan_is_resign');
            $query->where(function ($q) use ($karyawan_is_resign) {
                $q->whereRaw("ISNULL(karyawan.karyawan_is_resign, 0) = ?", [$karyawan_is_resign]);
            });
        }


        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('karyawan_nip');
        }

        $total = $query->count();

        $perPage = $request->input('size', 10);
        $page = $request->input('page', 1);
        $orders = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'data' => $orders,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'size' => $perPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }
}
