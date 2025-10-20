@extends('layouts.app')

@section('content')
<div class="container">

    @php
        $pendingPaymentsCount = \App\Models\Order::where('payment_status', 'pending_verification')->count();
    @endphp

    <h2 class="mb-4">
        All Orders 
        @if($pendingPaymentsCount > 0)
            <span class="badge bg-danger ms-2">{{ $pendingPaymentsCount }} Pending Payment{{ $pendingPaymentsCount > 1 ? 's' : '' }}</span>
        @endif
    </h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ request()->has('filter') ? route('admin.orders.index') : route('admin.orders.index', ['filter' => 'pending_verification']) }}" class="btn btn-outline-danger mb-3">
        {{ request()->has('filter') ? 'Show All Orders' : 'Show Pending GCash Payments' }}
    </a>


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
                <th>Delivery Personnel</th>
                <th>Delivery Actions</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name ?? $order->user->firstname.' '.$order->user->lastname }}</td>
                <td>
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
                <td>
                    @if($order->items && $order->items->count())
                        {{ $order->items->sum('quantity') }}
                    @else
                        {{ $order->quantity ?? 1 }}
                    @endif
                </td>

                <td>
                    @php
                        $statusColors = [
                            'pending' => 'secondary',
                            'approved' => 'primary',
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
                            <option value="approved" @if($order->status=='approved') selected @endif>Approved</option>
                            <option value="out_for_delivery" @if($order->status=='out_for_delivery') selected @endif>Out for Delivery</option>
                            <option value="delivered" @if($order->status=='delivered') selected @endif>Delivered</option>
                            <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </form>
                </td>
                <td>
                    @if($order->deliveryPersonnel)
                        Assigned to: <strong>{{ $order->deliveryPersonnel->name }}</strong>
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
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
                <td>
                    <strong>{{ $order->payment_method }}</strong><br>

                    @if($order->payment_method === 'GCash')
                        @if($order->payment_receipt)
                            <a href="{{ asset('storage/' . $order->payment_receipt) }}" 
                            target="_blank" 
                            class="text-primary d-block mb-2">View Receipt</a>

                            <span class="badge 
                                @if($order->payment_status == 'pending_verification') bg-warning 
                                @elseif($order->payment_status == 'paid') bg-success 
                                @elseif($order->payment_status == 'declined') bg-danger 
                                @else bg-secondary 
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>

                            @if($order->payment_status === 'pending_verification')
                                <div class="mt-2 d-flex gap-1">
                                    {{-- APPROVE BUTTON --}}
                                    <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>

                                    {{-- DECLINE BUTTON --}}
                                    <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="declined">
                                        <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                    </form>
                                </div>
                            @endif

                        @else
                            <span class="text-danger">No receipt uploaded</span>
                        @endif
                    @else
                        <span class="badge bg-info">COD</span>
                    @endif
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
