@extends($activeTemplate.'layouts.master')

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
                <h2 class="page-title"><i class="bi bi-check-circle"></i> @lang('Withdraw Preview')</h2>
                <p class="page-subtitle">@lang('Confirm your withdrawal details')</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="dashboard-item mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-bank"></i> @lang('Withdraw Via') {{ $withdraw->method->name }}</h4>
                </div>
                
                <div class="alert alert-info" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 4px solid var(--accent-blue); border-radius: 10px;">
                    <h5 class="alert-heading"><i class="bi bi-info-circle-fill"></i> @lang('Withdrawal Summary')</h5>
                    <p class="mb-0">
                        @lang('You are requesting') <strong class="text-primary">{{ showAmount($withdraw->amount) }}</strong> @lang('for withdraw.')
                        @lang('The admin will send you') <strong class="text-success">{{showAmount($withdraw->final_amount,currencyFormat:false) .' '.$withdraw->currency }}</strong> @lang('to your account.')
                    </p>
                </div>

                <form action="{{route('user.withdraw.submit')}}" class="disableSubmission" method="post" enctype="multipart/form-data">
                    @csrf
                    
                    @if($withdraw->method->description)
                    <div class="mb-4 p-3" style="background: var(--card-bg); border-radius: 10px; border-left: 3px solid var(--accent-cyan);">
                        @php
                            echo $withdraw->method->description;
                        @endphp
                    </div>
                    @endif
                    
                    <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                    
                    @if(auth()->user()->ts)
                    <div class="form-group mb-4">
                        <label class="form-label"><i class="bi bi-shield-lock"></i> @lang('Google Authenticator Code')</label>
                        <input type="text" name="authenticator_code" class="form-control" placeholder="@lang('Enter 6-digit code')" required>
                    </div>
                    @endif
                    
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-check-circle"></i> @lang('Submit Withdrawal')
                    </button>
                </form>
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
@endpush
