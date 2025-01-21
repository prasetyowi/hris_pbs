<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-connection', function () {
    $serverName = "LAPTOP-V14U56J4\\SQLEXPRESS"; // Pastikan menggunakan dua backslash
    $connectionInfo = ["Database" => "BACKEND"];

    // Coba koneksi SQL Server
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if ($conn) {
        return response()->json(['message' => 'Connection established!']);
    } else {
        return response()->json(['message' => 'Connection failed!', 'errors' => sqlsrv_errors()]);
    }
});

Route::get('/test-db', function () {
    try {
        // Coba query sederhana menggunakan query builder Laravel
        $result = DB::connection('sqlsrv')->select("SELECT 1 AS test");

        if ($result) {
            return response()->json(['message' => 'Connection successful!', 'result' => $result]);
        } else {
            return response()->json(['message' => 'Connection failed.']);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Connection failed!', 'error' => $e->getMessage()]);
    }
});

Route::get('/test-data', function () {
    $data = DB::table('karyawan')->get();
    return response()->json(['data' => $data]);
});
