@extends($activeTemplate.'layouts.master')

@push('style')
    <link href="{{asset('assets/global/css/tree.css')}}" rel="stylesheet">
@endpush

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<style>
    /* Mobile Full Width Adjustments */
    @media (max-width: 768px) {
        .inner-dashboard-container,
        .container-fluid.px-4,
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            width: 100% !important;
            margin: 0 !important;
        }
        .premium-card {
            width: 100% !important;
            margin-bottom: 10px;
            border-radius: 12px !important;
        }
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .search-form-wrapper {
            width: 100% !important;
            max-width: 100% !important;
        }
        .page-header-wrapper {
            flex-direction: column;
            align-items: stretch !important;
        }
        .tree-card {
            padding: 10px !important;
        }
    }

    /* Theme-aware text colors */
    h1, h2, h3, h4, h5, h6, .premium-title {
        color: var(--text-primary) !important;
    }
    
    /* Search Input Styling */
    .premium-search-input {
        background: var(--bg-card) !important;
        border: 1px solid rgba(128,128,128,0.2) !important;
        color: var(--text-primary) !important;
        border-radius: 10px 0 0 10px !important;
        padding: 10px 15px;
    }
    .premium-search-input::placeholder {
        color: var(--text-muted) !important;
    }
    .premium-search-input:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(var(--rgb-primary), 0.1) !important;
    }
    .premium-search-btn {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%) !important;
        border: none !important;
        border-radius: 0 10px 10px 0 !important;
        padding: 10px 20px;
        color: #fff !important;
    }
    .premium-search-btn:hover {
        opacity: 0.9;
    }

    /* Premium Modal Styling */
    .premium-modal .modal-content {
        background: #1a1f2e !important;
        border: 1px solid rgba(128,128,128,0.2) !important;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }
    [data-theme="light"] .premium-modal .modal-content {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.1) !important;
    }
    .premium-modal .modal-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-bottom: none !important;
        padding: 20px 25px;
    }
    .premium-modal .modal-title {
        color: #fff !important;
        font-weight: 600;
    }
    .premium-modal .modal-header .btn-close {
        filter: invert(1) !important;
        opacity: 0.8;
    }
    .premium-modal .modal-header .btn-close:hover {
        opacity: 1;
    }
    .premium-modal .modal-body {
        background: #1a1f2e;
    }
    [data-theme="light"] .premium-modal .modal-body {
        background: #ffffff;
    }
    .premium-modal .user-header-box {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 25px;
        text-align: center;
    }
    [data-theme="light"] .premium-modal .user-header-box {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .premium-modal .info-box {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 15px;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .premium-modal .info-box {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .premium-modal .info-box:hover {
        transform: translateY(-2px);
    }
    .premium-modal .stat-card {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 20px;
    }
    [data-theme="light"] .premium-modal .stat-card {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .premium-modal .table {
        color: var(--text-primary) !important;
        margin-bottom: 0;
    }
    .premium-modal .table th {
        background: rgba(128,128,128,0.05) !important;
        border-color: rgba(128,128,128,0.1) !important;
        color: var(--text-muted) !important;
        font-weight: 600;
        padding: 12px 15px;
    }
    .premium-modal .table td {
        background: transparent !important;
        border-color: rgba(128,128,128,0.1) !important;
        color: var(--text-primary) !important;
        padding: 12px 15px;
    }
    .premium-modal .table tbody tr:hover {
        background: rgba(128,128,128,0.03) !important;
    }
    .premium-modal .text-label {
        color: var(--text-muted) !important;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .premium-modal .referral-box {
        background: #1e3a4a;
        border: 1px solid rgba(13, 202, 240, 0.3);
        border-radius: 12px;
        padding: 15px 20px;
    }
    [data-theme="light"] .premium-modal .referral-box {
        background: #e8f7fa;
        border: 1px solid rgba(13, 202, 240, 0.4);
    }
    .premium-modal .user-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 3px solid var(--color-primary);
        box-shadow: 0 0 20px rgba(var(--rgb-primary), 0.3);
        object-fit: cover;
    }
    .premium-modal .username-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 10px;
    }
    .premium-modal .tree-link-btn {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #fff;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .premium-modal .tree-link-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(var(--rgb-primary), 0.4);
        color: #fff;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3 page-header-wrapper">
                <div>
                    <h4 class="m-0"><i class="bi bi-diagram-3-fill"></i> @lang('My Tree')</h4>
                    <p class="text-muted small m-0">@lang('View your binary tree structure')</p>
                </div>
                
                <!-- Search Form -->
                <form action="{{ route('user.other.tree.search') }}" method="get" class="flex-grow-1 search-form-wrapper" style="max-width: 400px;">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="@lang('Search by username...')" class="form-control premium-search-input" required>
                        <button class="btn premium-search-btn" type="submit">
                            <i class="bi bi-search"></i> @lang('Search')
                        </button>
                    </div>
                </form>
            </div>

            <div class="premium-card tree-card overflow-auto">   
                <div class="card-body">
                    <div class="row text-center justify-content-center llll">
                        <!-- <div class="col"> -->
                        <div class="w-1">
                            @php echo showSingleUserinTree($tree['a']); @endphp
                        </div>
                    </div>
                    <div class="row text-center justify-content-center llll">
                        <!-- <div class="col"> -->
                        <div class="w-2">
                            @php echo showSingleUserinTree($tree['b']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-2 ">
                            @php echo showSingleUserinTree($tree['c']); @endphp
                        </div>
                    </div>
                    <div class="row text-center justify-content-center llll">
                        <!-- <div class="col"> -->
                        <div class="w-4 ">
                            @php echo showSingleUserinTree($tree['d']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-4 ">
                            @php echo showSingleUserinTree($tree['e']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-4 ">
                            @php echo showSingleUserinTree($tree['f']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-4 ">
                            @php echo showSingleUserinTree($tree['g']); @endphp
                        </div>
                        <!-- <div class="col"> -->

                    </div>
                    <div class="row text-center justify-content-center llll">
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['h']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['i']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['j']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['k']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['l']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['m']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['n']); @endphp
                        </div>
                        <!-- <div class="col"> -->
                        <div class="w-8">
                            @php echo showSingleUserinTree($tree['o']); @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@push('modal')
<div class="modal fade user-details-modal-area premium-modal" id="exampleModalCenter" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-vcard me-2"></i> @lang('User Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="user-details-modal">
                    <!-- User Profile Header -->
                    <div class="user-header-box mb-4">
                        <div class="mb-3">
                            <img src="#" alt="*" class="tree_image user-avatar">
                        </div>
                        <h4 class="main-username username-display"></h4>
                        <a class="tree_url tree_name tree-link-btn" href="">
                            <i class="bi bi-diagram-3 me-1"></i> <span></span>
                        </a>
                        <div class="badges mt-3">
                            <span class="badge tree_status me-1"></span>
                            <span class="badge tree_plan"></span>
                        </div>
                    </div>

                    <!-- Balance & E-Pin Cards -->
                    <div class="row dsp_div mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="info-box h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box variant-blue rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-wallet2 fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-label d-block">@lang('Current Balance')</small>
                                        <h5 class="balance mb-0 fw-bold"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box variant-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-credit-card-2-front fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-label d-block">@lang('E-Pin Credit')</small>
                                        <h5 class="epincredit mb-0 fw-bold"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Info -->
                    <div class="referral-box mb-4">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div>
                                <i class="bi bi-person-check-fill text-info fs-5"></i>
                                <span class="text-muted">@lang('Referred By'):</span>
                                <span class="tree_ref text-info fw-bold"></span>
                            </div>
                            <div>
                                <i class="bi bi-geo-alt-fill text-info fs-5"></i>
                                <span class="text-muted">@lang('City'):</span>
                                <span class="city text-info fw-bold"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tree Statistics Table -->
                    <div class="stat-card">
                        <h6 class="mb-3 text-muted"><i class="bi bi-bar-chart-fill me-2"></i>@lang('Team Statistics')</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-diagram-2 me-1"></i> @lang('Member Type')</th>
                                        <th class="text-center"><i class="bi bi-arrow-left-circle me-1"></i> @lang('LEFT')</th>
                                        <th class="text-center"><i class="bi bi-arrow-right-circle me-1"></i> @lang('RIGHT')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-people text-secondary me-2"></i> @lang('Free Member')</td>
                                        <td class="text-center"><span class="badge bg-secondary bg-opacity-25 text-secondary px-3 py-2 lfree"></span></td>
                                        <td class="text-center"><span class="badge bg-secondary bg-opacity-25 text-secondary px-3 py-2 rfree"></span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-person-check-fill text-success me-2"></i> @lang('Paid Member')</td>
                                        <td class="text-center"><span class="badge bg-success bg-opacity-25 text-success px-3 py-2 lpaid"></span></td>
                                        <td class="text-center"><span class="badge bg-success bg-opacity-25 text-success px-3 py-2 rpaid"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endpush



@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

    <script>
        "use strict";
        (function ($) {
            $('.showDetails').on('click', function () {
                var modal = $('#exampleModalCenter');
				
				if($(this).data('dspusername') == ''){
                    $('.dsp_div').show();
                }else{$('.dsp_div').hide();}
				
                $('.main-username').text($(this).data('mainusername'));
                $('.tree_name').text($(this).data('name'));
                $('.tree_url').attr({"href": $(this).data('treeurl')});
                $('.tree_status').text($(this).data('status'));
                $('.tree_plan').text($(this).data('plan'));
                $('.tree_image').attr({"src": $(this).data('image')});
                $('.user-details-header').removeClass('Paid');
                $('.user-details-header').removeClass('Free');
                $('.user-details-header').addClass($(this).data('status'));
                $('.tree_ref').text($(this).data('refby'));
                $('.lbv').text($(this).data('lbv'));
                $('.rbv').text($(this).data('rbv'));
                $('.lpaid').text($(this).data('lpaid'));
                $('.rpaid').text($(this).data('rpaid'));
                $('.lfree').text($(this).data('lfree'));
                $('.rfree').text($(this).data('rfree'));
                $('#exampleModalCenter').modal('show');
				$('.dspusername').text($(this).data('dspusername')); 
                $('.balance').text($(this).data('balance')); 
                $('.epincredit').text($(this).data('epincredit'));
				$('.city').text($(this).data('city'));
				
            });
        })(jQuery);
    </script>

@endpush



