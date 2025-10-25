@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order Payment</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="order-details">
        <h4>Order Details</h4>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Product:</strong> {{ $order->product->name }}</p>
        <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
        <p><strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Delivery Date:</strong> {{ $order->delivery_date }}</p>
    </div>

    <h4>Payment Method</h4>
    <form method="POST" action="{{ route('customer-payments.pay', $order->id) }}">
        @csrf

        <div class="form-group">
            <label for="payment_method">Choose Payment Method</label>
            <select class="form-control" name="payment_type" id="payment_method" required>
                <option value="online">Online Payment</option>
                <option value="cash">Cash on Delivery</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="payment_amount">Amount</label>
            <input type="number" class="form-control" name="payment_amount" id="payment_amount" value="{{ $order->total_price }}" readonly>
        </div>

        <!-- Cash Payment -->
        <div id="cash-payment" class="mt-3" style="display: none;">
            <div class="form-group">
                <label for="payor_name">Payor Name</label>
                <input type="text" class="form-control" name="payor_name" id="payor_name" required placeholder="Enter your name">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Proceed to Payment</button>
    </form>
</div>

<script>
    // Show or hide the Cash Payment section based on the selected payment method
    document.getElementById('payment_method').addEventListener('change', function () {
        if (this.value === 'cash') {
            document.getElementById('cash-payment').style.display = 'block';
        } else {
            document.getElementById('cash-payment').style.display = 'none';
        }
    });
</script>

@endsection
