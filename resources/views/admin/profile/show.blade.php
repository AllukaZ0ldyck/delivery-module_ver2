@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm">
        <div class="d-flex align-items-center">
            <img src="{{ $admin->profile_picture ? asset('storage/' . $admin->profile_picture) : asset('images/default-avatar.png') }}"
                 alt="Profile"
                 class="rounded-circle me-3"
                 style="width:100px; height:100px; object-fit:cover;">
            <div>
                <h4>{{ $admin->name }}</h4>
                <p class="mb-0 text-muted">{{ ucfirst($admin->user_type) }}</p>
                <p>{{ $admin->email }}</p>
            </div>
        </div>

        <p class="mt-3"><strong>Phone:</strong> {{ $admin->phone ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $admin->address ?? 'N/A' }}</p>
        @php
            $admin = Auth::guard('admin')->user();
        @endphp
        <a href="{{ route('role.profile.edit', ['role' => strtolower($admin->user_type)]) }}" class="btn btn-primary mt-3">
            Edit Profile
        </a>
    </div>
</div>
@endsection
