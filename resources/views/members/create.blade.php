@extends('layouts.app')

@section('title', 'Register Member')

@section('content')
    <h1 class="h3 mb-3">Register Member</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('members.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                               class="form-control @error('first_name') is-invalid @enderror" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                               class="form-control @error('last_name') is-invalid @enderror" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}"
                           class="form-control @error('address') is-invalid @enderror">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}"
                               class="form-control @error('birthdate') is-invalid @enderror">
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="joined_at" class="form-label">Date joined</label>
                        <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', now()->toDateString()) }}"
                               class="form-control @error('joined_at') is-invalid @enderror" required>
                        @error('joined_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="membership_category_id" class="form-label">Category</label>
                        <select id="membership_category_id" name="membership_category_id"
                                class="form-select @error('membership_category_id') is-invalid @enderror" required>
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('membership_category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('membership_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="membership_level_id" class="form-label">Level</label>
                        <select id="membership_level_id" name="membership_level_id"
                                class="form-select @error('membership_level_id') is-invalid @enderror" required>
                            <option value="">Select level</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}" @selected(old('membership_level_id') == $level->id)>{{ $level->name }} (₱{{ number_format($level->fee, 2) }})</option>
                            @endforeach
                        </select>
                        @error('membership_level_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-dark">Register</button>
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection