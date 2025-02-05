<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\KaryawanLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KaryawanLevelController extends Controller
{
    public function index()
    {
        $data = KaryawanLevel::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = KaryawanLevel::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'karyawan_level_id' => 'required|unique:karyawan_level,karyawan_level_id',
                'karyawan_divisi_id' => 'required|string|max:255',
                'karyawan_level_kode' => 'required|unique:karyawan_level,karyawan_level_kode|string|max:255',
                'karyawan_level_nama' => 'required|string|max:255',
                'karyawan_level_is_aktif' => '',
                'karyawan_level_is_deleted' => '',
            ]);

            $data = KaryawanLevel::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $level = KaryawanLevel::find($id);
        if (!$level) {
            return response()->json(['status' => '404', 'message' => 'Karyawan divisi not found'], 404);
        }

        try {
            $validated = $request->validate([
                'karyawan_level_kode' => 'required|string|max:255',
                'karyawan_divisi_id' => 'required|string|max:255',
                'karyawan_level_nama' => 'required|string|max:255',
                'karyawan_level_is_aktif' => '',
                'karyawan_level_is_deleted' => '',
            ]);

            $level->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $level], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $KaryawanLevel = KaryawanLevel::find($id);

        if (!$KaryawanLevel) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $KaryawanLevel->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKaryawanLevelAktif()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('karyawan_level')
                ->where('karyawan_level_is_aktif', 1)
                ->orderBy('karyawan_level_nama', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            }

            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function getKaryawanLevelDivisi($divisi)
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::table('karyawan_level')
                ->where('karyawan_divisi_id', $divisi)
                ->where('karyawan_level_is_aktif', 1)
                ->orderBy('karyawan_level_nama', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            }

            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function paginate_level(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_level_is_aktif' => 'nullable|string|max:255',
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

        $query = DB::table('karyawan_level')
                    ->select('karyawan_level.*', 'karyawan_divisi.karyawan_divisi_nama')
                    ->leftJoin('karyawan_divisi', 'karyawan_level.karyawan_divisi_id', '=', 'karyawan_divisi.karyawan_divisi_id');

        $query->whereNotNull('karyawan_level_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('karyawan_level_kode', 'like', "%{$search}%")
                    ->orWhere('karyawan_level_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('karyawan_level_is_aktif')) {
            $karyawan_level_is_aktif = $request->input('karyawan_level_is_aktif');
            $query->where(function ($q) use ($karyawan_level_is_aktif) {
                $q->whereRaw("ISNULL(karyawan_level_is_aktif, 0) = ?", [$karyawan_level_is_aktif]);
            });
        }


        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('karyawan_level_kode');
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
