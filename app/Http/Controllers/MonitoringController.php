<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use App\Models\ProductActivityLog;
use App\Services\PerformanceMonitorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class MonitoringController
 * Provides real-time dashboard for log monitoring and performance telemetry.
 * Fulfills:
 * - UK J.620100.043.01 (Melakukan Log Monitoring)
 * - UK J.620100.044.01 (Menerapkan Alert Notification)
 * - UK J.620100.045.01 (Memantau Performa Aplikasi)
 * - UK J.620100.047.01 (Melaksanakan Evaluasi Performa)
 */
class MonitoringController extends Controller
{
    protected PerformanceMonitorService $monitorService;
    protected ProductServiceInterface $productService;

    public function __construct(
        PerformanceMonitorService $monitorService,
        ProductServiceInterface $productService
    ) {
        $this->monitorService = $monitorService;
        $this->productService = $productService;
    }

    /**
     * Dashboard page with telemetry metrics and activity logs
     */
    public function index(Request $request): View
    {
        $actionFilter = $request->query('action');
        $logQuery = ProductActivityLog::with('product')->orderBy('id', 'desc');

        if ($actionFilter) {
            $logQuery->where('action', $actionFilter);
        }

        $logs = $logQuery->paginate(15)->withQueryString();
        $summary = $this->monitorService->getPerformanceSummary();
        $recentMetrics = $this->monitorService->getRecentMetrics(15);
        $lowStockAlerts = $this->productService->getLowStockAlerts(10);

        return view('monitoring.index', compact('summary', 'recentMetrics', 'logs', 'lowStockAlerts', 'actionFilter'));
    }
}
