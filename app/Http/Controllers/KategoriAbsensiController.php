<?php

namespace App\Http\Controllers;

use App\Models\KategoriAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class KategoriAbsensiController extends Controller
{
    public function index()
    {
        $data = KategoriAbsensi::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = KategoriAbsensi::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'kategori_absensi_id' => 'required|unique:kategori_absensi,kategori_absensi_id|string|max:255',
                'kategori_absensi_kode' => 'required|unique:kategori_absensi,kategori_absensi_kode|string|max:255',
                'kategori_absensi_nama' => 'required|unique:kategori_absensi,kategori_absensi_nama|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'kategori_absensi_keterangan' => '',
                'kategori_absensi_is_unlimited_saldo' => '',
                'kategori_absensi_saldo_hari' => '',
                'kategori_absensi_maks_request' => '',
                'kategori_absensi_allow_exceed' => '',
                'kategori_absensi_max_exceed' => '',
                'kategori_absensi_is_cutitahunan' => '',
                'kategori_absensi_is_potongcutitahunan' => '',
                'kategori_absensi_is_alpha' => '',
                'kategori_absensi_is_hadir' => '',
                'kategori_absensi_is_aktif' => '',
                'kategori_absensi_who_create' => '',
                'kategori_absensi_tgl_create' => '',
                'kategori_absensi_who_update' => '',
                'kategori_absensi_tgl_update' => ''
            ]);

            $data = KategoriAbsensi::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = KategoriAbsensi::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'kategori_absensi_kode' => 'required|string|max:255',
                'kategori_absensi_nama' => 'required|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'kategori_absensi_kode' => '',
                'kategori_absensi_nama' => '',
                'kategori_absensi_keterangan' => '',
                'kategori_absensi_is_unlimited_saldo' => '',
                'kategori_absensi_saldo_hari' => '',
                'kategori_absensi_maks_request' => '',
                'kategori_absensi_allow_exceed' => '',
                'kategori_absensi_max_exceed' => '',
                'kategori_absensi_is_cutitahunan' => '',
                'kategori_absensi_is_potongcutitahunan' => '',
                'kategori_absensi_is_alpha' => '',
                'kategori_absensi_is_hadir' => '',
                'kategori_absensi_is_aktif' => '',
                'kategori_absensi_who_create' => '',
                'kategori_absensi_tgl_create' => '',
                'kategori_absensi_who_update' => '',
                'kategori_absensi_tgl_update' => ''
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $KategoriAbsensi = KategoriAbsensi::find($id);

        if (!$KategoriAbsensi) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $KategoriAbsensi->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKategoriAbsensiAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('kategori_absensi')
                ->where('kategori_absensi_is_aktif', 1)
                ->orderBy('kategori_absensi_nama', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            }

            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_kategori_absensi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_absensi_is_aktif' => 'nullable|string|max:255',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        $query = DB::table('kategori_absensi')
            ->select([
                'kategori_absensi_id',
                'perusahaan_id',
                'depo_id',
                'kategori_absensi_kode',
                'kategori_absensi_nama',
                'kategori_absensi_keterangan',
                'kategori_absensi_is_unlimited_saldo',
                'kategori_absensi_saldo_hari',
                'kategori_absensi_maks_request',
                'kategori_absensi_allow_exceed',
                'kategori_absensi_max_exceed',
                'kategori_absensi_is_cutitahunan',
                'kategori_absensi_is_potongcutitahunan',
                'kategori_absensi_is_alpha',
                'kategori_absensi_is_hadir',
                'kategori_absensi_is_aktif',
                'kategori_absensi_who_create',
                'kategori_absensi_tgl_create',
                'kategori_absensi_who_update',
                'kategori_absensi_tgl_update',
            ]);

        $query->whereNotNull('kategori_absensi_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kategori_absensi_nama', 'like', "%{$search}%")
                    ->orWhere('kategori_absensi_kode', 'like', "%{$search}%")
                    ->orWhere('kategori_absensi_keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_absensi_is_aktif')) {
            $kategori_absensi_is_aktif = $request->input('kategori_absensi_is_aktif');
            $query->where(function ($q) use ($kategori_absensi_is_aktif) {
                $q->whereRaw("ISNULL(kategori_absensi.kategori_absensi_is_aktif, 0) = ?", [$kategori_absensi_is_aktif]);
            });
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('kategori_absensi_nama');
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
