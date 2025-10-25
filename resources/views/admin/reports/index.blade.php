@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase">Sales Reports</h2>

    {{-- Filter buttons --}}
    <div class="mb-3">
        <a href="{{ route('admin.reports.index', ['filter' => 'daily']) }}" 
            class="btn btn-sm {{ $filter == 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">Daily</a>
        <a href="{{ route('admin.reports.index', ['filter' => 'monthly']) }}" 
            class="btn btn-sm {{ $filter == 'monthly' ? 'btn-primary' : 'btn-outline-primary' }}">Monthly</a>
        <a href="{{ route('admin.reports.index', ['filter' => 'yearly']) }}" 
            class="btn btn-sm {{ $filter == 'yearly' ? 'btn-primary' : 'btn-outline-primary' }}">Yearly</a>
    </div>

    {{-- Stats cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white p-3">
                <h5>Total Revenue</h5>
                <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3">
                <h5>Total Orders</h5>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white p-3">
                <h5>Pending Orders</h5>
                <h3>{{ $pendingOrders }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white p-3">
                <h5>Delivered Orders</h5>
                <h3>{{ $deliveredOrders }}</h3>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Orders Report ({{ ucfirst($filter) }})</h5>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Unknown' }}</td>
                            <td>₱{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ ucfirst($order->payment_status ?? 'N/A') }}</td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No orders found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('admin.reports.export', ['filter' => $filter]) }}" 
    class="btn btn-outline-success btn-sm mb-3">
    <i class="bi bi-file-earmark-excel"></i> Export to Excel
    </a>


</div>
@endsection
