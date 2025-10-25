@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Profile</h2>
        @php
            $admin = Auth::guard('admin')->user();
        @endphp
    <form action="{{ route('role.profile.update', ['role' => strtolower($admin->user_type)]) }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" value="{{ old('address', $admin->address) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
            @if($admin->profile_picture)
                <img src="{{ asset('storage/' . $admin->profile_picture) }}" class="mt-2 rounded" width="120">
            @endif
        </div>

        <div class="mb-3">
            <label>Change Password (optional)</label>
            <input type="password" name="password" class="form-control" placeholder="New password">
            <input type="password" name="password_confirmation" class="form-control mt-2" placeholder="Confirm password">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('role.profile.show', ['role' => strtolower($admin->user_type)]) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
