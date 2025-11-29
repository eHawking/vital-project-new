@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="premium-card">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                        <h5 class="title text-white m-0">@lang('KYC Verification')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modern-form-group">
                                <x-viser-form identifier="act" identifierValue="kyc" />
                            </div>
                            <button type="submit" class="btn btn-primary w-100 pulse-animation mt-3" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
<script>
    $(document).ready(function() {
        // Apply modern styling to dynamic form elements from x-viser-form
        $('.form-control').addClass('bg-transparent text-white border-secondary');
        $('.form-label').addClass('text-white-50');
        $('.form-select').addClass('bg-transparent text-white border-secondary');
    });
</script>
@endpush
