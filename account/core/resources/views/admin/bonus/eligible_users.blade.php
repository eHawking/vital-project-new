@extends('admin.layouts.app')

@section('panel')
<style>
	/* Custom Search Input Background */
    .custom-search-input {
        background-color: #ffffff; 
    }

    /* Optional: Add background to input group */
    .custom-search-group {
        background-color: #ffffff; 
    }

   
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px; /* Reduced desktop width */
    }
	
	.progress.flex-grow-1 {
		margin-bottom: 0px;
	}

    .progress-col {
        min-width: 300px; /* Wider progress column */
    }

    @media (max-width: 767px) {
        .truncate {
            max-width: 100%; /* Full width on mobile */
            white-space: normal; /* Allow line break */
            overflow: visible;
            text-overflow: clip;
        }
		.progress.flex-grow-1 {
		margin-bottom: 5px;
	}

        .progress-col {
            min-width: 100%; /* Full width progress bar on mobile */
        }

        table.style--two tbody tr {
            border: 2px dashed #0000005c;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s ease-in-out;
        }

        table.style--two tbody tr:hover {
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.4);
            transition: box-shadow 0.3s ease-in-out;
        }
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Full Name')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Current Bonus')</th>
                                <th>@lang('Remaining Balance')</th>
                                <th class="progress-col">@lang('Progress to 2600')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eligibleUsers as $user)
                                @php
                                    $currentBonus = $user->royalty_bonus ?? 0;
                                    $maxBonus = 2600;
                                    $remaining = max(0, $maxBonus - $currentBonus);
                                    $percentage = min(100, ($currentBonus / $maxBonus) * 100);
                                @endphp
                                <tr>
                                    <!-- Full Name -->
                                    <td class="truncate" title="{{ $user->firstname }} {{ $user->lastname }}">
                                        <a href="{{ route('admin.users.detail', $user->id) }}" class="fw-bold text--info">
                                            {{ \Illuminate\Support\Str::limit($user->firstname . ' ' . $user->lastname, 25) }}
                                        </a>
                                    </td>

                                    <!-- Username -->
                                    <td>
                                        <a href="{{ route('admin.users.detail', $user->id) }}" class="fw-bold">
                                            {{ $user->username }}
                                        </a>
                                    </td>

                                    <!-- Current Bonus -->
                                    <td>
                                        <span class="fw-bold text--success">{{ number_format($currentBonus, 2) }}</span>
                                    </td>

                                    <!-- Remaining Balance -->
                                    <td>
                                        <span class="fw-bold text--danger">{{ number_format($remaining, 2) }}</span>
                                    </td>

                                    <!-- Progress Bar + Percentage -->
                                    <td class="progress-col">
                                        <div class="d-flex align-items-center">
                                            <!-- Progress Bar -->
                                            <div class="progress flex-grow-1" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $percentage >= 90 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success') }}"
                                                     role="progressbar"
                                                     style="width: {{ $percentage }}%;"
                                                     aria-valuenow="{{ $percentage }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>

                                            <!-- Percentage Text -->
                                            <span class="ms-2 fw-bold text-{{ $percentage >= 90 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success') }}">
                                                {{ number_format($percentage, 2) }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">
                                        {{ __('No eligible users found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($eligibleUsers->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($eligibleUsers) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{ route('admin.bonus.eligible_users') }}" method="GET" class="form-inline">
        <div class="input-group custom-search-group">
            <input type="text" name="search" class="form-control custom-search-input" placeholder="Search by full name, username..." value="{{ request('search') }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.bonus.eligible_users') }}" class="btn btn--danger input-group-text"><i class="fa fa-times"></i></a>
            @endif
        </div>
    </form>
@endpush