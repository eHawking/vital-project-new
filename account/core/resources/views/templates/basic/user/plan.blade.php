@extends($activeTemplate . 'layouts.master')

@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="row justify-content-center g-4">
        @foreach ($plans as $data)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="premium-card h-100 d-flex flex-column">
                    <div class="card-body p-0">
                        <div class="pricing-table text-center mb-4">
                            <h2 class="package-name mb-3" style="font-size: 1.8rem; background: var(--grad-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">
                                {{ __(strtoupper($data->name)) }}
                            </h2>
                            <div class="price mb-4">
                                <span class="text-white display-4 fw-bold">{{ showAmount($data->price) }}</span>
                            </div>
                            
                            <div class="divider my-4" style="height: 1px; background: rgba(255,255,255,0.1);"></div>
                            
                            <ul class="package-features-list text-start d-inline-block mx-auto">
                                <li class="mb-3 d-flex align-items-center text-white-50">
                                    <div class="icon-box variant-green me-3" style="width: 32px; height: 32px; font-size: 14px;">
                                        <i class="las la-check"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-white small">@lang('Business Volume (BV)')</span>
                                        <strong class="text-primary">{{ getAmount($data->bv) }}</strong>
                                        <i class="las la-info-circle __plan_info text-muted ms-1 cursor-pointer" data="bv" style="font-size: 12px;"></i>
                                    </div>
                                </li>
                                <li class="mb-3 d-flex align-items-center text-white-50">
                                    <div class="icon-box variant-blue me-3" style="width: 32px; height: 32px; font-size: 14px;">
                                        <i class="las la-users"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-white small">@lang('Referral Commission')</span>
                                        <strong class="text-info">{{ gs('cur_sym') }}{{ getAmount($data->ref_com) }}</strong>
                                        <i class="las la-info-circle __plan_info text-muted ms-1 cursor-pointer" data="ref_com" style="font-size: 12px;"></i>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center text-white-50">
                                    <div class="icon-box variant-purple me-3" style="width: 32px; height: 32px; font-size: 14px;">
                                        <i class="las la-project-diagram"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-white small">@lang('Tree Commission')</span>
                                        <strong class="text-warning">{{ gs('cur_sym') }}{{ getAmount($data->tree_com) }}</strong>
                                        <i class="las la-info-circle __plan_info text-muted ms-1 cursor-pointer" data="tree_com" style="font-size: 12px;"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-auto text-center">
                            @if (auth()->user()->plan_id != $data->id)
                                <button class="btn btn-primary w-100 pulse-animation __subscribe" data-id="{{ $data->id }}" style="background: var(--grad-primary); border: none; padding: 12px; border-radius: 12px; font-weight: 600;">
                                    @lang('Subscribe Now')
                                </button>
                            @else
                                <button class="btn btn-secondary w-100" disabled style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 12px;">
                                    @lang('Current Plan')
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="plan_info_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="plan_info_modal_title">@lang('Commission Info')</h5>
                    <button type="button" class="btn-close btn-close-white" id="__modal_close"></button>
                </div>
                <div class="modal-body text-white-50">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subscribe_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">@lang('Confirm Purchase')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white-50">@lang('Are you sure you want to subscribe to this plan?')</p>
                </div>
                <div class="modal-footer border-0">
                    <form method="post" action="{{ route('user.plan.purchase') }}" class="w-100">
                        @csrf
                        <input id="plan_id" name="plan_id" type="hidden">
                        <div class="d-flex gap-2">
                            <button class="btn btn-light flex-fill" data-bs-dismiss="modal" type="button">@lang('Cancel')</button>
                            <button class="btn btn-primary flex-fill" type="submit" style="background: var(--grad-primary); border: none;">@lang('Confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.__plan_info').on('click', function(e) {
                let html = "";
                let data = $(this).attr('data');
                let modal = $("#plan_info_modal");
                if (data == 'bv') {
                    html = ` <h5>   <span class="text--danger">@lang('When someone from your below tree subscribe this plan, You will get this Business Volume  which will be used for matching bonus').</span>
                </h5>`
                    modal.find('#plan_info_modal_title').html("@lang('Business Volume (BV) info')")

                }
                if (data == 'ref_com') {
                    html = `  <h5>  <span class=" text--danger">@lang('When Your Direct-Referred/Sponsored  User Subscribe in') <b> @lang('ANY PLAN') </b>, @lang('You will get this amount').</span>
                        <br>
                        <br>
                        <span class="text--success"> @lang('This is the reason You should Choose a Plan With Bigger Referral Commission').</span> </h5>`
                    modal.find('#plan_info_modal_title').html("@lang('Referral Commission info')")

                }
                if (data == 'tree_com') {
                    html = ` <h5 class=" text--danger">@lang('When someone from your below tree subscribe this plan, You will get this amount as Tree Commission'). </h5>`
                    modal.find('#plan_info_modal_title').html("@lang('Referral Commission info')")

                }
                modal.find('.modal-body').html(html)
                $(modal).modal('show')
            });

            $('body').on('click', '#__modal_close', function(e) {
                $("#plan_info_modal").modal('hide');
            });

            $('.__subscribe').on('click', function(e) {
                let id = $(this).attr('data-id');
                $('#plan_id').attr('value', id);
                $("#subscribe_modal").modal('show');
            })
        })(jQuery)
    </script>
@endpush
