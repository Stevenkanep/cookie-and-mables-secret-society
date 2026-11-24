@extends('layouts.app')

@section('title', $member->full_name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Member Profile</h1>
        <div class="d-flex flex-wrap gap-2">
            @if ($member->status === 'active')
                <a href="{{ route('transactions.create', ['member' => $member->id]) }}" class="btn btn-success">New Transaction</a>
            @endif
            <a href="{{ route('members.transactions', $member) }}" class="btn btn-outline-dark">Transaction History</a>
            <a href="{{ route('members.edit', $member) }}" class="btn btn-dark">Edit</a>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Back to list</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h4">{{ $member->full_name }}
                @if ($member->status === 'active')
                    <span class="badge text-bg-success fs-6">Active</span>
                @else
                    <span class="badge text-bg-secondary fs-6">Inactive</span>
                @endif
            </h2>
            <p class="text-muted">{{ $member->membership_number }}</p>

            <dl class="row mb-0">
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $member->email }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $member->phone ?: '-' }}</dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">{{ $member->address ?: '-' }}</dd>

                <dt class="col-sm-3">Birthdate</dt>
                <dd class="col-sm-9">{{ $member->birthdate?->format('F j, Y') ?: '-' }}</dd>

                <dt class="col-sm-3">Category</dt>
                <dd class="col-sm-9">{{ $member->category->name }}</dd>

                <dt class="col-sm-3">Level</dt>
                <dd class="col-sm-9">{{ $member->level->name }} (₱{{ number_format($member->level->fee, 2) }}, {{ $member->level->validity_months }} months)</dd>

                <dt class="col-sm-3">Date joined</dt>
                <dd class="col-sm-9">{{ $member->joined_at->format('F j, Y') }}</dd>
            </dl>
        </div>
    </div>
@endsection