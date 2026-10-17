<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color_hex',
        'icon',
        'created_by',
    ];

    /**
     * Relasi ke user admin yang membuat kategori ini.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke tabel programs (satu kategori punya banyak program).
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
