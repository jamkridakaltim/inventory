<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AssetRequestController;
use App\Http\Controllers\API\AssetRequestItemController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\AssetPhotoController;
use App\Http\Controllers\API\ProcurementController;
use App\Http\Controllers\API\ProcurementItemController;


// =====================================================
// LOGIN
// Semua orang boleh login
// =====================================================

Route::post('/login', [AuthController::class, 'login']);


// =====================================================
// ROUTE YANG WAJIB LOGIN
// =====================================================

Route::middleware('auth:sanctum')->group(function () {


    // =================================================
    // AUTH
    // Semua role
    // =================================================

    Route::post('/logout', [AuthController::class, 'logout']);


    // =================================================
    // PROFILE
    // Semua role
    // =================================================

    Route::get(
        '/profile',
        [ProfileController::class, 'show']
    );

    Route::put(
        '/profile',
        [ProfileController::class, 'update']
    );

    Route::put(
        '/profile/change-password',
        [ProfileController::class, 'changePassword']
    );


    // =================================================
    // MASTER DATA - ROLES
    // Admin saja
    // =================================================

    Route::middleware('role:1')->group(function () {

        Route::apiResource(
            'roles',
            RoleController::class
        );

    });


    // =================================================
    // DEPARTMENTS
    // Semua role - hanya melihat
    // =================================================

    Route::middleware('role:1,2,3')->group(function () {

        Route::get(
            '/departments',
            [DepartmentController::class, 'index']
        );

        Route::get(
            '/departments/{department}',
            [DepartmentController::class, 'show']
        );

    });


    // =================================================
    // LOCATIONS
    // Semua role
    // =================================================

    Route::middleware('role:1,2,3')->group(function () {

        Route::get(
            '/locations',
            [LocationController::class, 'index']
        );

        Route::get(
            '/locations/{location}',
            [LocationController::class, 'show']
        );

    });


    // =================================================
    // USERS
    // Admin saja
    // =================================================

    Route::middleware('role:1')->group(function () {

        Route::apiResource(
            'users',
            UserController::class
        );

        Route::patch(
            '/users/{user}/activate',
            [UserController::class, 'activate']
        );

    });


    // =====================================================
    // ASSET REQUEST
    // =====================================================


    // -------------------------------------------------
    // Melihat pengajuan
    // Admin + Staff + Direktur
    // -------------------------------------------------

    Route::middleware('role:1,2,3')->group(function () {

        Route::get(
            '/asset-requests',
            [AssetRequestController::class, 'index']
        );

        Route::get(
            '/asset-requests/{assetRequest}',
            [AssetRequestController::class, 'show']
        );

    });


    // -------------------------------------------------
    // Membuat / mengubah / menghapus pengajuan
    // Admin + Staff
    // -------------------------------------------------

    Route::middleware('role:1,2')->group(function () {

        Route::post(
            '/asset-requests',
            [AssetRequestController::class, 'store']
        );

        Route::put(
            '/asset-requests/{assetRequest}',
            [AssetRequestController::class, 'update']
        );

        Route::delete(
            '/asset-requests/{assetRequest}',
            [AssetRequestController::class, 'destroy']
        );

        Route::patch(
            '/asset-requests/{assetRequest}/submit',
            [AssetRequestController::class, 'submit']
        );

    });


    // -------------------------------------------------
    // ITEM PENGAJUAN - MELIHAT
    // Admin + Staff + Direktur
    // -------------------------------------------------

    Route::middleware('role:1,2,3')->group(function () {

        Route::get(
            '/asset-requests/{assetRequest}/items',
            [AssetRequestItemController::class, 'index']
        );

    });


    // -------------------------------------------------
    // ITEM PENGAJUAN - TAMBAH / EDIT / HAPUS
    // Admin + Staff
    // -------------------------------------------------

    Route::middleware('role:1,2')->group(function () {

        Route::post(
            '/asset-requests/{assetRequest}/items',
            [AssetRequestItemController::class, 'store']
        );

        Route::put(
            '/asset-request-items/{assetRequestItem}',
            [AssetRequestItemController::class, 'update']
        );

        Route::delete(
            '/asset-request-items/{assetRequestItem}',
            [AssetRequestItemController::class, 'destroy']
        );

    });


    // -------------------------------------------------
    // APPROVAL
    // Direktur saja
    // -------------------------------------------------

    Route::middleware('role:3')->group(function () {

        Route::patch(
            '/asset-requests/{assetRequest}/approve',
            [AssetRequestController::class, 'approve']
        );

        Route::patch(
            '/asset-requests/{assetRequest}/reject',
            [AssetRequestController::class, 'reject']
        );

    });


    // -------------------------------------------------
    // PDF / MEMO
    // Admin + Direktur
    // -------------------------------------------------

    Route::middleware('role:1,3')->group(function () {

        Route::get(
            '/asset-requests/{assetRequest}/pdf',
            [AssetRequestController::class, 'pdf']
        );

    });


    // =====================================================
    // ASSET
    // =====================================================


    // -------------------------------------------------
    // Melihat daftar/detail aset
    // Admin + Direktur
    // -------------------------------------------------

    Route::middleware('role:1,3')->group(function () {

        Route::get(
            '/assets',
            [AssetController::class, 'index']
        );

        Route::get(
            '/assets/{asset}',
            [AssetController::class, 'show']
        );

    });


    // -------------------------------------------------
    // Mengelola aset
    // Admin saja
    // -------------------------------------------------

    Route::middleware('role:1')->group(function () {

        Route::post(
            '/assets',
            [AssetController::class, 'store']
        );

        Route::put(
            '/assets/{asset}',
            [AssetController::class, 'update']
        );

        Route::delete(
            '/assets/{asset}',
            [AssetController::class, 'destroy']
        );


        // Foto aset

        Route::post(
            '/assets/{asset}/photos',
            [AssetPhotoController::class, 'store']
        );

        Route::delete(
            '/asset-photos/{assetPhoto}',
            [AssetPhotoController::class, 'destroy']
        );

    });


    // =====================================================
    // PROCUREMENT / INPUT ASET
    // Admin saja
    // =====================================================

    Route::middleware('role:1')->group(function () {

        Route::apiResource(
            'procurements',
            ProcurementController::class
        );

        Route::patch(
            '/procurements/{procurement}/receive',
            [ProcurementController::class, 'receive']
        );


        // Procurement Items

        Route::get(
            '/procurements/{procurement}/items',
            [ProcurementItemController::class, 'index']
        );

        Route::post(
            '/procurements/{procurement}/items',
            [ProcurementItemController::class, 'store']
        );

        Route::put(
            '/procurement-items/{procurementItem}',
            [ProcurementItemController::class, 'update']
        );

        Route::delete(
            '/procurement-items/{procurementItem}',
            [ProcurementItemController::class, 'destroy']
        );

    });

});