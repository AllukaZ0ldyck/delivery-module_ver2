@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Order Details #{{ $order->id }}</h2>

    {{-- 🧍 Customer Info --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Customer Info</div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $order->user->name ?? $order->user->firstname.' '.$order->user->lastname }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Contact:</strong> {{ $order->user->contact ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
        </div>
    </div>

    {{-- 🧾 Order Info --}}
    <div class="card mb-3">
        <div class="card-header bg-success text-white">Order Information</div>
        <div class="card-body">

            {{-- ✅ Ordered Items Table --}}
            @if($order->items && $order->items->count() > 0)
                <h5 class="mb-3">Ordered Products</h5>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Water Type</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity ?? 0 }}</td>
                                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                <td>₱{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{-- Backward compatibility --}}
                <p><strong>Water Type:</strong> {{ $order->product->name ?? 'N/A' }}</p>
                <p><strong>Quantity:</strong> {{ $order->quantity ?? 0 }}</p>
            @endif

            <hr>
            <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
            <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </p>
        </div>
    </div>

    {{-- 💳 Payment Info --}}
    <div class="card mb-3">
        <div class="card-header bg-warning text-white">Payment Details</div>
        <div class="card-body">
            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
            <p><strong>Payment Status:</strong>
                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'pending_verification' ? 'warning' : 'secondary') }}">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                </span>
            </p>

            {{-- ✅ Display GCash Receipt --}}
            @if($order->payment_method === 'GCash' && $order->payment_receipt)
                <div class="mt-3">
                    <strong>GCash Receipt:</strong><br>
                    <img src="{{ asset('storage/' . $order->payment_receipt) }}"
                         alt="GCash Receipt"
                         class="img-thumbnail mt-2"
                         style="max-width: 250px;">
                </div>
            @endif

            {{-- ✅ Payment History --}}
            @if($order->payments && $order->payments->count() > 0)
                <h5 class="mt-4">Payment History</h5>
                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- 🚚 Delivery Info --}}
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Delivery Details</div>
        <div class="card-body">
            <p><strong>Delivery Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->delivery_status ?? 'N/A')) }}</p>

            {{-- Assigned Delivery Personnel --}}
            @if($order->deliveryPersonnel)
                <p><strong>Assigned To:</strong> {{ $order->deliveryPersonnel->name }}</p>
                <p><strong>Contact:</strong> {{ $order->deliveryPersonnel->email ?? 'N/A' }}</p>
            @else
                <p><em>No delivery personnel assigned yet.</em></p>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Back to Orders</a>
</div>
@endsection
