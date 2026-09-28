<?php

namespace App\Http\Middleware;

use App\Models\PerformanceMetric;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PerformanceMonitoringMiddleware
 * Intercepts HTTP requests to capture response latency, peak memory usage,
 * and database query count for performance evaluation and telemetry.
 * Fulfills UK J.620100.045.01 (Memantau Performa Aplikasi).
 */
class PerformanceMonitoringMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enable database query logging for accurate count
        DB::enableQueryLog();
        $startTime = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        $endTime = microtime(true);
        $durationMs = round(($endTime - $startTime) * 1000, 2);
        $memoryMb = round(memory_get_peak_usage(true) / (1024 * 1024), 2);
        $queryCount = count(DB::getQueryLog());

        // Attach telemetry headers to HTTP response
        $response->headers->set('X-Response-Time-Ms', (string) $durationMs);
        $response->headers->set('X-Memory-Usage-MB', (string) $memoryMb);
        $response->headers->set('X-Query-Count', (string) $queryCount);

        // Record metrics to DB (skipping static asset requests if any)
        try {
            if (!$request->is('*.css', '*.js', '*.ico', '*.png', '*.jpg')) {
                PerformanceMetric::create([
                    'route_name' => $request->route() ? $request->route()->getName() : null,
                    'method' => $request->method(),
                    'url' => substr($request->fullUrl(), 0, 255),
                    'response_time_ms' => $durationMs,
                    'memory_usage_mb' => $memoryMb,
                    'db_query_count' => $queryCount,
                    'status_code' => $response->getStatusCode(),
                    'created_at' => now(),
                ]);
            }
        } catch (Exception $e) {
            // Silently ignore metric insertion failures to safeguard response delivery
        }

        return $response;
    }
}
