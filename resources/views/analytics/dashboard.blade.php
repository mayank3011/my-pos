@extends('admin_dashboard')
@section('admin')
    <div class="container mt-4">
        <h1 class="text-center mb-4">
            <i class="mdi mdi-chart-line"></i> Sales Analytics Dashboard
        </h1>

        <!-- Date Range Filter -->
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <form method="GET" action="{{ route('analytics.index') }}" class="d-flex gap-2 align-items-center">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="mdi mdi-calendar"></i>
                        </span>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request()->input('start_date', now()->subDays(7)->format('Y-m-d')) }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="mdi mdi-calendar"></i>
                        </span>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ request()->input('end_date', now()->format('Y-m-d')) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-filter"></i> Filter
                    </button>
                    <a href="{{ route('analytics.export') }}" class="btn btn-success">
                        <i class="mdi mdi-export"></i> Export Report
                    </a>
                </form>
            </div>
        </div>
        <div class="row">
        @php
            $cards = [
                ['title' => 'Total Customers', 'icon' => 'mdi-account-group', 'value' => $totalCustomers, 'bg' => 'success'],
                ['title' => 'Total Orders', 'icon' => 'mdi-package-variant', 'value' => $totalOrders, 'bg' => 'info'],
                ['title' => 'Total Sales', 'icon' => 'mdi-cash-multiple', 'value' => '₹' . number_format($totalSales, 2), 'bg' => 'warning']
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-md-4">
            <div class="card text-white bg-{{ $card['bg'] }} shadow-sm">
                <div class="card-body text-center">
                    <i class="mdi {{ $card['icon'] }} mdi-48px"></i>
                    <h5 class="card-title">{{ $card['title'] }}</h5>
                    <p class="fs-3 fw-bold">{{ $card['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

        <!-- Category-wise Sales Bar Chart -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-chart-bar"></i> Category-wise Sales (Amount)
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($categoryWiseSales->isEmpty())
                            <p class="text-muted">No sales data available for this period</p>
                        @else
                            <canvas id="categorySalesChart" style="max-height: 400px; min-width: 100%;"></canvas>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Quantity Sales Data Chart -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-counter"></i> Category-wise Quantity Sold
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($categoryWiseSales->isEmpty())
                            <p class="text-muted">No quantity data available for this period</p>
                        @else
                            <canvas id="quantitySalesChart" style="max-height: 400px; min-width: 100%;"></canvas>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Breakdown -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-credit-card-outline"></i> Payment Status Breakdown
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($paymentStatusBreakdown->isEmpty())
                            <p class="text-muted">No payment data available</p>
                        @else
                            <canvas id="paymentStatusChart" style="max-height: 400px; min-width: 100%;"></canvas>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sales Trends Over Time -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-trending-up"></i> Sales Trends Over Time
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($salesTrends->isEmpty())
                            <p class="text-muted">No trend data available</p>
                        @else
                            <canvas id="salesTrendsChart" style="max-height: 400px; min-width: 100%;"></canvas>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock and Top Selling Products -->
        <div class="row">
            <!-- Low Stock Products -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-package-variant-closed"></i> Low Stock Products
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($lowStockProducts->isEmpty())
                            <p class="text-muted">No low stock products found</p>
                        @else
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <i class="mdi mdi-package"></i> Product Name
                                        </th>
                                        <th>
                                            <i class="mdi mdi-database"></i> Stock Quantity
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->product_store ?? $product->stock_quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="mdi mdi-star"></i> Top Selling Products
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topSellingProducts->isEmpty())
                            <p class="text-muted">No top-selling products available</p>
                        @else
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <i class="mdi mdi-file-document"></i> Product Name
                                        </th>
                                        <th scope="col">
                                            <i class="mdi mdi-counter"></i> Total Quantity Sold
                                        </th>
                                        <th scope="col">
                                            <i class="mdi mdi-currency-inr"></i> Total Sales
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topSellingProducts as $product)
                                        <tr>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->total_quantity }}</td>
                                            <td>₹{{ number_format($product->total_sales) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data from backend
        const categorySalesData = @json($categoryWiseSales);

        // Category-wise Sales (Amount) Chart
        if (categorySalesData.length > 0) {
            const categoryLabels = categorySalesData.map(item => item.category_name);
            const categoryTotalSales = categorySalesData.map(item => item.total_sales);

            new Chart(document.getElementById('categorySalesChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Total Sales (₹)',
                        data: categoryTotalSales,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) label += `₹${context.parsed.y.toFixed(2)}`;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return `₹${value}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Category-wise Quantity Sold Chart
        if (categorySalesData.length > 0) {
            // Adjust field name if necessary (use 'total_quantity' or 'quantity')
            const quantityData = categorySalesData.map(item => item.total_quantity);

            new Chart(document.getElementById('quantitySalesChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: categorySalesData.map(item => item.category_name),
                    datasets: [{
                        label: 'Total Quantity Sold',
                        data: quantityData,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) label += context.parsed.y;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Payment Status Breakdown Chart
        const paymentStatusData = @json($paymentStatusBreakdown);
        if (paymentStatusData.length > 0) {
            const paymentLabels = paymentStatusData.map(item => item.payment_status || 'Unknown');
            const paymentOrders = paymentStatusData.map(item => item.total_orders);

            new Chart(document.getElementById('paymentStatusChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        label: 'Orders by Payment Status',
                        data: paymentOrders,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Sales Trends Over Time Chart
        const salesTrendsData = @json($salesTrends);
        if (salesTrendsData.length > 0) {
            const trendLabels = salesTrendsData.map(item => item.date);
            const trendSales = salesTrendsData.map(item => item.total_sales);

            new Chart(document.getElementById('salesTrendsChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Total Sales (₹)',
                        data: trendSales,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endsection
