<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PerformanceMetric
 * Entity for recording performance metrics and telemetry.
 * Fulfills UK J.620100.045.01 (Memantau Performa Aplikasi)
 */
class PerformanceMetric extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'route_name',
        'method',
        'url',
        'response_time_ms',
        'memory_usage_mb',
        'db_query_count',
        'status_code',
        'created_at',
    ];

    protected $casts = [
        'response_time_ms' => 'float',
        'memory_usage_mb' => 'float',
        'db_query_count' => 'integer',
        'status_code' => 'integer',
        'created_at' => 'datetime',
    ];
}
