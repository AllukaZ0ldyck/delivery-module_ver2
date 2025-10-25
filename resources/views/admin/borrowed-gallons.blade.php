@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Borrowed Gallons Management</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Type</th>
                <th>Count</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowedGallons as $gallon)
                <tr>
                    <td>{{ $gallon->user->name }}</td>
                    <td>{{ $gallon->gallon_type }}</td>
                    <td>{{ $gallon->gallon_count }}</td>
                    <td>{{ \Carbon\Carbon::parse($gallon->due_date)->format('M d, Y') }}</td>
                    <td>
                        <span class="badge 
                            @if($gallon->status == 'pending') bg-warning
                            @elseif($gallon->status == 'approved') bg-success
                            @elseif($gallon->status == 'returned') bg-info
                            @endif">
                            {{ ucfirst($gallon->status) }}
                        </span>
                    </td>
                    <td>{{ $gallon->approver->name ?? '-' }}</td>
                    <td>
                        @if($gallon->status == 'pending')
                            <form action="{{ route('admin.borrowed-gallons.approve', $gallon->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                        @elseif($gallon->status == 'approved')
                            <form action="{{ route('admin.borrowed-gallons.update', $gallon->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="returned">
                                <button type="submit" class="btn btn-info btn-sm">Mark as Returned</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
