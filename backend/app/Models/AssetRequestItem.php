<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'item_name',
        'specification',
        'quantity',
        'unit_name',
        'requested_amount',
        'actual_amount',
        'item_status',
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
    ];

    /**
     * Header pengajuan aset.
     */
    public function assetRequest()
    {
        return $this->belongsTo(AssetRequest::class, 'request_id');
    }

    public function assets(): HasMany
{
    return $this->hasMany(
        Asset::class,
        'request_item_id'
    );
}
}