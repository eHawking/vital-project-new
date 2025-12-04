@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="container-fluid px-4 py-4 inner-dashboard-container">

    <div id="referral-content">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h3 class="premium-title mb-0">@lang('My Referrals')</h3>
                        <p class="text-muted small m-0">@lang('View all your direct referrals and their status')</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Stats Cards -->
        <div class="stats-grid">
            
            <!-- Total Referrals -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Total Referrals')</h6>
                    <h3>{{ $logs->total() }}</h3>
                </div>
                <div class="icon-box variant-blue mb-0">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>

            <!-- Active DSP -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Active DSP')</h6>
                    <h3>{{ $logs->where('plan_id', 1)->count() }}</h3>
                </div>
                <div class="icon-box variant-green mb-0">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>

            <!-- Active BTP -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Active BTP')</h6>
                    <h3>{{ $logs->where('is_btp', 1)->count() }}</h3>
                </div>
                <div class="icon-box variant-purple mb-0">
                    <i class="bi bi-star-fill fs-3"></i>
                </div>
            </div>

            <!-- This Month -->
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('This Month')</h6>
                    <h3>{{ $logs->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
                <div class="icon-box variant-orange mb-0">
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Referrals Table -->
        <div class="premium-card">
            <h5 class="mb-4">@lang('Referral List')</h5>
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
                                    {{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="pagination-wrapper d-flex justify-content-between align-items-center flex-wrap mt-4 gap-3">
                    <div class="pagination-container">
                        {{ paginateLinks($logs) }}
                    </div>
                    <div class="go-to-page d-flex align-items-center gap-2">
                        <span class="text-muted small">@lang('Go to page'):</span>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <input type="number" class="form-control bg-transparent text-primary border-secondary" id="pageNumber" min="1" max="{{ $logs->lastPage() }}" placeholder="1">
                            <button class="btn btn-outline-primary" type="button" id="goToPageBtn"><i class="bi bi-arrow-right"></i></button>
                        </div>
                        <span class="text-muted small">@lang('of') {{ $logs->lastPage() }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

<style>
    /* Ensure table styling matches theme */
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
    /* Ensure headings and other text inherit correct color */
    h1, h2, h3, h4, h5, h6, .premium-title {
        color: var(--text-primary) !important;
    }
    
    /* Pagination Redesign */
    .pagination {
        margin-bottom: 0;
        gap: 5px;
    }
    .page-item .page-link {
        background: transparent;
        border: 1px solid rgba(128,128,128,0.2);
        color: var(--text-primary);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
    }
    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(var(--rgb-primary), 0.3);
    }
    .page-item.disabled .page-link {
        background: rgba(128,128,128,0.05);
        color: rgba(128,128,128,0.4);
        border-color: transparent;
    }
    .page-item .page-link:hover:not(.active) {
        background: rgba(var(--rgb-primary), 0.1);
        color: var(--color-primary);
        border-color: var(--color-primary);
    }

    /* Table Enhancements */
    .table-custom tbody tr {
        transition: background 0.2s ease;
    }
    .table-custom tbody tr:hover {
        background: rgba(128,128,128,0.05) !important;
    }
    
    /* Mobile Full Width Adjustments */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr !important;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .stat-item, .premium-card {
            width: 100% !important;
            margin-bottom: 10px;
        }

        /* Outer container padding RESET */
        .container-fluid.px-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Remove inner container padding */
        .inner-dashboard-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Reset Row margins */
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .premium-card {
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
        }
        
        /* Pagination Responsive */
        .pagination-wrapper {
            justify-content: center !important;
            flex-direction: column;
        }
        .pagination-container, .go-to-page {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<script>
    (function($) {
        "use strict";
        
        // AJAX Pagination Logic
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            fetchData(url);
        });

        // Go To Page Logic
        $(document).on('click', '#goToPageBtn', function() {
            let page = $('#pageNumber').val();
            let currentUrl = new URL(window.location.href);
            
            if(page && page > 0) {
                currentUrl.searchParams.set('page', page);
                fetchData(currentUrl.toString());
            }
        });

        // Handle Enter Key in Go To Page Input
        $(document).on('keypress', '#pageNumber', function(e) {
            if(e.which == 13) {
                $('#goToPageBtn').click();
            }
        });

        function fetchData(url) {
            // Show loading state if desired (optional)
            $('.table-responsive').css('opacity', '0.5');
            
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    // Parse the response HTML
                    let newContent = $(response).find('#referral-content').html();
                    
                    // Update the content
                    $('#referral-content').html(newContent);
                    
                    // Update URL in browser address bar
                    window.history.pushState({path: url}, '', url);
                    
                    // Restore opacity
                    $('.table-responsive').css('opacity', '1');
                    
                    // Scroll to top of content
                    $('html, body').animate({
                        scrollTop: $("#referral-content").offset().top - 100
                    }, 500);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    $('.table-responsive').css('opacity', '1');
                    notify('error', 'Failed to load data. Please try again.');
                }
            });
        }

    })(jQuery);
</script>
@endpush