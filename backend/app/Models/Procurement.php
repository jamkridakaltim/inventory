<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_number',
        'asset_request_id',
        'procurement_date',
        'vendor_name',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'procurement_date' => 'date',
        ];
    }

    /**
     * Pengajuan aset yang terkait dengan pengadaan.
     */
    public function assetRequest(): BelongsTo
    {
        return $this->belongsTo(
            AssetRequest::class,
            'asset_request_id'
        );
    }

    /**
     * Daftar item dalam pengadaan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            ProcurementItem::class,
            'procurement_id'
        );
    }
}