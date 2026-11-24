@extends('layouts.app')

@section('title', 'Members')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Members</h1>
        <a href="{{ route('members.create') }}" class="btn btn-dark">Register Member</a>
    </div>

    <form method="GET" action="{{ route('members.index') }}" class="card card-body shadow-sm mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                       placeholder="Search name, email or membership no.">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="level" class="form-select">
                    <option value="">All levels</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" @selected(request('level') == $level->id)>{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid d-md-flex gap-2">
                <button type="submit" class="btn btn-dark">Search</button>
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Membership No.</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $member->membership_number }}</td>
                            <td>{{ $member->full_name }}</td>
                            <td>{{ $member->category->name }}</td>
                            <td>{{ $member->level->name }}</td>
                            <td>
                                @if ($member->status === 'active')
                                    <span class="badge text-bg-success">Active</span>
                                @else
                                    <span class="badge text-bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this member?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $members->links('pagination::bootstrap-5') }}
    </div>
@endsection