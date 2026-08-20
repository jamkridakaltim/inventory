<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetPhotoController extends Controller
{
    /**
     * Menambahkan foto ke asset.
     */
    public function store(
        Request $request,
        Asset $asset
    ): JsonResponse {
        $request->validate([
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'photo_type' => [
                'required',
                'string',
                'in:asset,sticker,location,memo',
            ],
        ]);

        $path = $request->file('photo')->store(
            'assets/photos',
            'public'
        );

        $photo = AssetPhoto::create([
            'asset_id' => $asset->id,
            'photo_type' => $request->photo_type,
            'photo_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto aset berhasil ditambahkan.',
            'data' => [
                'id' => $photo->id,
                'asset_id' => $photo->asset_id,
                'photo_type' => $photo->photo_type,
                'photo_path' => $photo->photo_path,
                'photo_url' => Storage::url($photo->photo_path),
                'created_at' => $photo->created_at,
                'updated_at' => $photo->updated_at,
            ],
        ], 201);
    }

    /**
     * Menghapus foto asset.
     */
    public function destroy(
        AssetPhoto $assetPhoto
    ): JsonResponse {
        if ($assetPhoto->photo_path) {
            Storage::disk('public')->delete(
                $assetPhoto->photo_path
            );
        }

        $assetPhoto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto aset berhasil dihapus.',
        ]);
    }
}