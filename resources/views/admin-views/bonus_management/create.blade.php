@extends('layouts.admin.app')

@section('title', translate('Add Bonus'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-capitalize">{{ translate('Add Bonus') }}</h1>
        <a href="{{ route('admin.bonus-management.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
            <i class="tio-arrow-back"></i> {{ translate('Back to List') }}
        </a>
    </div>

    <!-- Add Bonus Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ translate('Create New Bonus') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bonus-management.store') }}" method="POST">
                @csrf

                <!-- Bonus Type -->
                <div class="mb-3">
                    <label for="bonus_type" class="form-label">{{ translate('Bonus Type') }}</label>
                    <input type="text" name="bonus_type" id="bonus_type" class="form-control" 
                           placeholder="{{ translate('Enter bonus type') }}" value="{{ old('bonus_type') }}" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">{{ translate('Description') }}</label>
                    <textarea name="description" id="description" class="form-control" rows="4" 
                              placeholder="{{ translate('Enter bonus description') }}" required>{{ old('description') }}</textarea>
                </div>

                <!-- SQL Statement -->
                <div class="mb-3">
                    <label for="statement" class="form-label">{{ translate('SQL Statement') }}</label>
                    <textarea name="statement" id="statement" class="form-control" rows="4" 
                              placeholder="{{ translate('Enter SQL statement') }}" required>{{ old('statement') }}</textarea>
                    <small class="form-text text-muted">
                        {{ translate('Use placeholders like {userId} and {amount}.') }}
                    </small>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="tio-add-circle-outlined"></i> {{ translate('Add Bonus') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
