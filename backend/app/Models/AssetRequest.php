<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
    'memo_number',
    'request_date',
    'requester_id',
    'department_id',
    'recipient_name',
    'sender_name',
    'subject',
    'notes',
    'status',
    'director_note',
    'submitted_at',
    'approved_at',
    'rejected_at',
];

    protected $casts = [
        'request_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * User yang mengajukan.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Departemen pemohon.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Daftar item yang diajukan.
     */
    public function items()
    {
        return $this->hasMany(AssetRequestItem::class, 'request_id');
    }

    public function assets(): HasMany
{
    return $this->hasMany(
        Asset::class,
        'asset_request_id'
    );
}

public function procurements(): HasMany
{
    return $this->hasMany(
        Procurement::class,
        'asset_request_id'
    );
}
}