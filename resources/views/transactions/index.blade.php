@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Membership Transactions</h1>
        <a href="{{ route('transactions.create') }}" class="btn btn-dark">New Transaction</a>
    </div>

    <form method="GET" action="{{ route('transactions.index') }}" class="card card-body shadow-sm mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                       placeholder="Search reference no., member name, or membership no.">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All types</option>
                    <option value="application" @selected(request('type') === 'application')>Application</option>
                    <option value="renewal" @selected(request('type') === 'renewal')>Renewal</option>
                </select>
            </div>
            <div class="col-md-2 d-grid d-md-flex gap-2">
                <button type="submit" class="btn btn-dark">Filter</button>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Member</th>
                        <th>Level</th>
                        <th>Type</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->reference_number }}</td>
                            <td>
                                {{ $transaction->member->full_name }}
                                <div class="small text-muted">{{ $transaction->member->membership_number }}</div>
                            </td>
                            <td>{{ $transaction->level->name }}</td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td class="text-end">₱{{ number_format($transaction->amount, 2) }}</td>
                            <td>@include('transactions._status', ['status' => $transaction->status])</td>
                            <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-dark">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
@endsection