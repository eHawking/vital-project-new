<div class="table-responsive">
    <table class="table table-custom">
        <thead>
            <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                <th class="text-muted"><i class="bi bi-hash"></i> @lang('No.')</th>
                <th class="text-muted"><i class="bi bi-person-badge"></i> @lang('Username')</th>
                <th class="text-muted"><i class="bi bi-person"></i> @lang('Name')</th>
                <th class="text-muted"><i class="bi bi-envelope"></i> @lang('Email')</th>
                <th class="text-muted"><i class="bi bi-shield-check"></i> @lang('DSP')</th>
                <th class="text-muted"><i class="bi bi-award"></i> @lang('BTP')</th>
                <th class="text-muted"><i class="bi bi-calendar-event"></i> @lang('Join Date')</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentPage = $logs->currentPage();
                $perPage = $logs->perPage();
                $totalRecords = $logs->total();
                $startNumber = $totalRecords - (($currentPage - 1) * $perPage);
            @endphp
            @forelse($logs as $key => $data)
                <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                    <td data-label="@lang('No.')" class="fw-bold">{{ $startNumber - $key }}</td>
                    <td data-label="@lang('Username')">
                        <strong class="text-primary">{{ $data->username }}</strong>
                    </td>
                    <td data-label="@lang('Name')">{{ $data->fullname }}</td>
                    <td data-label="@lang('Email')">
                        <small class="text-muted">{{ $data->email }}</small>
                    </td>
                    <td data-label="@lang('DSP')">
                        @if($data->plan_id == 1)
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Active')</span>
                        @else
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill"><i class="bi bi-x-circle-fill"></i> @lang('Inactive')</span>
                        @endif
                    </td>
                    <td data-label="@lang('BTP')">
                        @if($data->is_btp == 1)
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-star-fill"></i> @lang('Active')</span>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill"><i class="bi bi-dash-circle-fill"></i> @lang('Inactive')</span>
                        @endif
                    </td>
                    <td data-label="@lang('Join Date')">
                        <div>
                            <i class="bi bi-calendar3 text-muted"></i> {{ showDateTime($data->created_at, 'd M Y') }}
                        </div>
                        <small class="text-muted">{{ $data->created_at->diffForHumans() }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center py-4" colspan="7">
                        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                        {{ __($emptyMessage ?? 'No referrals found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($logs->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <div class="pagination-wrapper">
            {{ paginateLinks($logs) }}
        </div>
    </div>
@endif
