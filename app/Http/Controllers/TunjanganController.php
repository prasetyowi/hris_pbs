<?php

namespace App\Http\Controllers;

use App\Models\Tunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function Get_paginate_tunjangan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tunjangan_kode' => 'nullable|string',
            'tunjangan_nama' => 'nullable|string',
            'tunjangan_is_aktif' => 'nullable|string|max:255',
            'kategori_tunjangan_id' => 'nullable|integer',
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

        $query = DB::table('tunjangan')
            ->select([
                'tunjangan_id',
                'perusahaan_id',
                'depo_id',
                'kategori_tunjangan_id',
                'tunjangan_kode',
                'tunjangan_nama',
                'tunjangan_keterangan',
                'tunjangan_jenistunjangan',
                'tunjangan_dasarbayar',
                'tunjangan_dibayar_oleh',
                'tunjangan_dibayar_kepada',
                'tunjangan_print_slip',
                'tunjangan_nama_print',
                'tunjangan_is_aktif',
                'tunjangan_who_create',
                'tunjangan_tgl_create',
                'tunjangan_who_update',
                'tunjangan_tgl_update',
                'tunjangan_flag_pph',
                'tunjangan_khusus',
                'kolom_baru',
            ]);

        $query->whereNotNull('tunjangan_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tunjangan_nama', 'like', "%{$search}%")
                    ->orWhere('tunjangan_kode', 'like', "%{$search}%")
                    ->orWhere('tunjangan_keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tunjangan_kode')) {
            $tunjangan_kode = $request->input('tunjangan_kode');
            $query->where('tunjangan_kode', '=', $tunjangan_kode);
        }

        if ($request->filled('tunjangan_nama')) {
            $tunjangan_nama = $request->input('tunjangan_nama');
            $query->where('tunjangan_nama', '=', $tunjangan_nama);
        }

        if ($request->filled('kategori_tunjangan_id')) {
            $kategori_tunjangan_id = $request->input('kategori_tunjangan_id');
            $query->where('kategori_tunjangan_id', '=', $kategori_tunjangan_id);
        }

        if ($request->filled('tunjangan_is_aktif')) {
            $tunjangan_is_aktif = $request->input('tunjangan_is_aktif');
            $query->where(function ($q) use ($tunjangan_is_aktif) {
                $q->whereRaw("ISNULL(tunjangan.tunjangan_is_aktif, 0) = ?", [$tunjangan_is_aktif]);
            });
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('tunjangan_nama');
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
