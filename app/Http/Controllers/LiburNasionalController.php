<?php

namespace App\Http\Controllers;

use App\Models\LiburNasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class LiburNasionalController extends Controller
{
    public function index()
    {
        $data = LiburNasional::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = LiburNasional::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'libur_nasional_id' => 'required|unique:libur_nasional,libur_nasional_id|string|max:255',
                'libur_nasional_tahun' => 'required|numeric',
                'libur_nasional_tanggal' => 'required|date',
                'libur_nasional_nama' => 'required|string|max:255'
            ]);

            $data = LiburNasional::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = LiburNasional::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'libur_nasional_tahun' => 'required|numeric',
                'libur_nasional_tanggal' => 'required|date',
                'libur_nasional_nama' => 'required|string|max:255'
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $LiburNasional = LiburNasional::find($id);

        if (!$LiburNasional) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $LiburNasional->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getLiburNasionalAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('libur_nasional')
                ->where('libur_nasional_is_aktif', 1)
                ->orderBy('libur_nasional_tanggal', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            }

            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_libur_nasional(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libur_nasional_tahun' => 'nullable|integer',
            'libur_nasional_nama' => 'nullable|string',
            'libur_nasional_is_aktif' => 'nullable|string|max:255',
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

        $query = DB::table('libur_nasional')
            ->select([
                'libur_nasional_id',
                'libur_nasional_tahun',
                'libur_nasional_tanggal',
                'libur_nasional_nama',
                'libur_nasional_is_aktif',
                'libur_nasional_who_create',
                'libur_nasional_tgl_create',
                'libur_nasional_who_update',
                'libur_nasional_tgl_update',
                'is_active',
            ]);

        $query->whereNotNull('libur_nasional_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('libur_nasional_nama', 'like', "%{$search}%")
                    ->orWhere('libur_nasional_tanggal', 'like', "%{$search}%")
                    ->orWhere('libur_nasional_tahun', 'like', "%{$search}%");
            });
        }

        if ($request->filled('libur_nasional_tahun')) {
            $libur_nasional_tahun = $request->input('libur_nasional_tahun');
            $query->where('libur_nasional_tahun', '=', $libur_nasional_tahun);
        }

        if ($request->filled('libur_nasional_is_aktif')) {
            $libur_nasional_is_aktif = $request->input('libur_nasional_is_aktif');
            $query->where(function ($q) use ($libur_nasional_is_aktif) {
                $q->whereRaw("ISNULL(libur_nasional.libur_nasional_is_aktif, 0) = ?", [$libur_nasional_is_aktif]);
            });
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('libur_nasional_tahun');
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

    public function Get_paginate_libur_nasional_index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libur_nasional_tahun' => 'nullable|integer',
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

        $query = DB::table('libur_nasional')
            ->select([
                'libur_nasional_tahun',
            ]);

        $query->whereNotNull('libur_nasional_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('libur_nasional_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('libur_nasional_tahun')) {
            $libur_nasional_tahun = $request->input('libur_nasional_tahun');
            $query->where('libur_nasional_tahun', '=', $libur_nasional_tahun);
        }

        $query->groupBy('libur_nasional_tahun');

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('libur_nasional_tahun');
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
