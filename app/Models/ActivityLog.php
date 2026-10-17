<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_value',
        'new_value',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'old_value' => 'array',
            'new_value' => 'array',
        ];
    }

    /**
     * User yang melakukan aksi ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: catat log aktivitas dengan mudah.
     */
    public static function record(
        int $userId,
        string $action,
        string $description,
        string $entityType = null,
        int $entityId = null,
        array $oldValue = null,
        array $newValue = null,
    ): self {
        return self::create([
            'user_id'     => $userId,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'description' => $description,
            'old_value'   => $oldValue,
            'new_value'   => $newValue,
            'ip_address'  => request()->ip(),
        ]);
    }
}
