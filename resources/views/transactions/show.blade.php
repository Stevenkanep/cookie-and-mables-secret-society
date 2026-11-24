@extends('layouts.app')

@section('title', 'Transaction ' . $transaction->reference_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Transaction {{ $transaction->reference_number }}</h1>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Transaction Details</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">@include('transactions._status', ['status' => $transaction->status])</dd>

                        <dt class="col-sm-4">Member</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('members.show', $transaction->member) }}">{{ $transaction->member->full_name }}</a>
                            ({{ $transaction->member->membership_number }})
                        </dd>

                        <dt class="col-sm-4">Level</dt>
                        <dd class="col-sm-8">{{ $transaction->level->name }}</dd>

                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">{{ ucfirst($transaction->type) }}</dd>

                        <dt class="col-sm-4">Amount</dt>
                        <dd class="col-sm-8">₱{{ number_format($transaction->amount, 2) }}</dd>

                        <dt class="col-sm-4">Amount paid</dt>
                        <dd class="col-sm-8">₱{{ number_format((float) $transaction->amount_paid, 2) }}</dd>

                        <dt class="col-sm-4">Balance</dt>
                        <dd class="col-sm-8">₱{{ number_format((float) $transaction->balance, 2) }}</dd>

                        <dt class="col-sm-4">Membership period</dt>
                        <dd class="col-sm-8">
                            @if ($transaction->start_date)
                                {{ $transaction->start_date->format('M d, Y') }} to {{ $transaction->end_date->format('M d, Y') }}
                            @else
                                -
                            @endif
                        </dd>

                        <dt class="col-sm-4">Remarks</dt>
                        <dd class="col-sm-8">{{ $transaction->remarks ?: '-' }}</dd>

                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $transaction->created_at->format('M d, Y h:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Actions</div>
                <div class="card-body d-grid gap-2 align-content-start">
                    @if ($transaction->status === 'pending')
                        <a href="{{ route('payments.create', $transaction) }}" class="btn btn-dark">Record Payment</a>
                    @endif

                    @if ($transaction->status === 'paid')
                        <form action="{{ route('transactions.approve', $transaction) }}" method="POST"
                              onsubmit="return confirm('Approve this transaction and activate the membership?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Approve Transaction</button>
                        </form>
                    @endif

                    @if ($transaction->status === 'pending' && $transaction->payments->isEmpty())
                        <form action="{{ route('transactions.cancel', $transaction) }}" method="POST"
                              onsubmit="return confirm('Cancel this transaction?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">Cancel Transaction</button>
                        </form>
                    @endif

                    <a href="{{ route('members.transactions', $transaction->member) }}" class="btn btn-outline-dark">
                        View Member History
                    </a>

                    @if (in_array($transaction->status, ['approved', 'expired', 'cancelled']))
                        <p class="text-muted small mb-0">This transaction is closed, so no further actions are available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-white fw-semibold">Payments</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Receipt No.</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th class="text-end">Amount</th>
                        <th>Notes</th>
                        <th class="text-end">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaction->payments as $payment)
                        <tr>
                            <td>{{ $payment->receipt_number }}</td>
                            <td>{{ $payment->paid_at->format('M d, Y') }}</td>
                            <td>{{ $payment->method }}</td>
                            <td class="text-end">₱{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->notes ?: '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-dark">Print</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No payments recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection