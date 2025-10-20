@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Assigned Orders</h2>

    {{-- Success or Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($orders->count() > 0)
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Delivery Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? $order->user->firstname.' '.$order->user->lastname }}</td>
                        
                        {{-- Handle multi-product orders --}}
                        <td>
                            @if($order->items && $order->items->count() > 0)
                                <ul class="mb-0">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->product->name ?? 'N/A' }} (x{{ $item->quantity }})</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $order->product->name ?? 'N/A' }}
                            @endif
                        </td>

                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status == 'pending') bg-secondary
                                @elseif($order->status == 'confirmed') bg-primary
                                @elseif($order->status == 'out_for_delivery') bg-warning text-dark
                                @elseif($order->status == 'delivered') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('delivery-personnel.show', $order->id) }}" class="btn btn-sm btn-info">View</a>

                            @if($order->status == 'out_for_delivery')
                                <form action="{{ route('delivery-personnel.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="btn btn-sm btn-success">Mark as Delivered</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            No orders have been assigned to you yet.
        </div>
    @endif
</div>
@endsection
