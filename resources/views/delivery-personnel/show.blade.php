@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order #{{ $order->id }} Details</h2>

    <div class="mb-4">
        <strong>Customer:</strong> {{ $order->user->name }} <br>
        <strong>Product:</strong> {{ $order->product->name }} <br>
        <strong>Quantity:</strong> {{ $order->quantity }} <br>
        <strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }} <br>
        <strong>Delivery Address:</strong> {{ $order->delivery_address }} <br>
        <strong>Status:</strong> {{ ucfirst($order->status) }} <br>
    </div>

    @if($order->status == 'out_for_delivery')
        <form action="{{ route('delivery-personnel.updateStatus', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="delivered">
            <button type="submit" class="btn btn-success">Mark as Delivered</button>
        </form>
    @endif
</div>
@endsection
