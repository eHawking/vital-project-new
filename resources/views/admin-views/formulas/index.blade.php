@extends('layouts.admin.app')

@section('title', __('Formula List'))

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h1 mb-0 text-capitalize">{{ __('Formula List') }}</h2>
        <a href="{{ route('admin.formulas.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="tio-add-circle-outlined"></i> Create New Formula
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.formulas.index') }}" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search formulas" value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="tio-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Formulas Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Formula List</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        @foreach ($fields as $field)
                            <th>{{ ucwords(str_replace('_', ' ', $field)) }}</th>
                        @endforeach
                        <th>Update Count</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($formulas as $formula)
                        <tr>
                            <td>{{ $formula->id }}</td>
                            @foreach ($fields as $field)
                                <td>
                                    @if (is_bool($formula->$field))
                                        <span class="badge {{ $formula->$field ? 'bg-success' : 'bg-danger' }}">
                                            {{ $formula->$field ? 'Yes' : 'No' }}
                                        </span>
                                    @elseif ($formula->$field instanceof \Carbon\Carbon)
                                        {{ $formula->$field->format('Y-m-d') }}
                                    @else
                                        {{ $formula->$field }}
                                    @endif
                                </td>
                            @endforeach
                            <td><span class="badge bg-info">{{ $formula->histories()->count() }}</span></td>
                            <td>{{ $formula->updated_at ? $formula->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.formulas.show', $formula->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="tio-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.formulas.edit', $formula->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="tio-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.formulas.destroy', $formula->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this formula?')">
                                            <i class="tio-delete"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($fields) + 5 }}" class="text-center">No formulas found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $formulas->links() }}
    </div>
</div>
@endsection
