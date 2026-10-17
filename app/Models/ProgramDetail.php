<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_title',
        'thumbnail_url',
        'description',
        'updated_by',
    ];

    /**
     * Admin yang terakhir mengedit detail acara ini.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
