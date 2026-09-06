<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Menampilkan seluruh kategori.
     */
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('category_name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data' => $categories,
        ]);
    }

    /**
     * Menampilkan detail kategori.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail kategori berhasil diambil.',
            'data' => $category,
        ]);
    }
}