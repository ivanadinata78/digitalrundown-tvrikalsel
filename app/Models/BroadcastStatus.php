<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'broadcast_date',
        'no_urut',
        'status',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'broadcast_date' => 'date',
        ];
    }

    /**
     * User yang terakhir mengubah status tayang ini.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Cek apakah acara sedang on-air.
     */
    public function isOnAir(): bool
    {
        return $this->status === 'on_air';
    }
}
