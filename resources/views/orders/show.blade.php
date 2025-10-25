@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header">
            <h1 class="mb-4 text-uppercase">Order Details</h1>
        </div>

        <div class="card p-4 shadow-sm border-0">
            <h4 class="mb-3">Order Information</h4>
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
            <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') }}</p>

            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
            <p><strong>Payment Status:</strong>
                <span class="badge 
                    @if($order->payment_status == 'paid') bg-success
                    @elseif($order->payment_status == 'pending_verification') bg-warning
                    @elseif($order->payment_status == 'declined') bg-danger
                    @else bg-secondary
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                </span>
            </p>

            <p><strong>Order Status:</strong>
                <span class="badge 
                    @if($order->status == 'delivered') bg-success
                    @elseif($order->status == 'out_for_delivery') bg-info
                    @elseif($order->status == 'confirmed') bg-primary
                    @elseif($order->status == 'pending') bg-warning
                    @else bg-secondary
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </p>

            {{-- Show GCash receipt if available --}}
            @if($order->payment_method === 'GCash' && $order->payment_receipt)
                <div class="mt-3">
                    <strong>GCash Payment Receipt:</strong><br>
                    <img src="{{ asset('storage/' . $order->payment_receipt) }}"
                         alt="GCash Receipt"
                         style="max-width: 300px; border-radius: 8px; border: 1px solid #ccc;">
                </div>
            @endif
        </div>

        {{-- 🧾 Order Items Section --}}
        <div class="card mt-4 p-4 shadow-sm border-0">
            <h4 class="mb-3">Ordered Items</h4>

            @if($order->items && $order->items->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items ?? [] as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                <td>₱{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <h5 class="text-end mt-3">
                    <strong>Grand Total:</strong> ₱{{ number_format($order->total_price, 2) }}
                </h5>
            @else
                {{-- Backward compatibility: old single-product orders --}}
                <p><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</p>
                <p><strong>Quantity:</strong> {{ $order->quantity ?? 1 }}</p>
                <p><strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
            @endif
        </div>

        {{-- 💳 Payment or Delivery Actions --}}
        <div class="mt-4">
            @if ($order->payment_status == 'pending')
                <form method="POST" action="{{ route('orders.pay', $order->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Make Payment</button>
                </form>
            @endif

            @if ($order->status == 'out_for_delivery')
                <form method="POST" action="{{ route('orders.updateDeliveryStatus', $order->id) }}" class="mt-3">
                    @csrf
                    <select name="delivery_status" class="form-select w-auto d-inline-block">
                        <option value="delivered">Mark as Delivered</option>
                    </select>
                    <button type="submit" class="btn btn-success">Update Delivery Status</button>
                </form>
            @endif
        </div>
    </div>
</main>
@endsection
