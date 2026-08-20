<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login User
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $login = $request->input('login');
            $password = $request->input('password');

            // Cari user berdasarkan username atau email
            $user = User::where('username', $login)
                ->orWhere('email', $login)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau email tidak ditemukan.'
                ], 404);
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak aktif.'
                ], 403);
            }

            if (!Hash::check($password, $user->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah.'
                ], 401);
            }

            // Update last login
            $user->last_login_at = now();
            $user->save();

            // Hapus token lama
            $user->tokens()->delete();

            // Buat token baru
            $token = $user->createToken('SIMAS')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'department_id' => $user->department_id,
                    'photo_url' => $user->photo_url,
                    'is_active' => $user->is_active,
                    'last_login_at' => $user->last_login_at,
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);

        }
    }

    /**
     * Logout User
     */
    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.'
        ]);
    }
}