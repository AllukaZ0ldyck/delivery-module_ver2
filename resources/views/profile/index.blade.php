@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update', ['user_type' => 'customer', 'id' => auth()->user()->id]) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
    </div>

    <div class="mb-3">
        <label for="qr_code" class="form-label">QR Code</label>
        @if(auth()->user()->qr_code)
            <div>
                <img src="{{ asset('storage/' . auth()->user()->qr_code) }}" alt="QR Code" style="width: 150px; height: 150px;">
            </div>
        @else
            <p>No QR Code available.</p>
        @endif
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </div>
</form>


    <div class="mt-4">
        <h5>Last Order</h5>
        @php
            $lastOrder = auth()->user()->orders()->latest()->first();
            $lastOrderDays = $lastOrder ? \Carbon\Carbon::parse($lastOrder->created_at)->diffInDays(\Carbon\Carbon::now()) : null;
        @endphp
        @if($lastOrder)
            <p>Last order: {{ $lastOrderDays }} days ago</p>
            <p>Status: {{ ucfirst($lastOrder->status) }}</p>
        @else
            <p>No orders yet.</p>
        @endif
    </div>
</div>
@endsection
