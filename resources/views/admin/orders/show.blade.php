@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Order Details #{{ $order->id }}</h2>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Customer Info</div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $order->user->name ?? $order->user->firstname.' '.$order->user->lastname }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-success text-white">Order Info</div>
        <div class="card-body">
            <p><strong>Water Type:</strong> {{ $order->product->name ?? 'N/A' }}</p>
            <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
            <p><strong>Total:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
            <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</p>
        </div>
    </div>

    @if($order->payments->count())
    <div class="card mb-3">
        <div class="card-header bg-warning text-white">Payments</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection
