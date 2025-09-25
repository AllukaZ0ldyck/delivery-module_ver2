@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5>Total Customers</h5>
                    <h3>{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger p-3 shadow">
                <h5>Total Gallons Borrowed</h5>
                <h3>
                    @php
                        $totalGallonsBorrowed = \App\Models\BorrowedGallon::where('status', 'borrowed')->sum('gallon_count');
                    @endphp
                    {{ $totalGallonsBorrowed }} Gallons
                </h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info p-3 shadow">
                <h5>Total Sales</h5>
                <h3>
                    @php
                        // Calculate total sales based on delivered orders
                        $totalSales = \App\Models\Order::where('status', 'delivered')->sum('total_price');
                    @endphp
                    ₱{{ number_format($totalSales, 2) }}
                </h3>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Recent Orders</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($recentOrders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name ?? $order->user->firstname.' '.$order->user->lastname }}</td>
                <td>{{ $order->product->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>₱{{ number_format($order->total_price, 2) }}</td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
