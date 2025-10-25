@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header mb-4">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-muted">Here's your account overview and recent activity</p>
        </div>

        {{-- Dashboard Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary p-3 shadow">
                    <h5>Total Orders</h5>
                    <h3>{{ $totalOrders ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success p-3 shadow">
                    <h5>Pending Orders</h5>
                    <h3>{{ $pendingOrders ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-danger p-3 shadow">
                    <h5>Total Amount Due</h5>
                    <h3>₱{{ number_format($totalDue ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>

        {{-- Action Button --}}
        @if(auth()->user()->status == 'archived')
            <form action="{{ route('customer.reactivation.request', auth()->user()->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Request Reactivation</button>
            </form>
        @else
            <div class="mb-4">
                <a href="{{ route('orders.create') }}" class="btn btn-primary text-uppercase fw-bold">Place New Order</a>
            </div>
        @endif

        {{-- Recent Orders --}}
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white text-uppercase fw-bold">
                Recent Orders
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Delivery Date</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->product->name ?? 'N/A' }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</td>
                                    <td>₱{{ number_format($order->total_price, 2) }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Cancel</button>
                                            </form>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info text-center text-uppercase">No recent orders found.</div>
                @endif
            </div>
        </div>

    </div>
</main>
@endsection
