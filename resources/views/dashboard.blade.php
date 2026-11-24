@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-3">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Members</div>
                    <div class="display-6 fw-bold">{{ $totalMembers }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Active Members</div>
                    <div class="display-6 fw-bold text-success">{{ $activeMembers }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Pending Transactions</div>
                    <div class="display-6 fw-bold text-warning">{{ $pendingTransactions }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Collected Payments</div>
                    <div class="display-6 fw-bold">₱{{ number_format($collectedPayments, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            @if ($weather)
                @if ($weather['icon'])
                    <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png" alt="" width="64" height="64">
                @endif
                <div>
                    <div class="text-muted small">Current weather in {{ $weather['place'] }} (OpenWeather)</div>
                    <div class="fs-4 fw-bold">
                        {{ round($weather['temperature']) }}°C
                        <span class="fs-6 fw-normal text-capitalize">{{ $weather['description'] }}</span>
                    </div>
                    <div class="small text-muted">Humidity {{ $weather['humidity'] }}%</div>
                </div>
            @else
                <div class="text-muted">Weather is currently unavailable.</div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Recent Transactions</span>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-dark">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Member</th>
                        <th>Level</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->reference_number }}</td>
                            <td>{{ $transaction->member->full_name }}</td>
                            <td>{{ $transaction->level->name }}</td>
                            <td class="text-end">₱{{ number_format($transaction->amount, 2) }}</td>
                            <td>@include('transactions._status', ['status' => $transaction->status])</td>
                            <td class="text-end">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-dark">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection