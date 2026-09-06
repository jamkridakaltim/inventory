<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\AssetPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Menampilkan seluruh aset.
     */
    public function index(): JsonResponse
    {
        $assets = Asset::with([
    'assetRequest',
    'requestItem',
    'category',
    'location',
    'assignedUser',
    'photos',
])
->latest()
->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar aset berhasil diambil.',
            'data' => $assets,
        ]);
    }


    /**
     * Menampilkan detail aset.
     */
    public function show(Asset $asset): JsonResponse
    {
        $asset->load([
    'assetRequest',
    'requestItem',
    'category',
    'location',
    'assignedUser',
    'photos',
]);

        return response()->json([
            'success' => true,
            'message' => 'Detail aset berhasil diambil.',
            'data' => $asset,
        ]);
    }


    /**
     * Menambahkan aset baru.
     */
    public function store(StoreAssetRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {

            /*
             * Membuat data asset.
             */
            $asset = Asset::create([
                'asset_code' => $this->generateAssetCode(),

                'asset_request_id' => $request->asset_request_id,

                'request_item_id' => $request->request_item_id,

                'asset_name' => $request->asset_name,

                'category_id' => $request->category_id,

                'brand' => $request->brand,

                'model' => $request->model,

                'serial_number' => $request->serial_number,

                'location_id' => $request->location_id,

'assigned_user_id' => $request->assigned_user_id,

'purchase_date' => $request->purchase_date,

                'purchase_proof_number' => $request->purchase_proof_number,

                'purchase_proof' => null,

                'acquisition_cost' => $request->acquisition_cost,

                'condition_status' => $request->condition_status,

                'asset_status' => 'active',

                'description' => $request->description,
            ]);


            /*
             * Upload bukti pembelian.
             */
            if ($request->hasFile('purchase_proof')) {

                $path = $request->file('purchase_proof')
                    ->store('assets/purchase-proofs', 'public');

                $asset->update([
                    'purchase_proof' => $path,
                ]);
            }


            /*
             * Upload foto asset.
             */
            $photoFields = [
                'photo_asset' => 'asset',
                'photo_sticker' => 'sticker',
                'photo_location' => 'location',
                'photo_memo' => 'memo',
            ];


            foreach ($photoFields as $field => $photoType) {

                if ($request->hasFile($field)) {

                    $path = $request->file($field)
                        ->store('assets/photos', 'public');

                    AssetPhoto::create([
                        'asset_id' => $asset->id,
                        'photo_type' => $photoType,
                        'photo_path' => $path,
                    ]);
                }
            }


            DB::commit();


            /*
             * Load relasi untuk response.
             */
            $asset->refresh()->load([
                'assetRequest',
                'requestItem',
                'category',
                'location',
                'assignedUser',
                'photos',
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Aset berhasil ditambahkan.',
                'data' => $asset,
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Aset gagal ditambahkan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(
    UpdateAssetRequest $request,
    Asset $asset
): JsonResponse
{
    DB::beginTransaction();

    try {

        /*
         * Update data utama asset.
         */
        $data = $request->validated();

        // Jangan masukkan file ke update assets
        unset(
            $data['purchase_proof'],
            $data['photo_asset'],
            $data['photo_sticker'],
            $data['photo_location'],
            $data['photo_memo']
        );

        $asset->update($data);


        /*
         * Update bukti pembelian jika ada file baru.
         */
        if ($request->hasFile('purchase_proof')) {

            // Hapus file lama
            if ($asset->purchase_proof) {
                Storage::disk('public')
                    ->delete($asset->purchase_proof);
            }

            // Simpan file baru
            $path = $request->file('purchase_proof')
                ->store('assets/purchase-proofs', 'public');

            $asset->update([
                'purchase_proof' => $path,
            ]);
        }


        /*
         * Update foto asset.
         */
        $photoFields = [
            'photo_asset' => 'asset',
            'photo_sticker' => 'sticker',
            'photo_location' => 'location',
            'photo_memo' => 'memo',
        ];


        foreach ($photoFields as $field => $photoType) {

            // Hanya proses jika ada file baru
            if ($request->hasFile($field)) {

                $photo = AssetPhoto::where('asset_id', $asset->id)
                    ->where('photo_type', $photoType)
                    ->first();


                // Hapus foto lama
                if ($photo && $photo->photo_path) {
                    Storage::disk('public')
                        ->delete($photo->photo_path);
                }


                // Simpan foto baru
                $path = $request->file($field)
                    ->store('assets/photos', 'public');


                if ($photo) {

                    // Update record yang sudah ada
                    $photo->update([
                        'photo_path' => $path,
                    ]);

                } else {

                    // Jika belum ada, buat record baru
                    AssetPhoto::create([
                        'asset_id' => $asset->id,
                        'photo_type' => $photoType,
                        'photo_path' => $path,
                    ]);
                }
            }
        }


        DB::commit();


        /*
         * Refresh dan load relasi.
         */
        $asset->refresh()->load([
            'assetRequest',
            'requestItem',
            'category',
            'location',
            'assignedUser',
            'photos',
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil diperbarui.',
            'data' => $asset,
        ]);


    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Aset gagal diperbarui.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Menghapus aset.
     */
    public function destroy(Asset $asset): JsonResponse
    {
        DB::beginTransaction();

        try {

            /*
             * Hapus bukti pembelian.
             */
            if ($asset->purchase_proof) {

                Storage::disk('public')
                    ->delete($asset->purchase_proof);
            }


            /*
             * Hapus seluruh foto asset.
             */
            foreach ($asset->photos as $photo) {

                Storage::disk('public')
                    ->delete($photo->photo_path);

                $photo->delete();
            }


            /*
             * Hapus data asset.
             */
            $asset->delete();


            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Aset berhasil dihapus.',
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Aset gagal dihapus.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Membuat kode asset otomatis.
     *
     * Contoh:
     * AST-20260812-0001
     */
    private function generateAssetCode(): string
    {
        $today = now()->format('Ymd');

        $prefix = 'AST-' . $today . '-';


        $lastAsset = Asset::where(
            'asset_code',
            'like',
            $prefix . '%'
        )
            ->latest('id')
            ->first();


        $sequence = 1;


        if ($lastAsset) {

            $lastSequence = (int) substr(
                $lastAsset->asset_code,
                -4
            );

            $sequence = $lastSequence + 1;
        }


        return sprintf(
            'AST-%s-%04d',
            $today,
            $sequence
        );
    }
}