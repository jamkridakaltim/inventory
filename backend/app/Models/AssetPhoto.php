<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AssetPhoto extends Model
{
    protected $fillable = [
        'asset_id',
        'photo_type',
        'photo_path',
    ];

    protected $appends = [
        'photo_url',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(
            Asset::class,
            'asset_id'
        );
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        return Storage::url($this->photo_path);
    }
}