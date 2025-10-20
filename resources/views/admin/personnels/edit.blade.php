@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase fw-bold">Edit Personnel</h2>

    <form action="{{ route('admin.personnels.update', $personnel->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Full Name</label>
                <input type="text" id="name" name="name" value="{{ $personnel->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" id="email" name="email" value="{{ $personnel->email }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">New Password (optional)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="user_type" class="form-label fw-bold">Role</label>
                <select id="user_type" name="user_type" class="form-control" required>
                    <option value="Delivery" {{ $personnel->user_type == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="staff" {{ $personnel->user_type == 'staff' ? 'selected' : '' }}>staff</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary px-4">Update</button>
            <a href="{{ route('admin.personnels.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
@endsection
