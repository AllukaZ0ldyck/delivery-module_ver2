@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
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
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>₱{{ number_format($order->total_price, 2) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->delivery_date }}</td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>

                    @if($order->status == 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                        </form>

                        <!-- Simulate Payment Button -->
                        <form action="{{ route('orders.simulatePayment', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Simulate Payment</button>
                        </form>
                    @elseif($order->status == 'out_for_delivery')
                        <span class="badge bg-warning">Out for Delivery</span>
                    @elseif($order->status == 'delivered')
                        <span class="badge bg-success">Delivered</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No orders yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
