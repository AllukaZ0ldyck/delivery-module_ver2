@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase">My Orders</h2>

    {{-- Success / Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Action Buttons --}}
    @if(auth()->user()->status == 'archived')
        <form action="{{ route('customer.reactivation.request', auth()->user()->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Request Reactivation</button>
        </form>
    @else
        <div class="mb-4">
            <a href="{{ route('orders.create') }}" class="btn btn-success text-uppercase fw-bold">
                + Place New Order
            </a>
        </div>
    @endif

    {{-- Orders Table --}}
    <div class="table-responsive">
        <table class="table table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Products</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Delivery Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>

                    {{-- 🧾 Product List --}}
                    <td class="text-start">
                        @if($order->items && $order->items->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($order->items as $item)
                                    <li>
                                        • {{ $item->product->name ?? 'Unknown Product' }}
                                        <small class="text-muted">(x{{ $item->quantity }})</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{ $order->product->name ?? 'N/A' }}
                        @endif
                    </td>

                    {{-- 💰 Total --}}
                    <td>₱{{ number_format($order->total_price, 2) }}</td>

                    {{-- 💳 Payment Info --}}
                    <td>
                        <div>
                            <span class="fw-bold">{{ $order->payment_method ?? 'N/A' }}</span><br>
                            <span class="badge
                                @if($order->payment_status == 'paid') bg-success
                                @elseif($order->payment_status == 'pending_verification') bg-warning text-dark
                                @elseif($order->payment_status == 'declined') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}
                            </span>
                        </div>
                    </td>

                    {{-- 🚚 Delivery Status --}}
                    <td>
                        <span class="badge 
                            @if($order->status == 'pending') bg-warning text-dark
                            @elseif($order->status == 'confirmed') bg-primary
                            @elseif($order->status == 'out_for_delivery') bg-info text-dark
                            @elseif($order->status == 'delivered') bg-success
                            @elseif($order->status == 'cancelled') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>

                    {{-- 📅 Delivery Date --}}
                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</td>

                    {{-- ⚙️ Actions --}}
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                        @if($order->status == 'pending')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endif

                        @if($order->status == 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        @endif

                        <!-- @if($order->payment_method === 'COD' && $order->payment_status === 'unpaid')
                            <form action="{{ route('orders.simulatePayment', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Simulate Payment</button>
                            </form>
                        @endif -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No orders yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
