@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">All Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Water Type</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name ?? $order->user->firstname.' '.$order->user->lastname }}</td>
                <td>{{ $order->product->name ?? 'N/A' }}</td>
                <td>{{ $order->quantity }}</td>
                <td>
                    @php
                        $statusColors = [
                            'pending' => 'secondary',
                            'confirmed' => 'primary',
                            'out_for_delivery' => 'warning',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</td>


                <td>₱{{ number_format($order->total_price, 2) }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        <select name="status" class="form-select form-select-sm d-inline w-auto">
                            <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                            <option value="confirmed" @if($order->status=='confirmed') selected @endif>Confirmed</option>
                            <option value="out_for_delivery" @if($order->status=='out_for_delivery') selected @endif>Out for Delivery</option>
                            <option value="delivered" @if($order->status=='delivered') selected @endif>Delivered</option>
                            <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('admin.orders.assignDeliveryPersonnel', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <select name="delivery_personnel_id" class="form-select form-select-sm">
                            <option value="">Select Delivery Personnel</option>
                            @foreach($deliveryPersonnel as $personnel)
                                <option value="{{ $personnel->id }}" @if($order->delivery_personnel_id == $personnel->id) selected @endif>{{ $personnel->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Assign Delivery Personnel</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">No orders found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
