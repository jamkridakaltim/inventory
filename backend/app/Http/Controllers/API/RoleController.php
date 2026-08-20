<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Menampilkan semua role.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Daftar role berhasil diambil.',
            'data' => Role::orderBy('id')->get(),
        ]);
    }

    /**
     * Menampilkan detail role.
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail role berhasil diambil.',
            'data' => $role,
        ]);
    }

    /**
     * Menyimpan role baru.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan.',
            'data' => $role,
        ], 201);
    }

    /**
     * Mengubah role.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui.',
            'data' => $role,
        ]);
    }

    /**
     * Menghapus role.
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus.',
        ]);
    }
}