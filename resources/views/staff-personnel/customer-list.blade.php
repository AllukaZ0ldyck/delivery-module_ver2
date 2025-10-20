@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Manage Customers</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>QR Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email ?? 'N/A' }}</td>
                    <td>{{ $customer->contact ?? 'N/A' }}</td>
                    <td>{{ $customer->address ?? 'N/A' }}</td>
                    <td>
                        @if($customer->qr_code)
                            <img src="{{ asset('storage/'.$customer->qr_code) }}" width="60">
                        @else
                            <span class="text-muted">No QR</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('staff.customer.manage', $customer->id) }}" class="btn btn-primary btn-sm">Manage</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
