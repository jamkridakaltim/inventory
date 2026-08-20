<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcurementItem\StoreProcurementItemRequest;
use App\Http\Requests\ProcurementItem\UpdateProcurementItemRequest;
use App\Models\Procurement;
use App\Models\ProcurementItem;
use Illuminate\Http\JsonResponse;

class ProcurementItemController extends Controller
{
    /**
     * Menampilkan seluruh item pengadaan.
     */
    public function index(Procurement $procurement): JsonResponse
    {
        $items = $procurement->items()
            ->with('requestItem')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar item pengadaan berhasil diambil.',
            'data' => $items,
        ]);
    }

    /**
     * Menambahkan item realisasi ke pengadaan.
     */
    public function store(
        StoreProcurementItemRequest $request,
        Procurement $procurement
    ): JsonResponse {
        if ($procurement->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan yang dibatalkan tidak dapat ditambahkan item.',
            ], 400);
        }

        $requestItem = $procurement->assetRequest
            ->items()
            ->find($request->request_item_id);

        if (!$requestItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak berasal dari pengajuan yang terkait dengan pengadaan ini.',
            ], 400);
        }

        if (
            $procurement->items()
                ->where('request_item_id', $requestItem->id)
                ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Item pengajuan ini sudah ditambahkan ke pengadaan.',
            ], 400);
        }

        $item = $procurement->items()->create([
            'request_item_id' => $requestItem->id,
            'actual_quantity' => $request->actual_quantity,
            'actual_amount' => $request->actual_amount,
            'received_at' => $request->received_at,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item pengadaan berhasil ditambahkan.',
            'data' => $item->load('requestItem'),
        ], 201);
    }

    /**
     * Mengubah item realisasi.
     */
    public function update(
        UpdateProcurementItemRequest $request,
        ProcurementItem $procurementItem
    ): JsonResponse {
        if ($procurementItem->procurement->status === 'received') {
            return response()->json([
                'success' => false,
                'message' => 'Item pengadaan yang sudah diterima tidak dapat diubah.',
            ], 400);
        }

        $procurementItem->update([
            'actual_quantity' => $request->actual_quantity,
            'actual_amount' => $request->actual_amount,
            'received_at' => $request->received_at,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item pengadaan berhasil diperbarui.',
            'data' => $procurementItem->fresh()->load('requestItem'),
        ]);
    }

    /**
     * Menghapus item realisasi.
     */
    public function destroy(
        ProcurementItem $procurementItem
    ): JsonResponse {
        if ($procurementItem->procurement->status === 'received') {
            return response()->json([
                'success' => false,
                'message' => 'Item pengadaan yang sudah diterima tidak dapat dihapus.',
            ], 400);
        }

        $procurementItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item pengadaan berhasil dihapus.',
        ]);
    }
}