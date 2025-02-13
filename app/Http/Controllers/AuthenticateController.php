<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba login
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Your credentials do not match our records.',
            ], 401);
        }

        // Ambil data user yang berhasil login
        $user = Auth::user();

        // Hapus token lama jika ingin hanya 1 token per user
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('PayrollToken')->plainTextToken;

        return (new UserResource($user))->additional([
            'token' => $token,
            'message' => 'Login successful!',
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Hapus hanya token yang sedang digunakan
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'You have been logged out.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong.',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
