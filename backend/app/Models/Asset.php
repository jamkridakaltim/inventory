<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    protected $table = 'assets';

    protected $fillable = [
        'asset_code',
        'asset_request_id',
        'request_item_id',
        'asset_name',
        'category_id',
        'brand',
        'model',
        'serial_number',
        'location_id',
        'assigned_user_id',
        'assigned_user_name',
        'purchase_date',
        'purchase_proof_number',
        'purchase_proof',
        'acquisition_cost',
        'condition_status',
        'asset_status',
        'description',
    ];

    protected $appends = [
        'purchase_proof_url',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'acquisition_cost' => 'decimal:2',
        ];
    }

    public function getPurchaseProofUrlAttribute(): ?string
    {
        if (!$this->purchase_proof) {
            return null;
        }

        return Storage::url($this->purchase_proof);
    }

    public function requestItem(): BelongsTo
    {
        return $this->belongsTo(
            AssetRequestItem::class,
            'request_item_id'
        );
    }

    public function assetRequest(): BelongsTo
    {
        return $this->belongsTo(
            AssetRequest::class,
            'asset_request_id'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_user_id'
        );
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(
            AssetPhoto::class,
            'asset_id'
        );
    }
}