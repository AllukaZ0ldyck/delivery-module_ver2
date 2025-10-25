@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sales Report</h2>

    <form action="{{ route('admin.sales-report') }}" method="GET" class="mb-3">
        <select name="date_range" class="form-select w-auto">
            <option value="daily" @if($dateRange == 'daily') selected @endif>Daily</option>
            <option value="weekly" @if($dateRange == 'weekly') selected @endif>Weekly</option>
            <option value="monthly" @if($dateRange == 'monthly') selected @endif>Monthly</option>
        </select>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>

    <div>
        <h3>Total Sales: ₱{{ number_format($sales, 2) }}</h3>
    </div>
</div>
@endsection
