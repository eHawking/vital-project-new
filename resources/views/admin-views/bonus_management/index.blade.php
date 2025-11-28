@extends('layouts.admin.app')

@section('title', translate('Bonus Management'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-capitalize">{{ translate('Bonus Management') }}</h1>
        <a href="{{ route('admin.bonus-management.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="tio-add-circle-outlined"></i> {{ translate('Add Bonus') }}
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

	<!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bonus-management.index') }}" class="input-group">
                 <input type="text" name="search" class="form-control" placeholder="{{ translate('Search by bonus type or description') }}" value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="tio-search"></i> Search
                </button>
            </form>
        </div>
    </div>

  
    <!-- Bonus Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('Bonus List') }}</h5>
        </div>
        <div class="table-responsive">
            @if ($bonuses->isEmpty())
                <div class="alert alert-info m-3">
                    {{ translate('No bonuses found.') }}
                    <a href="{{ route('admin.bonus-management.create') }}">{{ translate('Click here to add a new bonus.') }}</a>
                </div>
            @else
                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('ID') }}</th>
                            <th>{{ translate('Bonus Type') }}</th>
                            <th>{{ translate('Description') }}</th>
                            <th>{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bonuses as $bonus)
                            <tr>
                                <td>{{ $bonus->id }}</td>
                                <td>{{ $bonus->bonus_type }}</td>
                                <td>{{ $bonus->description }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.bonus-management.edit', $bonus->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="tio-edit"></i> {{ translate('Edit') }}
                                        </a>
                                        <form action="{{ route('admin.bonus-management.destroy', $bonus->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ translate('Are you sure you want to delete this bonus?') }}')">
                                                <i class="tio-delete"></i> {{ translate('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $bonuses->links() }}
    </div>
</div>
@endsection
