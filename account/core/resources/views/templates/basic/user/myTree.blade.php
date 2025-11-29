@extends($activeTemplate.'layouts.master')

@push('style')
    <link href="{{asset('assets/global/css/tree.css')}}" rel="stylesheet">
@endpush

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div>
                    <h4 class="text-white m-0"><i class="bi bi-diagram-3-fill"></i> @lang('My Tree')</h4>
                    <p class="text-white-50 small m-0">@lang('View your binary tree structure')</p>
                </div>
                
                <!-- Search Form -->
                <form action="{{ route('user.other.tree.search') }}" method="get" class="flex-grow-1" style="max-width: 400px;">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="@lang('Search by username...')" class="form-control bg-transparent text-white border-secondary" required style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                        <button class="btn btn-primary" type="submit" style="background: var(--grad-primary); border: none; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
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
<div class="modal fade user-details-modal-area" id="exampleModalCenter" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white"><i class="bi bi-person-vcard"></i> @lang('User Details')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="user-details-modal">
                    <div class="user-details-header modern-user-header p-3 rounded mb-4" style="background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="thumb mb-3">
                            <img src="#" alt="*" class="tree_image rounded-circle border border-2 border-primary" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="content text-center">
                            <h4 class="main-username mb-2 text-white"></h4>
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
                                <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box variant-blue rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-wallet2 text-white"></i>
                                        </div>
                                        <div>
                                            <small class="text-white-50 d-block">@lang('Current Balance')</small>
                                            <h5 class="balance mb-0 text-white"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.03);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box variant-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-credit-card-2-front text-white"></i>
                                        </div>
                                        <div>
                                            <small class="text-white-50 d-block">@lang('E-Pin Credit')</small>
                                            <h5 class="epincredit mb-0 text-white"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="referral-info mb-4 p-3 rounded" style="background: rgba(13, 202, 240, 0.1); border-left: 4px solid var(--bs-info);">
                            <i class="bi bi-person-check-fill text-info"></i>
                            <strong class="text-white-50">@lang('Referred By'):</strong> <span class="tree_ref text-info fw-bold"></span>
                            <i class="bi bi-geo-alt-fill ms-3 text-info"></i>
                            <strong class="text-white-50">@lang('from'):</strong> <span class="city text-info fw-bold"></span>
                        </div>

                        <!-- Tree Statistics -->
                        <div class="table-responsive">
                            <table class="table modern-table">
                                <thead>
                                    <tr>
                                        <th class="text-white-50 bg-transparent border-bottom border-secondary border-opacity-25"><i class="bi bi-diagram-2"></i> @lang('Team Side')</th>
                                        <th class="text-center text-white-50 bg-transparent border-bottom border-secondary border-opacity-25"><i class="bi bi-arrow-left-circle"></i> @lang('LEFT')</th>
                                        <th class="text-center text-white-50 bg-transparent border-bottom border-secondary border-opacity-25"><i class="bi bi-arrow-right-circle"></i> @lang('RIGHT')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-white bg-transparent border-bottom border-secondary border-opacity-25"><i class="bi bi-people text-white-50"></i> @lang('Free Member')</td>
                                        <td class="text-center bg-transparent border-bottom border-secondary border-opacity-25"><span class="badge bg-secondary lfree"></span></td>
                                        <td class="text-center bg-transparent border-bottom border-secondary border-opacity-25"><span class="badge bg-secondary rfree"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-white bg-transparent border-0"><i class="bi bi-person-check-fill text-white-50"></i> @lang('Paid Member')</td>
                                        <td class="text-center bg-transparent border-0"><span class="badge bg-success lpaid"></span></td>
                                        <td class="text-center bg-transparent border-0"><span class="badge bg-success rpaid"></span></td>
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



