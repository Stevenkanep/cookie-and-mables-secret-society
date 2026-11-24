@extends('layouts.app')

@section('title', 'Membership Levels')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Membership Levels</h1>
        <a href="{{ route('levels.create') }}" class="btn btn-dark">Add Level</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Fee</th>
                        <th>Validity</th>
                        <th>Benefits</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($levels as $level)
                        <tr>
                            <td>{{ $level->name }}</td>
                            <td>₱{{ number_format($level->fee, 2) }}</td>
                            <td>{{ $level->validity_months }} months</td>
                            <td>{{ $level->benefits ?: '-' }}</td>
                            <td>
                                @if ($level->is_active)
                                    <span class="badge text-bg-success">Active</span>
                                @else
                                    <span class="badge text-bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('levels.edit', $level) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                <form action="{{ route('levels.destroy', $level) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this level?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No levels yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection