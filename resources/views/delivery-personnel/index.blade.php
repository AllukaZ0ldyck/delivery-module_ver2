@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Assigned Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('delivery-personnel.show', $order->id) }}" class="btn btn-info btn-sm">View</a>

                        @if($order->status == 'out_for_delivery')
                            <form action="{{ route('delivery-personnel.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="delivered">  <!-- Hidden input for status -->
                                <button type="submit" class="btn btn-success btn-sm">Mark as Delivered</button>
                            </form>


                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
