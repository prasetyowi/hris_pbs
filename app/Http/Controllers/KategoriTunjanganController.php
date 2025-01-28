<?php

namespace App\Http\Controllers;

use App\Models\KategoriTunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function get_paginate_kategori_tunjangan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_tunjangan_nama' => 'nullable|string',
            'kategori_tunjangan_is_aktif' => 'nullable|string|max:255',
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

        $query = DB::table('kategori_tunjangan')
            ->select([
                'kategori_tunjangan_id',
                'perusahaan_id',
                'depo_id',
                'kategori_tunjangan_kode',
                'kategori_tunjangan_nama',
                'kategori_tunjangan_keterangan',
                'kategori_tunjangan_is_aktif',
                'kategori_tunjangan_who_create',
                'kategori_tunjangan_tgl_create',
                'kategori_tunjangan_who_update',
                'kategori_tunjangan_tgl_update',
            ]);

        $query->whereNotNull('kategori_tunjangan_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kategori_tunjangan_nama', 'like', "%{$search}%")
                    ->orWhere('kategori_tunjangan_kode', 'like', "%{$search}%")
                    ->orWhere('kategori_tunjangan_keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_tunjangan_is_aktif')) {
            $kategori_tunjangan_is_aktif = $request->input('kategori_tunjangan_is_aktif');
            $query->where(function ($q) use ($kategori_tunjangan_is_aktif) {
                $q->whereRaw("ISNULL(kategori_tunjangan.kategori_tunjangan_is_aktif, 0) = ?", [$kategori_tunjangan_is_aktif]);
            });
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('kategori_tunjangan_nama');
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
