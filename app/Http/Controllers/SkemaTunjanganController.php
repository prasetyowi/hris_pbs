<?php

namespace App\Http\Controllers;

use App\Models\SkemaTunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function Get_paginate_skema_tunjangan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skema_tunjangan_kode' => 'nullable|string',
            'skema_tunjangan_nama' => 'nullable|string',
            'skema_tunjangan_is_aktif' => 'nullable|string|max:255',
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

        $query = DB::table('skema_tunjangan')
            ->select([
                'skema_tunjangan_id',
                'perusahaan_id',
                'depo_id',
                'karyawan_divisi_id',
                'karyawan_level_id',
                'skema_tunjangan_kode',
                'skema_tunjangan_nama',
                'skema_tunjangan_keterangan',
                'skema_tunjangan_is_aktif',
                'skema_tunjangan_who_create',
                'skema_tunjangan_tgl_create',
                'skema_tunjangan_who_update',
                'skema_tunjangan_tgl_update',
                'kolom_baru',
            ]);

        $query->whereNotNull('skema_tunjangan_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('skema_tunjangan_nama', 'like', "%{$search}%")
                    ->orWhere('skema_tunjangan_kode', 'like', "%{$search}%")
                    ->orWhere('skema_tunjangan_keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('skema_tunjangan_is_aktif')) {
            $skema_tunjangan_is_aktif = $request->input('skema_tunjangan_is_aktif');
            $query->where(function ($q) use ($skema_tunjangan_is_aktif) {
                $q->whereRaw("ISNULL(skema_tunjangan.skema_tunjangan_is_aktif, 0) = ?", [$skema_tunjangan_is_aktif]);
            });
        }

        if ($request->filled('skema_tunjangan_kode')) {
            $skema_tunjangan_kode = $request->input('skema_tunjangan_kode');
            $query->where(function ($q) use ($skema_tunjangan_kode) {
                $q->whereRaw("ISNULL(skema_tunjangan.skema_tunjangan_kode, 0) = ?", [$skema_tunjangan_kode]);
            });
        }

        if ($request->filled('skema_tunjangan_nama')) {
            $skema_tunjangan_nama = $request->input('skema_tunjangan_nama');
            $query->where(function ($q) use ($skema_tunjangan_nama) {
                $q->whereRaw("ISNULL(skema_tunjangan.skema_tunjangan_nama, 0) = ?", [$skema_tunjangan_nama]);
            });
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('skema_tunjangan_nama');
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
