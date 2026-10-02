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
     * Telemetry and System Performance Monitoring Dashboard.
     * Fulfills UK J.620100.044.01, J.620100.045.01, & J.620100.047.01.
     */
    public function index(Request $request): View
    {
        $summary = $this->monitorService->getPerformanceSummary();
        $recentMetrics = $this->monitorService->getRecentMetrics(25);
        $lowStockAlerts = $this->productService->getLowStockAlerts(10);
        $totalLogsCount = ProductActivityLog::count();

        return view('monitoring.index', compact('summary', 'recentMetrics', 'lowStockAlerts', 'totalLogsCount'));
    }

    /**
     * Dedicated Audit Trail & Activity Log Page.
     * Fulfills UK J.620100.043.01 & J.620100.046.01.
     */
    public function logs(Request $request): View
    {
        $actionFilter = $request->query('action');
        $search = $request->query('q');

        $logQuery = ProductActivityLog::with('product')->orderBy('id', 'desc');

        if ($actionFilter) {
            $logQuery->where('action', $actionFilter);
        }

        if ($search) {
            $logQuery->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_identifier', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $logQuery->paginate(20)->withQueryString();

        $counts = [
            'total' => ProductActivityLog::count(),
            'create' => ProductActivityLog::where('action', 'CREATE')->count(),
            'update' => ProductActivityLog::where('action', 'UPDATE')->count(),
            'delete' => ProductActivityLog::where('action', 'DELETE')->count(),
            'search' => ProductActivityLog::where('action', 'SEARCH')->count(),
            'alerts' => ProductActivityLog::where('action', 'LOW_STOCK_ALERT')->count(),
            'error' => ProductActivityLog::where('action', 'ERROR')->count(),
        ];

        return view('monitoring.logs', compact('logs', 'actionFilter', 'search', 'counts'));
    }
}
