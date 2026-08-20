<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    /**
     * Menampilkan semua department.
     */
    public function index(): JsonResponse
    {
        $departments = Department::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar department berhasil diambil.',
            'data' => $departments,
        ]);
    }

    /**
     * Menampilkan detail department.
     */
    public function show(Department $department): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail department berhasil diambil.',
            'data' => $department,
        ]);
    }
}