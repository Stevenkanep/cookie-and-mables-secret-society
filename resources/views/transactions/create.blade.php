@extends('layouts.app')

@section('title', 'New Transaction')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Membership Transaction</h1>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="member_id" class="form-label">Member</label>
                    <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                        <option value="">Choose a member</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected(old('member_id', $selectedMember) == $member->id)>
                                {{ $member->last_name }}, {{ $member->first_name }} ({{ $member->membership_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="membership_level_id" class="form-label">Membership level</label>
                    <select name="membership_level_id" id="membership_level_id"
                            class="form-select @error('membership_level_id') is-invalid @enderror" required>
                        <option value="">Choose a level</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}" @selected(old('membership_level_id') == $level->id)>
                                {{ $level->name }} - ₱{{ number_format($level->fee, 2) }} / {{ $level->validity_months }} month(s)
                            </option>
                        @endforeach
                    </select>
                    @error('membership_level_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Transaction type</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="application" @selected(old('type', 'application') === 'application')>New application</option>
                        <option value="renewal" @selected(old('type') === 'renewal')>Renewal</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks (optional)</label>
                    <textarea name="remarks" id="remarks" rows="3"
                              class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <p class="text-muted small">The amount is copied from the selected level's fee. The reference number is generated automatically.</p>

                <button type="submit" class="btn btn-dark">Create Transaction</button>
            </form>
        </div>
    </div>
@endsection