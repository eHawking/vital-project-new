@extends($activeTemplate.'layouts.master')

@push('style')
    <link href="{{asset('assets/global/css/tree.css')}}" rel="stylesheet">
@endpush

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="dashboard-header mb-4">
                <h2 class="page-title"><i class="bi bi-diagram-3-fill"></i> @lang('My Tree')</h2>
                <p class="page-subtitle">@lang('View your binary tree structure')</p>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-item">
                <div class="dashboard-item-header mb-3">
                    <h5 class="title"><i class="bi bi-search"></i> @lang('Search Tree')</h5>
                </div>
                <form action="{{ route('user.other.tree.search') }}" method="get">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="@lang('Search by username...')" 
                               class="form-control form-control-lg" required>
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="bi bi-search"></i> @lang('Search')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card custom--card tree-card">   
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

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@push('modal')
<div class="modal fade user-details-modal-area" id="exampleModalCenter" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title"><i class="bi bi-person-vcard"></i> @lang('User Details')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="user-details-modal">
                    <div class="user-details-header modern-user-header">
                        <div class="thumb">
                            <img src="#" alt="*" class="tree_image rounded-circle">
                        </div>
                        <div class="content">
                            <h4 class="main-username mb-2"></h4>
                            <a class="user-name tree_url tree_name btn btn-sm btn-outline-primary mb-2" href="">
                                <i class="bi bi-diagram-3"></i> <span></span>
                            </a>
                            <div class="badges">
                                <span class="badge badge-status tree_status"></span>
                                <span class="badge badge-plan tree_plan"></span>
                            </div>
                        </div>
                    </div>
                    <div class="user-details-body mt-4">
                        <!-- Balance Info -->
                        <div class="row dsp_div mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <div class="info-content">
                                        <small class="text-muted">@lang('Current Balance')</small>
                                        <h5 class="balance mb-0"></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-credit-card-2-front"></i>
                                    </div>
                                    <div class="info-content">
                                        <small class="text-muted">@lang('E-Pin Credit')</small>
                                        <h5 class="epincredit mb-0"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="referral-info mb-4">
                            <i class="bi bi-person-check-fill"></i>
                            <strong>@lang('Referred By'):</strong> <span class="tree_ref text-primary"></span>
                            <i class="bi bi-geo-alt-fill ms-3"></i>
                            <strong>@lang('from'):</strong> <span class="city text-primary"></span>
                        </div>

                        <!-- Tree Statistics -->
                        <div class="table-responsive">
                            <table class="table modern-table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-diagram-2"></i> @lang('Team Side')</th>
                                        <th class="text-center"><i class="bi bi-arrow-left-circle"></i> @lang('LEFT')</th>
                                        <th class="text-center"><i class="bi bi-arrow-right-circle"></i> @lang('RIGHT')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-people"></i> @lang('Free Member')</td>
                                        <td class="text-center"><span class="badge bg-info lfree"></span></td>
                                        <td class="text-center"><span class="badge bg-info rfree"></span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-person-check-fill"></i> @lang('Paid Member')</td>
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

<style>
/* My Tree Page Custom Styles */
.dashboard-header {
    text-align: center;
    padding: 20px 0;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    font-size: 16px;
    color: var(--text-secondary);
    opacity: 0.8;
}

/* Tree Card */
.tree-card {
    background: var(--card-bg);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 30px 15px;
    overflow-x: auto;
}

/* Search Enhancement */
.input-group .form-control {
    border-radius: 10px 0 0 10px;
    border: 2px solid rgba(102, 126, 234, 0.2);
    padding: 12px 20px;
    font-size: 16px;
}

.input-group .form-control:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group .btn {
    border-radius: 0 10px 10px 0;
    padding: 12px 30px;
    background: var(--gradient-purple-blue);
    border: none;
    font-weight: 600;
}

.input-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Modern Modal */
.modern-modal .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.gradient-header {
    background: var(--gradient-purple-blue);
    color: #ffffff;
    padding: 20px 30px;
    border: none;
}

.gradient-header .modal-title {
    font-weight: 700;
    font-size: 20px;
}

/* User Header in Modal */
.modern-user-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 15px;
    margin-bottom: 20px;
}

.modern-user-header .thumb img {
    width: 100px;
    height: 100px;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.modern-user-header .content {
    flex: 1;
}

.modern-user-header h4 {
    color: var(--text-primary);
    font-weight: 700;
    margin: 0;
}

.badge-status, .badge-plan {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-right: 8px;
}

/* Info Cards */
.info-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: var(--card-bg);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-purple-blue);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 24px;
}

.info-content h5 {
    color: var(--text-primary);
    font-weight: 700;
    margin: 0;
}

/* Referral Info */
.referral-info {
    padding: 15px;
    background: rgba(102, 126, 234, 0.05);
    border-left: 4px solid var(--accent-blue);
    border-radius: 8px;
    color: var(--text-primary);
}

.referral-info i {
    color: var(--accent-blue);
}

/* Modern Table */
.modern-table {
    margin: 0;
    border-radius: 10px;
    overflow: hidden;
}

.modern-table thead {
    background: var(--gradient-purple-blue);
    color: #ffffff;
}

.modern-table thead th {
    padding: 15px;
    font-weight: 600;
    border: none;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
}

.modern-table tbody td {
    padding: 15px;
    color: var(--text-primary);
    border-color: rgba(0, 0, 0, 0.05);
}

.modern-table .badge {
    font-size: 14px;
    padding: 8px 16px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }
    
    .tree-card {
        padding: 20px 10px;
    }
    
    .modern-user-header {
        flex-direction: column;
        text-align: center;
    }
    
    .modern-user-header .thumb img {
        width: 80px;
        height: 80px;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control,
    .input-group .btn {
        border-radius: 10px;
        width: 100%;
    }
    
    .input-group .btn {
        margin-top: 10px;
    }
}
</style>
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



