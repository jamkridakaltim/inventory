<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Procurement\StoreProcurementRequest;
use App\Http\Requests\Procurement\UpdateProcurementRequest;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use App\Models\AssetRequest;
use App\Models\Procurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class ProcurementController extends Controller
{
    /**
     * Menampilkan seluruh pengadaan.
     */
    public function index(): JsonResponse
    {
        $procurements = Procurement::with([
            'assetRequest',
            'items.requestItem',
        ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengadaan berhasil diambil.',
            'data' => $procurements,
        ]);
    }

    /**
     * Membuat pengadaan dari Asset Request yang sudah approved.
     */
    public function store(StoreProcurementRequest $request): JsonResponse
    {
        $assetRequest = AssetRequest::findOrFail(
            $request->asset_request_id
        );

        if ($assetRequest->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan hanya dapat dibuat dari pengajuan yang sudah disetujui.',
            ], 400);
        }

        if ($assetRequest->procurements()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan ini sudah memiliki data pengadaan.',
            ], 400);
        }

        $procurement = Procurement::create([
            'procurement_number' => $this->generateProcurementNumber(),
            'asset_request_id' => $assetRequest->id,
            'procurement_date' => $request->procurement_date,
            'vendor_name' => $request->vendor_name,
            'status' => $request->status ?? 'draft',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengadaan berhasil dibuat.',
            'data' => $procurement->load('assetRequest'),
        ], 201);
    }

    /**
     * Menampilkan detail pengadaan.
     */
    public function show(Procurement $procurement): JsonResponse
    {
        $procurement->load([
            'assetRequest',
            'items.requestItem',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengadaan berhasil diambil.',
            'data' => $procurement,
        ]);
    }

    /**
     * Mengubah data pengadaan.
     */
    public function update(
        UpdateProcurementRequest $request,
        Procurement $procurement
    ): JsonResponse {
        if ($procurement->status === 'received') {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan yang sudah diterima tidak dapat diubah.',
            ], 400);
        }

        $procurement->update([
            'procurement_date' => $request->procurement_date,
            'vendor_name' => $request->vendor_name,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pengadaan berhasil diperbarui.',
            'data' => $procurement->fresh()->load('assetRequest'),
        ]);
    }

    /**
     * Menghapus pengadaan.
     */
    public function destroy(Procurement $procurement): JsonResponse
    {
        if ($procurement->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status draft yang dapat dihapus.',
            ], 400);
        }

        $procurement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengadaan berhasil dihapus.',
        ]);
    }

    /**
 * Menandai pengadaan sebagai sudah diterima.
 */
/**
 * Menandai pengadaan sebagai sudah diterima
 * dan membuat asset berdasarkan realisasi pengadaan.
 */
public function receive(Procurement $procurement): JsonResponse
{
    if ($procurement->status !== 'draft') {
        return response()->json([
            'success' => false,
            'message' => 'Pengadaan ini tidak dapat diproses lagi.',
        ], 400);
    }

    $procurement->load([
        'assetRequest',
        'items.requestItem',
    ]);

    if ($procurement->items->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Pengadaan belum memiliki item.',
        ], 400);
    }

    DB::beginTransaction();

    try {
        $createdAssets = [];

        foreach ($procurement->items as $procurementItem) {

            /*
             * Pastikan realisasi quantity sudah diisi.
             */
            if (
                is_null($procurementItem->actual_quantity) ||
                $procurementItem->actual_quantity <= 0
            ) {
                throw new \Exception(
                    'Semua item pengadaan harus memiliki actual quantity sebelum diterima.'
                );
            }

            /*
             * Pastikan realisasi harga sudah diisi.
             */
            if (
                is_null($procurementItem->actual_amount) ||
                $procurementItem->actual_amount < 0
            ) {
                throw new \Exception(
                    'Semua item pengadaan harus memiliki actual amount sebelum diterima.'
                );
            }

            /*
             * Pastikan item pengajuan tersedia.
             */
            $requestItem = $procurementItem->requestItem;

            if (!$requestItem) {
                throw new \Exception(
                    'Item pengajuan tidak ditemukan.'
                );
            }

            /*
             * Actual amount adalah total harga untuk seluruh quantity.
             *
             * Contoh:
             * actual_quantity = 2
             * actual_amount   = 28.000.000
             *
             * Harga per asset:
             * 28.000.000 / 2 = 14.000.000
             */
            $unitAmount = $procurementItem->actual_amount
                / $procurementItem->actual_quantity;

            /*
             * Membuat asset sesuai jumlah barang yang diterima.
             */
            for (
                $i = 1;
                $i <= $procurementItem->actual_quantity;
                $i++
            ) {

                $asset = Asset::create([
                    'asset_code' => $this->generateAssetCode(),

                    'asset_request_id' => $procurement->asset_request_id,

                    'request_item_id' => $procurementItem->request_item_id,

                    'asset_name' => $requestItem->item_name,

                    'brand' => null,

                    'model' => null,

                    'serial_number' => null,

                    'location_id' => null,

                    'assigned_user_id' => null,

                    'purchase_date' => $procurement->procurement_date,

                    'acquisition_cost' => $unitAmount,

                    'condition_status' => 'good',

                    'asset_status' => 'active',

                    'description' => $requestItem->specification,
                ]);

                $createdAssets[] = $asset;
            }
        }

        /*
 * Tandai item pengajuan sebagai sudah diproses
 * setelah asset berhasil dibuat.
 */
$requestItem->update([
    'item_status' => 'procured',
]);

        /*
         * Pastikan minimal ada asset yang dibuat.
         */
        if (empty($createdAssets)) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada asset yang berhasil dibuat.',
            ], 400);
        }

        /*
         * Setelah seluruh item berhasil direalisasikan,
         * procurement menjadi received.
         */
        $procurement->update([
            'status' => 'received',
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pengadaan berhasil diterima dan asset berhasil dibuat.',
            'data' => [
                'procurement' => $procurement
                    ->fresh()
                    ->load([
                        'assetRequest',
                        'items.requestItem',
                    ]),

                'assets' => $createdAssets,
            ],
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Pengadaan gagal diproses.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

/**
 * Membuat kode asset otomatis.
 */
private function generateAssetCode(): string
{
    $prefix = 'AST-' . now()->format('Ymd') . '-';

    $lastAsset = Asset::where(
        'asset_code',
        'like',
        $prefix . '%'
    )
        ->latest('id')
        ->first();

    $number = 1;

    if ($lastAsset) {
        $lastNumber = (int) Str::afterLast(
            $lastAsset->asset_code,
            '-'
        );

        $number = $lastNumber + 1;
    }

    return $prefix . str_pad(
        $number,
        4,
        '0',
        STR_PAD_LEFT
    );
}

    /**
     * Membuat nomor pengadaan otomatis.
     */
    private function generateProcurementNumber(): string
    {
        $prefix = 'PROC-' . now()->format('Ymd') . '-';

        $lastProcurement = Procurement::where(
            'procurement_number',
            'like',
            $prefix . '%'
        )
            ->latest('id')
            ->first();

        $number = 1;

        if ($lastProcurement) {
            $lastNumber = (int) Str::afterLast(
                $lastProcurement->procurement_number,
                '-'
            );

            $number = $lastNumber + 1;
        }

        return $prefix . str_pad(
            $number,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}