@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="container-fluid px-4 py-4 inner-dashboard-container">

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

    <style>
        /* Mobile Full Width Adjustments */
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
                border-radius: 12px !important;
            }

            /* Pagination Mobile Fixes */
            .pagination-wrapper {
                width: 100%;
                display: flex;
                justify-content: center;
            }
            
            /* Reset Row margins */
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Mobile "Table as Card" */
            .table-custom thead {
                display: none;
            }
            .table-custom, .table-custom tbody, .table-custom tr, .table-custom td {
                display: block;
                width: 100%;
            }
            .table-custom tr {
                margin-bottom: 15px;
                border: 1px solid rgba(128,128,128,0.1);
                border-radius: 12px;
                background: rgba(255,255,255,0.02);
                padding: 15px;
            }
            .table-custom td {
                text-align: right;
                padding: 10px 0;
                position: relative;
                border-bottom: 1px solid rgba(128,128,128,0.1);
                display: flex;
                justify-content: space-between;
                align-items: center;
                white-space: normal !important; /* Allow text wrap in card view */
            }
            .table-custom td:last-child {
                border-bottom: none;
            }
            .table-custom td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                color: var(--text-primary);
                opacity: 0.7;
            }
        }

        /* Desktop Styles */
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
        
        /* Pagination Styling */
        .pagination {
            justify-content: center;
            gap: 5px;
            margin-bottom: 0;
        }
        .page-item .page-link {
            background: transparent;
            border: 1px solid rgba(128,128,128,0.2);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 6px 12px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(var(--rgb-primary), 0.3);
        }
        .page-item.disabled .page-link {
            background: rgba(128,128,128,0.1);
            color: rgba(128,128,128,0.5);
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
    </style>

    <!-- Referral Stats Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
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
        <div id="referral-table-container">
            @include($activeTemplate . 'user.partials.referral_table')
        </div>
    </div>

</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<script>
    (function($){
        "use strict";
        
        // AJAX Pagination
        $(document).on('click', '.pagination a', function(e){
            e.preventDefault();
            let url = $(this).attr('href');
            fetchData(url);
        });

        // AJAX Jump to Page
        $(document).on('submit', '.pagination-jump-form', function(e){
            e.preventDefault();
            let page = $(this).find('input[name="page"]').val();
            let url = $(this).attr('action');
            // Construct URL with page parameter
            if(url.includes('?')) {
                url += '&page=' + page;
            } else {
                url += '?page=' + page;
            }
            fetchData(url);
        });

        function fetchData(url) {
            // Show loading state if desired (optional)
            $('#referral-table-container').css('opacity', '0.5');
            
            $.ajax({
                url: url,
                type: "GET",
                success: function(response){
                    $('#referral-table-container').html(response);
                    $('#referral-table-container').css('opacity', '1');
                    // Re-initialize any plugins if needed (like tooltips)
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    $('#referral-table-container').css('opacity', '1');
                }
            });
        }
    })(jQuery);
</script>
@endpush