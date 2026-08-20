<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil user yang sedang login.
     */
    public function show(): JsonResponse
    {
        $user = auth()->user()->load([
            'role',
            'department',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diambil.',
            'data' => $user,
        ]);
    }

    /**
     * Mengubah profil sendiri.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();

        $user->update($request->validated());

        $user->refresh()->load([
            'role',
            'department',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user,
        ]);
    }

    /**
     * Mengubah password.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = auth()->user();

        if (!Hash::check(
            $request->current_password,
            $user->password_hash
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai.',
            ], 400);
        }

        $user->update([
            'password_hash' => Hash::make(
                $request->new_password
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }
}