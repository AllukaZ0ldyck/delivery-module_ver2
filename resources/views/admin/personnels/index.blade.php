@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase fw-bold">Admin Personnels</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Registered Personnel</h5>
            <a href="{{ route('admin.personnels.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add New Personnel
            </a>
        </div>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personnels as $person)
                    <tr>
                        <td>{{ $person->id }}</td>
                        <td>{{ $person->name }}</td>
                        <td>{{ $person->email }}</td>
                        <td>
                            <span class="badge 
                                @if($person->user_type === 'Delivery') bg-info 
                                @elseif($person->user_type === 'staff') bg-warning 
                                @else bg-secondary @endif">
                                {{ ucfirst($person->user_type) }}
                            </span>
                        </td>
                        <td>{{ $person->created_at ? $person->created_at->format('M d, Y') : '—' }}</td>
                        <td>
                            <a href="{{ route('admin.personnels.edit', $person->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                            <form action="{{ route('admin.personnels.destroy', $person->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this personnel?')">
                                    Delete
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            No personnels found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
