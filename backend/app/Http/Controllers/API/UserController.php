<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan semua user.
     */
    public function index(): JsonResponse
    {
        $users = User::with(['role', 'department'])
            ->orderBy('full_name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar user berhasil diambil.',
            'data' => $users,
        ]);
    }

    /**
     * Menampilkan detail user.
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['role', 'department']);

        return response()->json([
            'success' => true,
            'message' => 'Detail user berhasil diambil.',
            'data' => $user,
        ]);
    }

    /**
     * Menambahkan user baru.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['password_hash'] = Hash::make($validated['password']);

        unset($validated['password']);

        $user = User::create($validated);

        $user->load(['role', 'department']);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan.',
            'data' => $user,
        ], 201);
    }

    /**
 * Mengubah data user.
 */
public function update(UpdateUserRequest $request, User $user): JsonResponse
{
    $user->update([
    'is_active' => true,
]);

$user->refresh()->load(['role', 'department']);

return response()->json([
    'success' => true,
    'message' => 'User berhasil diaktifkan kembali.',
    'data' => $user,
]);
}

    /**
 * Menonaktifkan user.
 */
public function destroy(User $user): JsonResponse
{
    // Pastikan relasi role sudah dimuat
    $user->load('role');

    // Admin tidak boleh menonaktifkan akun sendiri
    if ($user->id === auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak dapat menonaktifkan akun sendiri.',
        ], 403);
    }

    // User sudah nonaktif
    if (!$user->is_active) {
        return response()->json([
            'success' => false,
            'message' => 'User sudah dalam keadaan nonaktif.',
        ], 400);
    }

    // Cegah menonaktifkan admin terakhir yang masih aktif
    if (
        $user->role &&
        strtolower($user->role->name) === 'admin'
    ) {
        $activeAdmin = User::whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })
            ->where('is_active', true)
            ->count();

        if ($activeAdmin <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal harus ada satu admin yang tetap aktif.',
            ], 400);
        }
    }

    // Nonaktifkan user
    $user->update([
        'is_active' => false,
    ]);

    // Ambil ulang data beserta relasinya
    $user->refresh()->load(['role', 'department']);

    return response()->json([
        'success' => true,
        'message' => 'User berhasil dinonaktifkan.',
        'data' => $user,
    ]);
}

/**
 * Mengaktifkan kembali user.
 */
public function activate(User $user): JsonResponse
{
    if ($user->is_active) {
        return response()->json([
            'success' => false,
            'message' => 'User sudah aktif.',
        ], 400);
    }

    $user->update([
        'is_active' => true,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'User berhasil diaktifkan kembali.',
        'data' => $user,
    ]);
}
}