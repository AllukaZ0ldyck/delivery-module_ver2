@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Customer Support Tickets</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Response</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->message }}</td>
                    <td>
                        <span class="badge bg-{{ $ticket->status == 'resolved' ? 'success' : ($ticket->status == 'in_progress' ? 'info' : 'warning') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td>{{ $ticket->staff_response ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('staff.tickets.update', $ticket->id) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select form-select-sm mb-2">
                                <option value="pending" @selected($ticket->status == 'pending')>Pending</option>
                                <option value="in_progress" @selected($ticket->status == 'in_progress')>In Progress</option>
                                <option value="resolved" @selected($ticket->status == 'resolved')>Resolved</option>
                            </select>
                            <textarea name="staff_response" class="form-control mb-2" rows="2" placeholder="Response (optional)">{{ $ticket->staff_response }}</textarea>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
