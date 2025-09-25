@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header">
            <h1 class="mb-4 text-uppercase">Paid Bills</h1>
        </div>

        {{-- Display Paid Bills --}}
        <div>
            @if(count($bills) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $bill)
                        <tr>
                            <td>{{ $bill->id }}</td>
                            <td>{{ $bill->product->name ?? 'N/A' }}</td>
                            <td>₱{{ number_format($bill->amount, 2) }}</td>
                            <td>{{ ucfirst($bill->status) }}</td>
                            <td>
                                @if($bill->payments->isEmpty())
                                    <span class="badge bg-warning">Unpaid</span>
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </td>
                            <td>
                                @if($bill->payments->isEmpty())
                                    <a href="{{ route('customer-payments.pay', $bill->id) }}" class="btn btn-primary btn-sm">Pay Now</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">No paid bills found.</div>
            @endif
        </div>
    </div>
</main>
@endsection
