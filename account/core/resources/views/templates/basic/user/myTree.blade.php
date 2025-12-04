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

    /* Modal Styling */
    .premium-modal .modal-content {
        background: var(--bg-card) !important;
        border: 1px solid rgba(128,128,128,0.1) !important;
        border-radius: 16px;
    }
    .premium-modal .modal-header {
        border-bottom: 1px solid rgba(128,128,128,0.1) !important;
    }
    .premium-modal .modal-title {
        color: var(--text-primary) !important;
    }
    .premium-modal .btn-close {
        filter: var(--btn-close-filter, none);
    }
    [data-theme="dark"] .premium-modal .btn-close {
        filter: invert(1);
    }
    .premium-modal .info-box {
        background: rgba(128,128,128,0.05);
        border-radius: 12px;
        padding: 15px;
    }
    .premium-modal .table {
        color: var(--text-primary) !important;
    }
    .premium-modal .table th,
    .premium-modal .table td {
        background: transparent !important;
        border-color: rgba(128,128,128,0.1) !important;
        color: var(--text-primary) !important;
    }
    .premium-modal .text-label {
        color: var(--text-muted) !important;
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
                <h5 class="modal-title"><i class="bi bi-person-vcard"></i> @lang('User Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="user-details-modal">
                    <div class="user-details-header modern-user-header p-3 rounded mb-4 info-box text-center">
                        <div class="thumb mb-3">
                            <img src="#" alt="*" class="tree_image rounded-circle border border-2 border-primary" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="content">
                            <h4 class="main-username mb-2"></h4>
                            <a class="user-name tree_url tree_name btn btn-sm btn-outline-info mb-2" href="">
                                <i class="bi bi-diagram-3"></i> <span></span>
                            </a>
                            <div class="badges mt-2">
                                <span class="badge tree_status"></span>
                                <span class="badge tree_plan"></span>
                            </div>
                        </div>
                    </div>
                    <div class="user-details-body mt-4">
                        <!-- Balance Info -->
                        <div class="row dsp_div mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="info-box h-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box variant-blue rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <div>
                                            <small class="text-label d-block">@lang('Current Balance')</small>
                                            <h5 class="balance mb-0"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-box h-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box variant-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </div>
                                        <div>
                                            <small class="text-label d-block">@lang('E-Pin Credit')</small>
                                            <h5 class="epincredit mb-0"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="referral-info mb-4 p-3 rounded" style="background: rgba(13, 202, 240, 0.1); border-left: 4px solid var(--bs-info);">
                            <i class="bi bi-person-check-fill text-info"></i>
                            <strong class="text-muted">@lang('Referred By'):</strong> <span class="tree_ref text-info fw-bold"></span>
                            <i class="bi bi-geo-alt-fill ms-3 text-info"></i>
                            <strong class="text-muted">@lang('from'):</strong> <span class="city text-info fw-bold"></span>
                        </div>

                        <!-- Tree Statistics -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-muted"><i class="bi bi-diagram-2"></i> @lang('Team Side')</th>
                                        <th class="text-center text-muted"><i class="bi bi-arrow-left-circle"></i> @lang('LEFT')</th>
                                        <th class="text-center text-muted"><i class="bi bi-arrow-right-circle"></i> @lang('RIGHT')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-people text-muted"></i> @lang('Free Member')</td>
                                        <td class="text-center"><span class="badge bg-secondary lfree"></span></td>
                                        <td class="text-center"><span class="badge bg-secondary rfree"></span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-person-check-fill text-muted"></i> @lang('Paid Member')</td>
                                        <td class="text-center"><span class="badge bg-success lpaid"></span></td>
                                        <td class="text-center"><span class="badge bg-success rpaid"></span></td>
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



