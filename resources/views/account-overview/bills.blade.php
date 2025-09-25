@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header">
            <h1 class="mb-4 text-uppercase">My Bills & Payments</h1>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'unpaid' ? 'active' : '' }}" href="{{ route('customer-payments.unpaid') }}">Unpaid</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'paid' ? 'active' : '' }}" href="{{ route('customer-payments.paid') }}">Paid</a>
            </li>
        </ul>

        <div>
            @if(count($bills) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Account</th>
                            <th>Amount</th>
                            <th>{{ $filter == 'unpaid' ? 'Due Date' : 'Date Paid' }}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $bill)
                        <tr>
                            <td>{{ $bill['reference_no'] }}</td>
                            <td>{{ $bill['account_no'] ?? '-' }}</td>
                            <td>₱{{ number_format($bill['amount'],2) }}</td>
                            <td>
                                {{ $filter == 'unpaid'
                                    ? \Carbon\Carbon::parse($bill['due_date'])->format('M d, Y')
                                    : \Carbon\Carbon::parse($bill['date_paid'])->format('M d, Y') }}
                            </td>
                            <td>
                                @if(!$bill['isPaid'])
                                    <a href="{{ route('customer-payments.pay', $bill['reference_no']) }}" class="btn btn-primary btn-sm">Pay Online</a>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#cashPaymentModal" data-ref="{{ $bill['reference_no'] }}" data-amount="{{ $bill['amount'] }}">Pay Cash</button>
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">No {{ $filter }} bills found.</div>
            @endif
        </div>
    </div>
</main>

{{-- Cash Payment Modal --}}
<div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="cashPaymentForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cash Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="reference_no" id="cashReference">
                    <div class="mb-3">
                        <label class="form-label">Payor Name</label>
                        <input type="text" class="form-control" name="payor" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" name="payment_amount" id="cashAmount" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
