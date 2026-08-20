<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_id',
        'request_item_id',
        'actual_quantity',
        'actual_amount',
        'received_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'actual_amount' => 'decimal:2',
            'received_at' => 'date',
        ];
    }

    /**
     * Pengadaan yang memiliki item ini.
     */
    public function procurement(): BelongsTo
    {
        return $this->belongsTo(
            Procurement::class,
            'procurement_id'
        );
    }

    /**
     * Item pengajuan yang direalisasikan.
     */
    public function requestItem(): BelongsTo
    {
        return $this->belongsTo(
            AssetRequestItem::class,
            'request_item_id'
        );
    }
}