@extends($activeTemplate.'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="dashboard-header mb-4 text-center text-lg-start">
                <h2 class="page-title text-white mb-1"><i class="bi bi-check-circle"></i> @lang('Withdraw Preview')</h2>
                <p class="page-subtitle text-white-50">@lang('Confirm your withdrawal details')</p>
            </div>

            <div class="premium-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary border-opacity-25 pb-3">
                        <h4 class="title text-white m-0"><i class="bi bi-bank me-2"></i> @lang('Withdraw Via') {{ $withdraw->method->name }}</h4>
                    </div>
                    
                    <div class="alert p-4 mb-4 text-white" style="background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%); border: 1px solid rgba(13, 202, 240, 0.3); border-radius: 12px;">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0 text-info">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading text-info fw-bold">@lang('Withdrawal Summary')</h5>
                                <p class="mb-0 opacity-75">
                                    @lang('You are requesting') <strong class="text-white">{{ showAmount($withdraw->amount) }}</strong> @lang('for withdraw.')
                                    @lang('The admin will send you') <strong class="text-success">{{showAmount($withdraw->final_amount,currencyFormat:false) .' '.$withdraw->currency }}</strong> @lang('to your account.')
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{route('user.withdraw.submit')}}" class="disableSubmission" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        @if($withdraw->method->description)
                        <div class="mb-4 p-4 rounded text-white-50" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--bs-info);">
                            @php
                                echo $withdraw->method->description;
                            @endphp
                        </div>
                        @endif
                        
                        <div class="modern-form-group">
                            <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                        </div>
                        
                        @if(auth()->user()->ts)
                        <div class="form-group mb-4">
                            <label class="form-label text-white-50"><i class="bi bi-shield-lock"></i> @lang('Google Authenticator Code')</label>
                            <input type="text" name="authenticator_code" class="form-control bg-transparent text-white border-secondary" placeholder="@lang('Enter 6-digit code')" required>
                        </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary w-100 pulse-animation py-3 fw-bold fs-5 mt-3" style="background: var(--grad-primary); border: none;">
                            <i class="bi bi-check-circle me-2"></i> @lang('Submit Withdrawal')
                        </button>
                    </form>
                </div>
            </div>
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
    // Apply modern styling to dynamic form elements
    $(document).ready(function() {
        $('.form-control').addClass('bg-transparent text-white border-secondary');
        $('.form-label').addClass('text-white-50');
    });
</script>
@endpush
