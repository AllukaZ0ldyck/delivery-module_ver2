@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase fw-bold">Admin Overview</h2>

    {{-- DASHBOARD STATS --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h6>Total Orders</h6>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6>Total Customers</h6>
                    <h3>{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h6>Total Revenue</h6>
                    <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h6>Total Gallons Borrowed</h6>
                    @php
                        $totalGallonsBorrowed = \App\Models\BorrowedGallon::whereIn('status', ['pending', 'approved'])
                            ->sum('gallon_count');
                    @endphp
                    <h3>{{ $totalGallonsBorrowed }} Gallons</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- SALES + BORROW CHARTS --}}
    <div class="row g-4 mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">Sales Overview</h5>
                        <select id="salesFilter" class="form-select form-select-sm w-auto">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                    <canvas id="salesChart" height="150"></canvas>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Borrowed Gallons by Status</h5>
                    <canvas id="borrowChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW ORDERS SECTION --}}
    <h4 class="mt-5 d-flex align-items-center">
        New Orders (Today)
        @if($newOrdersCount > 0)
            <span class="ms-2 badge bg-danger rounded-circle" style="width: 12px; height: 12px;"></span>
        @endif
    </h4>

    @if($newOrdersCount > 0)
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todayOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? ($order->user->firstname.' '.$order->user->lastname) }}</td>
                        <td>
                            @if($order->items && count($order->items) > 0)
                                <ul class="mb-0 ps-3">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->product->name ?? 'N/A' }} (x{{ $item->quantity }})</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $order->product->name ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : 'success' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->created_at->format('h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info mt-3">No new orders today.</div>
    @endif
</div>
@endsection


{{-- CHART.JS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const salesCtx = document.getElementById('salesChart');
        let salesChart;

        function renderSalesChart(labels, data) {
            if (salesChart) salesChart.destroy(); // destroy existing chart before re-render
            salesChart = new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Sales (₱)',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Default render (this week)
        renderSalesChart(@json($salesLast7Days['dates']), @json($salesLast7Days['amounts']));

        // Filter change event
        document.getElementById('salesFilter').addEventListener('change', function () {
            const filter = this.value;
            fetch(`/admin/dashboard/sales-data/${filter}`)
                .then(res => res.json())
                .then(data => {
                    renderSalesChart(data.labels, data.values);
                })
                .catch(err => console.error('Error fetching chart data:', err));
        });

        // Borrowed Gallons Pie Chart
        const borrowCtx = document.getElementById('borrowChart');
        if (borrowCtx) {
            new Chart(borrowCtx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Approved', 'Returned'],
                    datasets: [{
                        data: @json($borrowStats),
                        backgroundColor: ['#ffc107', '#198754', '#0d6efd']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>

@endpush

<style>
.badge.bg-danger.rounded-circle {
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.6; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
