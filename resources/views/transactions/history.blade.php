@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Transaction History</h1>
            <div class="text-muted">
                {{ $member->full_name }} ({{ $member->membership_number }})
                @if ($member->category) - {{ $member->category->name }} @endif
                @if ($member->level) - {{ $member->level->name }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.create', ['member' => $member->id]) }}" class="btn btn-dark">New Transaction</a>
            <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">Back to Member</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Level</th>
                        <th>Type</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th>Membership Period</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->reference_number }}</td>
                            <td>{{ $transaction->level->name }}</td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td class="text-end">₱{{ number_format($transaction->amount, 2) }}</td>
                            <td>@include('transactions._status', ['status' => $transaction->status])</td>
                            <td>
                                @if ($transaction->start_date)
                                    {{ $transaction->start_date->format('M d, Y') }} to {{ $transaction->end_date->format('M d, Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-dark">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">This member has no transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection