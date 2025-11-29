@extends($activeTemplate . 'layouts.master')

@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="premium-card">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <h5 class="text-white m-0">@lang('Confirm Deposit')</h5>
                </div>
                <div class="card-body p-4">
                    <form class="disableSubmission" action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 text-white mb-4">
                            <p class="mb-0"><i class="las la-info-circle text-primary me-2"></i> @lang('You are requesting') <b class="text-white">{{ showAmount($data['amount']) }}</b> @lang('to deposit.') @lang('Please pay')
                                <b class="text-white">{{ showAmount($data['final_amount'], currencyFormat: false) . ' ' . $data['method_currency'] }} </b> @lang('for successful payment.')
                            </p>
                        </div>

                        <div class="mb-4 text-white-50">@php echo  $data->gateway->description @endphp</div>

                        <div class="modern-form-group">
                            <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />
                        </div>
                            
                        <button class="btn btn-primary w-100 pulse-animation mt-3" type="submit" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;">@lang('Pay Now')</button>

                    </form>
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
        // Apply modern styling to dynamic form elements
        $('.form-control').addClass('bg-transparent text-white border-secondary');
        $('.form-label').addClass('text-white-50');
    });
</script>
@endpush
