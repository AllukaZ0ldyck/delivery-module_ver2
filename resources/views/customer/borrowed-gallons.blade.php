@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Borrowed Gallons</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Type</th>
                <th>Count</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Approved By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowedGallons as $gallon)
                <tr>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
