<?php

namespace App\Http\Controllers;

use App\Services\SalesAnalyticsService;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class AnalyticsController extends Controller
{
    protected $salesAnalyticsService;

    public function __construct(SalesAnalyticsService $salesAnalyticsService)
    {
        $this->salesAnalyticsService = $salesAnalyticsService;
    }

    public function index(Request $request)
    {
        // Validate date inputs
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Get date inputs with defaults
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Fetch data
        $totalOrders = Order::count();
        $totalSales = Order::sum('total');
        $categoryWiseSales = $this->salesAnalyticsService->getCategoryWiseSales($startDate, $endDate);
        $paymentStatusBreakdown = $this->salesAnalyticsService->getPaymentStatusBreakdown($startDate, $endDate);
        $salesTrends = $this->salesAnalyticsService->getSalesTrends($startDate, $endDate);
        $lowStockProducts = $this->salesAnalyticsService->getLowStockProducts();
        $topSellingProducts = $this->salesAnalyticsService->getTopSellingProducts($startDate, $endDate);
        $totalCustomers = $this->salesAnalyticsService->getTotalCustomers($startDate, $endDate);

        return view('analytics.dashboard', compact(
            'categoryWiseSales',
            'paymentStatusBreakdown',
            'salesTrends',
            'lowStockProducts',
            'topSellingProducts',
            'totalCustomers',
            'totalOrders',
            'totalSales'
        ));
    }

    public function exportReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch data for the report
        $reportData = $this->salesAnalyticsService->getCategoryWiseSales($startDate, $endDate);

        // Export to Excel
        return Excel::download(new SalesReportExport($reportData), 'sales_report.xlsx');
    }
}
