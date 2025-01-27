<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\KaryawanDivisi;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;

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
                'karyawan_divisi_id' => 'required|unique:karyawan_divisi,karyawan_divisi_id',
                'karyawan_divisi_kode' => 'required|unique:karyawan_divisi,karyawan_divisi_kode|string|max:255',
                'karyawan_divisi_nama' => 'required|string|max:255',
                'karyawan_divisi_is_aktif' => '',
                'karyawan_divisi_is_deleted' => '',
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
                'karyawan_divisi_is_aktif' => '',
                'karyawan_divisi_is_deleted' => '',
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

    public function paginate_disivi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_divisi_is_aktif' => 'nullable|string|max:255',
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

        $query = DB::table('karyawan_divisi');

        $query->whereNotNull('karyawan_divisi_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('karyawan_divisi_kode', 'like', "%{$search}%")
                    ->orWhere('karyawan_divisi_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('karyawan_divisi_is_aktif')) {
            $karyawan_divisi_is_aktif = $request->input('karyawan_divisi_is_aktif');
            $query->where(function ($q) use ($karyawan_divisi_is_aktif) {
                $q->whereRaw("ISNULL(karyawan_divisi_is_aktif, 0) = ?", [$karyawan_divisi_is_aktif]);
            });
        }


        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('karyawan_divisi_kode');
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
