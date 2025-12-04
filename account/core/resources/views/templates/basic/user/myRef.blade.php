@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Color Picker (Right Sidebar) -->
@include($activeTemplate . 'partials.color-picker')

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <div class="referral-wrapper">

        <!-- Referral Stats Cards -->
        <div class="stats-grid">
            
            <!-- Total Referrals -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Total Referrals')</h6>
                    <h3 id="stat-total">{{ $logs->total() }}</h3>
                </div>
                <div class="icon-box variant-blue mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>

            <!-- Active DSP -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Active DSP')</h6>
                    <h3 id="stat-dsp">{{ $logs->where('plan_id', 1)->count() }}</h3>
                </div>
                <div class="icon-box variant-green mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>

            <!-- Active BTP -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Active BTP')</h6>
                    <h3 id="stat-btp">{{ $logs->where('is_btp', 1)->count() }}</h3>
                </div>
                <div class="icon-box variant-purple mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                </div>
            </div>

            <!-- This Month -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('This Month')</h6>
                    <h3 id="stat-month">{{ $logs->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
                <div class="icon-box variant-orange mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
            </div>
        </div>

        <!-- Referrals Table Card -->
        <div class="premium-card table-card">
            <div class="card-header-premium d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <h5 class="mb-0">@lang('Referral List')</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">@lang('Page') {{ $logs->currentPage() }} @lang('of') {{ $logs->lastPage() }}</span>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div id="table-loading" class="table-loading" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="table-responsive" id="referral-table-container">
                <table class="table table-custom" id="referral-table">
                    <thead>
                        <tr>
                            <th class="text-muted">@lang('No.')</th>
                            <th class="text-muted">@lang('Username')</th>
                            <th class="text-muted">@lang('Name')</th>
                            <th class="text-muted">@lang('Email')</th>
                            <th class="text-muted">@lang('DSP')</th>
                            <th class="text-muted">@lang('BTP')</th>
                            <th class="text-muted">@lang('Join Date')</th>
                        </tr>
                    </thead>
                    <tbody id="referral-tbody">
                        @php
                            $currentPage = $logs->currentPage();
                            $perPage = $logs->perPage();
                            $totalRecords = $logs->total();
                            $startNumber = $totalRecords - (($currentPage - 1) * $perPage);
                        @endphp
                        @forelse($logs as $key => $data)
                            <tr>
                                <td data-label="@lang('No.')"><span class="row-number">{{ $startNumber - $key }}</span></td>
                                <td data-label="@lang('Username')">
                                    <strong class="text-primary">{{ $data->username }}</strong>
                                </td>
                                <td data-label="@lang('Name')">{{ $data->fullname }}</td>
                                <td data-label="@lang('Email')">
                                    <small class="text-muted">{{ $data->email }}</small>
                                </td>
                                <td data-label="@lang('DSP')">
                                    @if($data->plan_id == 1)
                                        <span class="badge badge-premium badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-premium badge-danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('BTP')">
                                    @if($data->is_btp == 1)
                                        <span class="badge badge-premium badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-premium badge-warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Join Date')">
                                    <div class="date-cell">
                                        <span>{{ showDateTime($data->created_at, 'd M Y') }}</span>
                                        <small class="text-muted">{{ $data->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center py-5" colspan="7">
                                    <div class="empty-state">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="opacity: 0.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                        <p class="mt-3 mb-0">@lang('No referrals found')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Premium Pagination -->
            @if ($logs->hasPages())
            <div class="premium-pagination-wrapper">
                <div class="pagination-info">
                    <span class="text-muted small">
                        @lang('Showing') {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} @lang('of') {{ $logs->total() }}
                    </span>
                </div>

                <div class="pagination-controls">
                    <!-- Previous Button -->
                    <button class="pagination-btn {{ $logs->onFirstPage() ? 'disabled' : '' }}" 
                            onclick="goToPage({{ $logs->currentPage() - 1 }})" 
                            {{ $logs->onFirstPage() ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>

                    <!-- Page Numbers (Desktop) -->
                    <div class="page-numbers d-none d-md-flex">
                        @php
                            $start = max(1, $logs->currentPage() - 2);
                            $end = min($logs->lastPage(), $logs->currentPage() + 2);
                        @endphp
                        
                        @if($start > 1)
                            <button class="pagination-btn" onclick="goToPage(1)">1</button>
                            @if($start > 2)
                                <span class="pagination-ellipsis">...</span>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            <button class="pagination-btn {{ $i == $logs->currentPage() ? 'active' : '' }}" onclick="goToPage({{ $i }})">{{ $i }}</button>
                        @endfor

                        @if($end < $logs->lastPage())
                            @if($end < $logs->lastPage() - 1)
                                <span class="pagination-ellipsis">...</span>
                            @endif
                            <button class="pagination-btn" onclick="goToPage({{ $logs->lastPage() }})">{{ $logs->lastPage() }}</button>
                        @endif
                    </div>

                    <!-- Current Page (Mobile) -->
                    <div class="current-page-mobile d-md-none">
                        <span class="pagination-btn active">{{ $logs->currentPage() }} / {{ $logs->lastPage() }}</span>
                    </div>

                    <!-- Next Button -->
                    <button class="pagination-btn {{ !$logs->hasMorePages() ? 'disabled' : '' }}" 
                            onclick="goToPage({{ $logs->currentPage() + 1 }})" 
                            {{ !$logs->hasMorePages() ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>

                <!-- Go To Page -->
                <div class="goto-page">
                    <div class="goto-page-input-group">
                        <input type="number" 
                               id="gotoPageInput" 
                               class="goto-page-input" 
                               placeholder="@lang('Page')" 
                               min="1" 
                               max="{{ $logs->lastPage() }}"
                               onkeypress="if(event.key === 'Enter') goToPageInput()">
                        <button class="goto-page-btn" onclick="goToPageInput()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            @lang('Go')
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<style>
    /* ========================================
       PREMIUM REFERRALS PAGE STYLES
       ======================================== */

    .referral-wrapper {
        max-width: 100%;
    }

    /* Stats Grid - Dashboard Style */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
    }

    .stat-info h6 {
        font-size: 12px;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-info h3 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary) !important;
    }

    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-box svg {
        color: #fff;
    }

    .icon-box.variant-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .icon-box.variant-green { background: linear-gradient(135deg, #10b981, #059669); }
    .icon-box.variant-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .icon-box.variant-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

    /* Table Card */
    .table-card {
        position: relative;
        overflow: hidden;
    }

    .card-header-premium h5 {
        color: var(--text-primary) !important;
        font-weight: 600;
    }

    /* Loading Overlay */
    .table-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(var(--bg-card-rgb, 30, 30, 40), 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: inherit;
    }

    /* Table Styling */
    .table-custom {
        color: var(--text-primary) !important;
        margin-bottom: 0;
    }

    .table-custom th {
        background: transparent !important;
        border: none;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted) !important;
        white-space: nowrap;
    }

    .table-custom td {
        background: transparent !important;
        border: none;
        padding: 16px;
        vertical-align: middle;
        color: var(--text-primary) !important;
        border-bottom: 1px solid rgba(128, 128, 128, 0.1);
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background: rgba(128, 128, 128, 0.05) !important;
    }

    .row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(128, 128, 128, 0.1);
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    /* Premium Badges */
    .badge-premium {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* Empty State */
    .empty-state {
        padding: 40px 20px;
    }

    .empty-state svg {
        color: var(--text-muted);
    }

    /* ========================================
       PREMIUM PAGINATION STYLES
       ======================================== */

    .premium-pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid rgba(128, 128, 128, 0.1);
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-numbers {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background: rgba(128, 128, 128, 0.1);
        border: 1px solid rgba(128, 128, 128, 0.2);
        border-radius: 10px;
        color: var(--text-primary);
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pagination-btn:hover:not(.disabled):not(.active) {
        background: rgba(var(--rgb-primary), 0.15);
        border-color: var(--color-primary);
        color: var(--color-primary);
        transform: translateY(-2px);
    }

    .pagination-btn.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 15px rgba(var(--rgb-primary), 0.4);
    }

    .pagination-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .pagination-ellipsis {
        padding: 0 8px;
        color: var(--text-muted);
    }

    /* Go To Page */
    .goto-page-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .goto-page-input {
        width: 70px;
        height: 40px;
        padding: 0 12px;
        background: rgba(128, 128, 128, 0.1);
        border: 1px solid rgba(128, 128, 128, 0.2);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 14px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .goto-page-input:focus {
        outline: none;
        border-color: var(--color-primary);
        background: rgba(var(--rgb-primary), 0.1);
    }

    .goto-page-input::placeholder {
        color: var(--text-muted);
    }

    .goto-page-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 40px;
        padding: 0 16px;
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .goto-page-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(var(--rgb-primary), 0.4);
    }

    /* Headings */
    h1, h2, h3, h4, h5, h6, .premium-title {
        color: var(--text-primary) !important;
    }

    /* ========================================
       MOBILE RESPONSIVENESS
       ======================================== */

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        /* Container Reset */
        .container-fluid.px-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .inner-dashboard-container {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        /* Stats Grid Mobile */
        .stats-grid {
            grid-template-columns: 1fr !important;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-item {
            width: 100% !important;
            padding: 16px;
        }

        .stat-info h3 {
            font-size: 24px;
        }

        .icon-box {
            width: 45px;
            height: 45px;
        }

        /* Premium Card Mobile */
        .premium-card {
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
            margin-left: -10px;
            margin-right: -10px;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Table Mobile - Card Layout */
        .table-custom thead {
            display: none;
        }

        .table-custom tbody tr {
            display: block;
            margin-bottom: 16px;
            padding: 16px;
            background: rgba(128, 128, 128, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(128, 128, 128, 0.1);
        }

        .table-custom tbody tr:hover {
            background: rgba(128, 128, 128, 0.08) !important;
        }

        .table-custom td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(128, 128, 128, 0.1);
        }

        .table-custom td:last-child {
            border-bottom: none;
        }

        .table-custom td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
        }

        .row-number {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .date-cell {
            text-align: right;
        }

        /* Pagination Mobile */
        .premium-pagination-wrapper {
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .pagination-info {
            order: 3;
        }

        .pagination-controls {
            order: 1;
            width: 100%;
            justify-content: center;
        }

        .goto-page {
            order: 2;
            width: 100%;
        }

        .goto-page-input-group {
            justify-content: center;
        }

        .goto-page-input {
            flex: 1;
            max-width: 100px;
        }

        .goto-page-btn {
            flex: 1;
            justify-content: center;
        }
    }

    @media (max-width: 400px) {
        .pagination-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            font-size: 13px;
        }

        .goto-page-input,
        .goto-page-btn {
            height: 36px;
        }
    }
</style>

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<script>
    // AJAX Pagination Functions
    const totalPages = {{ $logs->lastPage() }};
    const currentUrl = "{{ route('user.my.ref') }}";

    function goToPage(page) {
        if (page < 1 || page > totalPages) return;
        loadPage(page);
    }

    function goToPageInput() {
        const input = document.getElementById('gotoPageInput');
        const page = parseInt(input.value);
        
        if (isNaN(page) || page < 1 || page > totalPages) {
            // Show error feedback
            input.style.borderColor = '#ef4444';
            input.style.animation = 'shake 0.5s';
            setTimeout(() => {
                input.style.borderColor = '';
                input.style.animation = '';
            }, 500);
            return;
        }
        
        loadPage(page);
        input.value = '';
    }

    function loadPage(page) {
        const loading = document.getElementById('table-loading');
        const container = document.getElementById('referral-table-container');
        
        // Show loading
        loading.style.display = 'flex';
        
        // Navigate to page (standard navigation for now - can be upgraded to full AJAX)
        window.location.href = currentUrl + '?page=' + page;
    }

    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // Highlight current page input on focus
    document.getElementById('gotoPageInput')?.addEventListener('focus', function() {
        this.select();
    });
</script>
@endpush