<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\Orderdetails;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesAnalyticsService
{
    /**
     * Fetch category-wise sales with optional date range filtering.
     */
    public function getCategoryWiseSales($startDate = null, $endDate = null)
    {
        Log::info('Fetching category-wise sales');

        $query = Category::select(
            'categories.category_name',
            DB::raw('COALESCE(SUM(orderdetails.total), 0) as total_sales'),
            DB::raw('COALESCE(SUM(orderdetails.quantity), 0) as total_quantity')
        )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('orderdetails', 'products.id', '=', 'orderdetails.product_id')
            ->leftJoin('orders', 'orderdetails.order_id', '=', 'orders.id');

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw("STR_TO_DATE(orders.order_date, '%Y-%m-%d')"), [$startDate, $endDate]);
        }

        $salesData = $query->groupBy('categories.category_name')->get();

        Log::info('Category-wise Sales Data:', ['data' => $salesData->toArray()]);
        return $salesData;
    }

    /**
     * Fetch top-selling products with optional date range filtering.
     */
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 10)
    {
        Log::info('Fetching top-selling products');

        $query = Orderdetails::select(
            'products.product_name',
            DB::raw('SUM(orderdetails.quantity) as total_quantity'),
            DB::raw('SUM(orderdetails.total) as total_sales')
        )
            ->join('products', 'orderdetails.product_id', '=', 'products.id')
            ->join('orders', 'orderdetails.order_id', '=', 'orders.id');

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw("STR_TO_DATE(orders.order_date, '%Y-%m-%d')"), [$startDate, $endDate]);
        }

        $topSellingProducts = $query
            ->groupBy('products.product_name')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get();

        Log::info('Top Selling Products Data:', ['data' => $topSellingProducts->toArray()]);
        return $topSellingProducts;
    }

    /**
     * Fetch payment status breakdown with optional date range filtering.
     */
    public function getPaymentStatusBreakdown($startDate = null, $endDate = null)
    {
        Log::info('Fetching payment status breakdown');

        $query = Order::select(
            'payment_status',
            DB::raw('COUNT(*) as total_orders')
        );

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw("STR_TO_DATE(order_date, '%Y-%m-%d')"), [$startDate, $endDate]);
        }

        $paymentStatusData = $query->groupBy('payment_status')->get();

        Log::info('Payment Status Breakdown:', ['data' => $paymentStatusData->toArray()]);
        return $paymentStatusData;
    }

    /**
     * Fetch daily sales trends with optional date range filtering.
     */
    public function getSalesTrends($startDate = null, $endDate = null, $interval = 'day')
    {
        \Log::info('Fetching sales trends');
        \Log::debug('Start Date: ' . $startDate);
        \Log::debug('End Date: ' . $endDate);

        $query = Order::select(
            DB::raw("DATE_FORMAT(STR_TO_DATE(order_date, '%d-%M-%Y'), '%Y-%m-%d') as date"),
            DB::raw('SUM(total) as total_sales')
        );

        // *** Removed ->where('order_status', 'complete')->where('payment_status', 'HandCash'); ***

        // Date range filter (optional)
        if ($startDate && $endDate) {
            $query->whereRaw("STR_TO_DATE(order_date, '%d-%M-%Y') BETWEEN ? AND ?", [$startDate, $endDate]);
        }

        $salesTrends = $query
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        \Log::info('Sales Trends Data:', ['data' => $salesTrends->toArray()]);
        return $salesTrends;
    }


    /**
     * Fetch products with low stock based on a threshold.
     */
    public function getLowStockProducts($threshold = 10)
    {
        Log::info("Fetching low stock products with threshold: {$threshold}");

        $lowStockProducts = Product::where('product_store', '<=', $threshold)
            ->orderBy('product_store', 'asc')
            ->get();

        Log::info('Low Stock Products:', ['data' => $lowStockProducts->toArray()]);
        return $lowStockProducts;
    }
    public function getTotalCustomers($startDate = null, $endDate = null)
    {
        Log::info('Fetching total customers');

        $query = Order::select(DB::raw('COUNT(DISTINCT customer_id) as total_customers'));

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw("STR_TO_DATE(order_date, '%Y-%m-%d')"), [$startDate, $endDate]);
        }

        $totalCustomers = $query->first()->total_customers ?? 0;

        Log::info('Total Customers:', ['count' => $totalCustomers]);
        return $totalCustomers;
    }

}
