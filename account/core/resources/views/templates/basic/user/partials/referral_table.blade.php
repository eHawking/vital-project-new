<!-- Desktop Table View -->
<div class="table-responsive d-none d-md-block">
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
                    <td class="fw-bold">{{ $startNumber - $key }}</td>
                    <td><strong class="text-primary">{{ $data->username }}</strong></td>
                    <td>{{ $data->fullname }}</td>
                    <td><small class="text-muted">{{ $data->email }}</small></td>
                    <td>
                        @if($data->plan_id == 1)
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Active')</span>
                        @else
                            <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill"><i class="bi bi-x-circle-fill"></i> @lang('Inactive')</span>
                        @endif
                    </td>
                    <td>
                        @if($data->is_btp == 1)
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-star-fill"></i> @lang('Active')</span>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-dash-circle-fill"></i> @lang('Inactive')</span>
                        @endif
                    </td>
                    <td>
                        <i class="bi bi-calendar3 text-muted"></i> {{ showDateTime($data->created_at, 'd M Y') }}
                        <br><small class="text-muted">{{ $data->created_at->diffForHumans() }}</small>
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

<!-- Mobile Card View -->
<div class="d-block d-md-none">
    @php
        $currentPage = $logs->currentPage();
        $perPage = $logs->perPage();
        $totalRecords = $logs->total();
        $startNumber = $totalRecords - (($currentPage - 1) * $perPage);
    @endphp
    @forelse($logs as $key => $data)
        <div class="mobile-card-item">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="text-muted small">#{{ $startNumber - $key }}</span>
                    <h6 class="mb-0 text-primary fw-bold">{{ $data->username }}</h6>
                    <small class="text-muted">{{ $data->fullname }}</small>
                </div>
                <div class="text-end">
                    @if($data->plan_id == 1)
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill mb-1"><i class="bi bi-check-circle-fill"></i> DSP</span>
                    @else
                        <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill mb-1"><i class="bi bi-x-circle-fill"></i> DSP</span>
                    @endif
                    <br>
                    @if($data->is_btp == 1)
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-star-fill"></i> BTP</span>
                    @else
                        <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-dash-circle-fill"></i> BTP</span>
                    @endif
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="bi bi-envelope"></i> {{ $data->email }}</small>
                <small class="text-muted"><i class="bi bi-calendar3"></i> {{ showDateTime($data->created_at, 'd M Y') }}</small>
            </div>
        </div>
    @empty
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
            {{ __($emptyMessage ?? 'No referrals found') }}
        </div>
    @endforelse
</div>

<!-- Premium Pagination -->
@if ($logs->hasPages())
    <div class="premium-pagination mt-4">
        <div class="d-flex justify-content-center align-items-center gap-2">
            {{-- Previous --}}
            @if ($logs->onFirstPage())
                <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
            @else
                <a href="{{ $logs->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
            @endif

            {{-- Page Numbers --}}
            @php
                $currentPage = $logs->currentPage();
                $lastPage = $logs->lastPage();
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
            @endphp

            @if($start > 1)
                <a href="{{ $logs->url(1) }}" class="page-btn">1</a>
                @if($start > 2)
                    <span class="page-dots">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                <a href="{{ $logs->url($i) }}" class="page-btn {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
            @endfor

            @if($end < $lastPage)
                @if($end < $lastPage - 1)
                    <span class="page-dots">...</span>
                @endif
                <a href="{{ $logs->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
            @endif

            {{-- Next --}}
            @if ($logs->hasMorePages())
                <a href="{{ $logs->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
        <div class="text-center mt-2">
            <small class="text-muted">@lang('Page') {{ $logs->currentPage() }} @lang('of') {{ $logs->lastPage() }} ({{ $logs->total() }} @lang('items'))</small>
        </div>
    </div>
@endif

<style>
    /* Mobile Card Item */
    .mobile-card-item {
        background: var(--bg-card);
        border: 1px solid rgba(128,128,128,0.1);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
    }
    
    /* Premium Pagination */
    .premium-pagination .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 8px;
        background: rgba(128,128,128,0.1);
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .premium-pagination .page-btn:hover:not(.disabled):not(.active) {
        background: rgba(var(--rgb-primary), 0.2);
        color: var(--color-primary);
    }
    .premium-pagination .page-btn.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        color: #fff;
        box-shadow: 0 4px 10px rgba(var(--rgb-primary), 0.3);
    }
    .premium-pagination .page-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    .premium-pagination .page-dots {
        color: var(--text-muted);
        padding: 0 5px;
    }
</style>
