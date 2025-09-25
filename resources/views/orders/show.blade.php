@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header">
            <h1 class="mb-4 text-uppercase">Order Details</h1>
        </div>

        <h4>Order Information</h4>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Product:</strong> {{ $order->product->name }}</p>
        <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
        <p><strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Delivery Date:</strong> {{ $order->delivery_date }}</p>

        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        <p><strong>Delivery Status:</strong> {{ ucfirst($order->delivery_status) }}</p>

        @if ($order->payment_status == 'pending')
            <form method="POST" action="{{ route('orders.pay', $order->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Make Payment</button>
            </form>
        @endif

        @if ($order->delivery_status == 'in_transit')
            <form method="POST" action="{{ route('orders.updateDeliveryStatus', $order->id) }}">
                @csrf
                <select name="delivery_status">
                    <option value="delivered">Mark as Delivered</option>
                </select>
                <button type="submit" class="btn btn-success">Update Delivery Status</button>
            </form>
        @endif
    </div>
</main>
@endsection
