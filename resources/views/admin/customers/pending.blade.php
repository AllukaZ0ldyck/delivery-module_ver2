@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Pending Customer Approvals</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Gallon Type</th>
                <th>Gallon Count</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendingUsers as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->contact }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->gallon_type }}</td>
                    <td>{{ $user->gallon_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.customers.approve', $user->id) }}" style="display:inline;">
                            @csrf
                            <button class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.customers.reject', $user->id) }}" style="display:inline;">
                            @csrf
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No pending accounts</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
