<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductActivityLog
 * Entity for tracking audit trails and application logs.
 * Fulfills UK J.620100.046.01 (Mengimplementasi Fitur Logging Aplikasi)
 */
class ProductActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false; // Only created_at is used

    protected $fillable = [
        'product_id',
        'action',
        'description',
        'user_identifier',
        'ip_address',
        'user_agent',
        'payload',
        'response_status',
        'execution_time_ms',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'execution_time_ms' => 'float',
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
