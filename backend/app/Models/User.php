<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'department_id',
        'full_name',
        'username',
        'email',
        'password_hash',
        'photo_url',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * User memiliki satu Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User berasal dari satu Department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * User membuat banyak Asset Request.
     */
    public function assetRequests(): HasMany
    {
        return $this->hasMany(AssetRequest::class);
    }

    /**
     * User menangani banyak Approval.
     */
    public function requestApprovals(): HasMany
    {
        return $this->hasMany(RequestApproval::class);
    }

    /**
     * User menerima banyak Asset.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'assigned_user_id');
    }

    /**
     * Aktivitas User.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Laravel Auth menggunakan kolom password_hash.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
}