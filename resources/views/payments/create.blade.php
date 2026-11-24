@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Record Payment</h1>
        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary">Back to Transaction</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Transaction Summary</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Reference</dt>
                        <dd class="col-sm-7">{{ $transaction->reference_number }}</dd>

                        <dt class="col-sm-5">Member</dt>
                        <dd class="col-sm-7">{{ $transaction->member->full_name }}</dd>

                        <dt class="col-sm-5">Level</dt>
                        <dd class="col-sm-7">{{ $transaction->level->name }}</dd>

                        <dt class="col-sm-5">Amount due</dt>
                        <dd class="col-sm-7">₱{{ number_format($transaction->amount, 2) }}</dd>

                        <dt class="col-sm-5">Already paid</dt>
                        <dd class="col-sm-7">₱{{ number_format((float) $transaction->amount_paid, 2) }}</dd>

                        <dt class="col-sm-5">Balance</dt>
                        <dd class="col-sm-7 fw-bold">₱{{ number_format((float) $transaction->balance, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.store', $transaction) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₱)</label>
                            <input type="number" step="0.01" min="0.01" max="{{ $transaction->balance }}"
                                   name="amount" id="amount"
                                   value="{{ old('amount', $transaction->balance) }}"
                                   class="form-control @error('amount') is-invalid @enderror" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can pay part of the balance now and the rest later.</div>
                        </div>

                        <div class="mb-3">
                            <label for="method" class="form-label">Payment method</label>
                            <select name="method" id="method" class="form-select @error('method') is-invalid @enderror" required>
                                <option value="">Choose a method</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method }}" @selected(old('method') === $method)>{{ $method }}</option>
                                @endforeach
                            </select>
                            @error('method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="paid_at" class="form-label">Date paid</label>
                            <input type="date" name="paid_at" id="paid_at"
                                   value="{{ old('paid_at', now()->toDateString()) }}"
                                   max="{{ now()->toDateString() }}"
                                   class="form-control @error('paid_at') is-invalid @enderror" required>
                            @error('paid_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optional)</label>
                            <input type="text" name="notes" id="notes" value="{{ old('notes') }}" maxlength="255"
                                   class="form-control @error('notes') is-invalid @enderror">
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark">Save Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection