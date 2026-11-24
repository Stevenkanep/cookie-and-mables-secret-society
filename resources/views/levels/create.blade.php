@extends('layouts.app')

@section('title', 'Add Level')

@section('content')
    <h1 class="h3 mb-3">Add Level</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('levels.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fee" class="form-label">Fee (₱)</label>
                        <input type="number" step="0.01" min="0" id="fee" name="fee" value="{{ old('fee', '0') }}"
                               class="form-control @error('fee') is-invalid @enderror" required>
                        @error('fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validity_months" class="form-label">Validity (months)</label>
                        <input type="number" min="1" id="validity_months" name="validity_months"
                               value="{{ old('validity_months', '12') }}"
                               class="form-control @error('validity_months') is-invalid @enderror" required>
                        @error('validity_months')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="benefits" class="form-label">Benefits</label>
                    <textarea id="benefits" name="benefits" rows="3"
                              class="form-control @error('benefits') is-invalid @enderror">{{ old('benefits') }}</textarea>
                    @error('benefits')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <button type="submit" class="btn btn-dark">Save</button>
                <a href="{{ route('levels.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection