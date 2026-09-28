<?php

namespace App\Services;

use App\Models\PerformanceMetric;
use App\Models\ProductActivityLog;
use Illuminate\Support\Facades\DB;

/**
 * Class PerformanceMonitorService
 * Service for collecting, aggregating, and evaluating system performance metrics.
 * Fulfills UK J.620100.045.01 & J.620100.047.01.
 */
class PerformanceMonitorService
{
    /**
     * Get aggregate statistics on application performance.
     */
    public function getPerformanceSummary(): array
    {
        $metrics = PerformanceMetric::query();

        $totalRequests = $metrics->count();
        $avgResponseTime = $totalRequests > 0 ? round($metrics->avg('response_time_ms'), 2) : 0;
        $maxResponseTime = $totalRequests > 0 ? round($metrics->max('response_time_ms'), 2) : 0;
        $minResponseTime = $totalRequests > 0 ? round($metrics->min('response_time_ms'), 2) : 0;
        $avgMemory = $totalRequests > 0 ? round($metrics->avg('memory_usage_mb'), 2) : 0;
        $peakMemory = $totalRequests > 0 ? round($metrics->max('memory_usage_mb'), 2) : 0;
        $errorCount = PerformanceMetric::where('status_code', '>=', 400)->count();

        // 95th Percentile calculation for Senior-level latency analysis
        $p95 = 0;
        if ($totalRequests > 0) {
            $offset = (int) floor($totalRequests * 0.95);
            $p95 = PerformanceMetric::orderBy('response_time_ms', 'asc')
                ->skip($offset)
                ->value('response_time_ms') ?? $avgResponseTime;
            $p95 = round($p95, 2);
        }

        return [
            'total_requests' => $totalRequests,
            'avg_response_time_ms' => $avgResponseTime,
            'p95_response_time_ms' => $p95,
            'min_response_time_ms' => $minResponseTime,
            'max_response_time_ms' => $maxResponseTime,
            'avg_memory_mb' => $avgMemory,
            'peak_memory_mb' => $peakMemory,
            'error_count' => $errorCount,
            'error_rate_percent' => $totalRequests > 0 ? round(($errorCount / $totalRequests) * 100, 2) : 0,
        ];
    }

    /**
     * Get recent request metrics for live telemetry table.
     */
    public function getRecentMetrics(int $limit = 15)
    {
        return PerformanceMetric::orderBy('id', 'desc')->limit($limit)->get();
    }

    /**
     * Get recent product activity audit logs.
     */
    public function getRecentActivityLogs(int $limit = 15)
    {
        return ProductActivityLog::with('product')->orderBy('id', 'desc')->limit($limit)->get();
    }
}
