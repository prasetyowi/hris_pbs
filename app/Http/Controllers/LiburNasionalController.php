<?php

namespace App\Http\Controllers;

use App\Models\LiburNasional;
use Carbon\Carbon;
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

    public function show($year)
    {
        $data = LiburNasional::where('libur_nasional_tahun', $year)->get();
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => ['year' => $year, 'holidays' => $data]], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->holidays) {
                foreach ($request->holidays as $holiday) {
                    $validator = Validator::make($holiday, [
                        'libur_nasional_id' => 'required|unique:libur_nasional,libur_nasional_id|string|max:255',
                        'libur_nasional_tahun' => 'required|numeric',
                        'libur_nasional_tanggal' => 'required|date',
                        'libur_nasional_nama' => 'required|string|max:255'
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 422);
                    }

                    $cekExistDate = LiburNasional::where('libur_nasional_tanggal', $holiday['libur_nasional_tanggal'])
                        ->where('libur_nasional_tahun', $holiday['libur_nasional_tahun'])->first();
                    if ($cekExistDate) {
                        return response()->json(['status' => '400', 'message' =>  "data " . Carbon::parse($holiday['libur_nasional_tanggal'])->tz(config('app.timezone'))->format('d F Y') . " already exist"], 400);
                    }

                    LiburNasional::create([
                        'libur_nasional_id' => $holiday['libur_nasional_id'],
                        'libur_nasional_tahun' => $holiday['libur_nasional_tahun'],
                        'libur_nasional_tanggal' => $holiday['libur_nasional_tanggal'],
                        'libur_nasional_nama' => $holiday['libur_nasional_nama'],
                        'libur_nasional_is_aktif' => 1,
                        'libur_nasional_who_create' => $request->user,
                        'libur_nasional_tgl_create' => Carbon::now()
                    ]);
                }
            }

            DB::commit();
            return response()->json(['status' => '200', 'message' => 'Data created successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $year)
    {
        $checkYear = LiburNasional::where('libur_nasional_tahun', $year)->get();
        if (!$checkYear) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        DB::beginTransaction();
        try {
            if ($request->holidays) {
                $arrayLiburNasionalId = [];
                foreach ($request->holidays as $holiday) {
                    $arrayLiburNasionalId[] = $holiday['libur_nasional_id'];
                    $fieldValidator = [
                        'libur_nasional_tahun' => 'required|numeric',
                        'libur_nasional_tanggal' => 'required|date',
                        'libur_nasional_nama' => 'required|string|max:255'
                    ];

                    if ($holiday['is_new'] == 1) {
                        $fieldValidator['libur_nasional_id'] = 'required|unique:libur_nasional,libur_nasional_id|string|max:255';
                    }

                    $validator = Validator::make($holiday, $fieldValidator);

                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 422);
                    }

                    if($holiday['is_new'] == 1){
                        $cekExistDate = LiburNasional::where('libur_nasional_tanggal', $holiday['libur_nasional_tanggal'])
                            ->where('libur_nasional_tahun', $holiday['libur_nasional_tahun'])->first();
                        if ($cekExistDate) {
                            return response()->json(['status' => '400', 'message' =>  "data " . Carbon::parse($holiday['libur_nasional_tanggal'])->tz(config('app.timezone'))->format('d F Y') . " already exist"], 400);
                        }

                        LiburNasional::create([
                            'libur_nasional_id' => $holiday['libur_nasional_id'],
                            'libur_nasional_tahun' => $holiday['libur_nasional_tahun'],
                            'libur_nasional_tanggal' => $holiday['libur_nasional_tanggal'],
                            'libur_nasional_nama' => $holiday['libur_nasional_nama'],
                            'libur_nasional_is_aktif' => 1,
                            'libur_nasional_who_create' => $request->user,
                            'libur_nasional_tgl_create' => Carbon::now()
                        ]);
                    } else {
                        LiburNasional::where('libur_nasional_id', $holiday['libur_nasional_id'])->update([
                            'libur_nasional_tanggal' => $holiday['libur_nasional_tanggal'],
                            'libur_nasional_nama' => $holiday['libur_nasional_nama'],
                            'libur_nasional_who_update' => $request->user,
                            'libur_nasional_tgl_update' => Carbon::now()
                        ]);
                    }
                }

                LiburNasional::where('libur_nasional_tahun', $year)->whereNotIn('libur_nasional_id', $arrayLiburNasionalId)->delete();
            }

            DB::commit();
            return response()->json(['status' => '200', 'message' => 'Data updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => '500', 'message' => 'Data updated failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($year)
    {
        $liburNasional = LiburNasional::where('libur_nasional_tahun', $year)->pluck('libur_nasional_id');

        if (!$liburNasional) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            LiburNasional::whereIn('libur_nasional_id', $liburNasional)->delete();

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

        $total = count($query->get());
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
