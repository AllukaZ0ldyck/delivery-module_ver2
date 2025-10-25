@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header">
            <h1 class="mb-4 text-uppercase">Bill Details</h1>
        </div>

        <div>
            <h4>Reference No: {{ $billData['current_bill']['reference_no'] }}</h4>
            <p>Account No: {{ $billData['client']['account_no'] }}</p>
            <p>Amount: ₱{{ number_format($billData['current_bill']['amount'], 2) }}</p>
            <p>Due Date: {{ \Carbon\Carbon::parse($billData['current_bill']['due_date'])->format('M d, Y') }}</p>

            <h5>Previous Payment: ₱{{ number_format($billData['previous_payment']['amount'], 2) }}</h5>

            <h5>Unpaid Amount: ₱{{ number_format($billData['unpaid_bills']->sum('amount'), 2) }}</h5>

            <a href="{{ route('customer-payments.pay', $billData['current_bill']['reference_no']) }}" class="btn btn-primary">Pay Bill</a>
        </div>
    </div>
</main>
@endsection
