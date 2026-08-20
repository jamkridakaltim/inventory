<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequestItem\StoreAssetRequestItemRequest;
use App\Http\Requests\AssetRequestItem\UpdateAssetRequestItemRequest;
use App\Models\AssetRequest;
use App\Models\AssetRequestItem;
use Illuminate\Http\JsonResponse;

class AssetRequestItemController extends Controller
{
    /**
     * Menampilkan seluruh item pada suatu request.
     */
    public function index(AssetRequest $assetRequest): JsonResponse
    {
        $items = $assetRequest->items()->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar item berhasil diambil.',
            'total_requested_amount' => $items->sum('requested_amount'),
            'data' => $items,
        ]);
    }

    /**
     * Menambahkan item ke dalam draft request.
     */
    public function store(
        StoreAssetRequestItemRequest $request,
        AssetRequest $assetRequest
    ): JsonResponse {

        if ($assetRequest->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya draft yang dapat ditambahkan item.',
            ], 400);
        }

        $item = $assetRequest->items()->create([
            'item_name' => $request->item_name,
            'specification' => $request->specification,
            'quantity' => $request->quantity,
            'unit_name' => $request->unit_name,
            'requested_amount' => $request->requested_amount,
            'item_status' => 'pending',
        ]);

        $total = $assetRequest->items()->sum('requested_amount');

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan.',
            'total_requested_amount' => $total,
            'data' => $item,
        ], 201);
    }

    /**
     * Mengubah item.
     */
    public function update(
        UpdateAssetRequestItemRequest $request,
        AssetRequestItem $assetRequestItem
    ): JsonResponse {

        if ($assetRequestItem->assetRequest->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak dapat diubah.',
            ], 400);
        }

        $assetRequestItem->update([
            'item_name' => $request->item_name,
            'specification' => $request->specification,
            'quantity' => $request->quantity,
            'unit_name' => $request->unit_name,
            'requested_amount' => $request->requested_amount,
        ]);

        $total = $assetRequestItem
            ->assetRequest
            ->items()
            ->sum('requested_amount');

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diperbarui.',
            'total_requested_amount' => $total,
            'data' => $assetRequestItem,
        ]);
    }

    /**
     * Menghapus item.
     */
    public function destroy(
        AssetRequestItem $assetRequestItem
    ): JsonResponse {

        if ($assetRequestItem->assetRequest->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak dapat dihapus.',
            ], 400);
        }

        $request = $assetRequestItem->assetRequest;

        $assetRequestItem->delete();

        $total = $request->items()->sum('requested_amount');

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus.',
            'total_requested_amount' => $total,
        ]);
    }
}