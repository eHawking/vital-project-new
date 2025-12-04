@extends($activeTemplate . 'layouts.master')
@section('content')
    @php $general = gs(); @endphp
    
    <!-- Include Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <!-- Include Color Picker (Right Sidebar) -->
    @include($activeTemplate . 'partials.color-picker')

    <style>
        /* Mobile Full Width Adjustments to match dashboard */
        @media (max-width: 768px) {
            /* Force Inner Container to have ZERO padding */
            .inner-dashboard-container,
            .container-fluid.px-4 {
                padding-left: 0 !important;
                padding-right: 0 !important;
                padding-top: 10px !important;
                padding-bottom: 10px !important;
                width: 100% !important;
                margin: 0 !important;
            }

            /* Reset Grid to Stack */
            .stats-grid {
                grid-template-columns: 1fr !important;
                display: flex !important;
                flex-direction: column;
                gap: 15px;
                width: 100% !important;
                margin-bottom: 20px !important;
            }
            
            /* Force Cards Full Width */
            .stat-item, .premium-card {
                width: 100% !important;
                margin-bottom: 10px;
                border-radius: 12px !important; /* Keep rounded corners */
            }

            /* Fix Wrapper */
            .bright-future-wrapper {
                width: 100% !important;
                padding: 0 !important;
            }

            /* Ensure rows don't cause overflow */
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        /* Desktop Styling Preserved */
        .table-custom {
            color: var(--text-primary) !important;
        }
        .table-custom th, .table-custom td {
            background: transparent !important;
            vertical-align: middle;
            padding: 15px;
            color: inherit !important;
            white-space: nowrap;
        }
        h1, h2, h3, h4, h5, h6, .premium-title {
            color: var(--text-primary) !important;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        }
    </style>

    <div class="container-fluid px-4 py-4 inner-dashboard-container">
        <div class="bright-future-wrapper">

            <!-- Stats Grid -->
            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
                
                <!-- Total Received -->
                <div class="premium-card stat-item">
                    <div class="stat-info">
                        <h6 class="text-muted text-uppercase">@lang('Total Received')</h6>
                        <h3>{{ showAmount($receivedAmount, 2, currencyFormat: false) }} {{ $general->cur_text }}</h3>
                        <small class="text-success">@lang('From Daily Profit')</small>
                    </div>
                    <div class="icon-box variant-green mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                <!-- Remaining Cap -->
                <div class="premium-card stat-item">
                    <div class="stat-info">
                        <h6 class="text-muted text-uppercase">@lang('Remaining Cap')</h6>
                        <h3>{{ showAmount($remainingAmount, 2, currencyFormat: false) }} {{ $general->cur_text }}</h3>
                        <small class="text-warning">@lang('Until Max Cap Reached')</small>
                    </div>
                    <div class="icon-box variant-orange mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                <!-- Target Goal -->
                <div class="premium-card stat-item">
                    <div class="stat-info">
                        <h6 class="text-muted text-uppercase">@lang('Target Goal')</h6>
                        <h3>{{ showAmount($maxCap, 2, currencyFormat: false) }} {{ $general->cur_text }}</h3>
                        <small class="text-primary">@lang('Maximum Earnings')</small>
                    </div>
                    <div class="icon-box variant-purple mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="premium-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">@lang('Plan Progress')</h5>
                    <span class="badge bg-primary">{{ round($progress, 1) }}%</span>
                </div>
                
                <div class="progress" style="height: 12px; background: rgba(128,128,128,0.1); border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-primary" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <p class="mt-3 text-muted small mb-0">
                    @lang('You have received') <strong>{{ showAmount($receivedAmount, 2, currencyFormat: false) }} {{ $general->cur_text }}</strong> @lang('out of') <strong>{{ showAmount($maxCap, 2, currencyFormat: false) }} {{ $general->cur_text }}</strong>. @lang('Daily profit will stop automatically once 100% is reached.')
                </p>
            </div>

            <!-- History Table -->
            <div class="premium-card">
                <h5 class="mb-4">@lang('Profit History')</h5>
                
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-custom">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                                <th class="text-muted">@lang('Transaction ID')</th>
                                <th class="text-muted">@lang('Date')</th>
                                <th class="text-muted">@lang('Amount')</th>
                                <th class="text-muted">@lang('Details')</th>
                                <th class="text-muted">@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                                    <td class="fw-bold">#{{ $trx->trx }}</td>
                                    <td>
                                        <span>{{ showDateTime($trx->created_at, 'Y-m-d') }}</span>
                                        <br><small class="text-muted">{{ showDateTime($trx->created_at, 'h:i A') }}</small>
                                    </td>
                                    <td class="fw-bold text-success">+{{ showAmount($trx->amount, currencyFormat: false) }} {{ $general->cur_text }}</td>
                                    <td class="text-muted">{{ __($trx->details) }}</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">@lang('Received')</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">@lang('No daily profit received yet.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @forelse($transactions as $trx)
                        <div class="mobile-card-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="text-muted small">#{{ $trx->trx }}</span>
                                    <h6 class="mb-0 text-success fw-bold">+{{ showAmount($trx->amount, currencyFormat: false) }} {{ $general->cur_text }}</h6>
                                </div>
                                <span class="badge bg-success bg-opacity-25 text-success">@lang('Received')</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ __($trx->details) }}</small>
                                <small class="text-muted"><i class="bi bi-calendar3"></i> {{ showDateTime($trx->created_at, 'd M Y') }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                            @lang('No daily profit received yet.')
                        </div>
                    @endforelse
                </div>

                <!-- Premium Pagination -->
                @if($transactions->hasPages())
                    <div class="premium-pagination mt-4">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            {{-- Previous --}}
                            @if ($transactions->onFirstPage())
                                <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                            @endif

                            {{-- Page Numbers --}}
                            @php
                                $currentPage = $transactions->currentPage();
                                $lastPage = $transactions->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $transactions->url(1) }}" class="page-btn">1</a>
                                @if($start > 2)
                                    <span class="page-dots">...</span>
                                @endif
                            @endif

                            @for ($i = $start; $i <= $end; $i++)
                                <a href="{{ $transactions->url($i) }}" class="page-btn {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <span class="page-dots">...</span>
                                @endif
                                <a href="{{ $transactions->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                            @endif

                            {{-- Next --}}
                            @if ($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                            @else
                                <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                            @endif
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">@lang('Page') {{ $transactions->currentPage() }} @lang('of') {{ $transactions->lastPage() }} ({{ $transactions->total() }} @lang('items'))</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    @include($activeTemplate . 'partials.mobile-bottom-nav')

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

@endsection

@push('script')
    <!-- Include Modern Dashboard Initialization -->
    @include($activeTemplate . 'js.modern-dashboard-init')

    <!-- Include Icon Enhancer -->
    @include($activeTemplate . 'js.icon-enhancer')
@endpush
