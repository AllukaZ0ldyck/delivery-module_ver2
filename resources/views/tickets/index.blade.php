@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Support Tickets</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tickets.create') }}" class="btn btn-success mb-3">+ New Ticket</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Response</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>
                        <span class="badge bg-{{ $ticket->status == 'resolved' ? 'success' : ($ticket->status == 'in_progress' ? 'info' : 'warning') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td>{{ $ticket->staff_response ?? 'No response yet' }}</td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
