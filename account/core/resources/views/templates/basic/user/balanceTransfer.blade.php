@extends($activeTemplate . 'layouts.master')

@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="premium-card">
                <div class="card-body">
                    <form class="contact-form" method="POST" action="{{ route('user.balance.transfer') }}">
                        @csrf
                        
                        <div class="text-center mb-4">
                            <div class="alert p-3 d-inline-block w-100" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); border-radius: 12px;">
                                <strong class="text-danger">@lang('Balance Transfer Charge'):</strong> 
                                <span class="text-white">
                                    {{ getAmount(gs('bal_trans_fixed_charge')) }} {{ __(gs('cur_text')) }} @lang('Fixed') + 
                                    {{ getAmount(gs('bal_trans_per_charge')) }}% 
                                </span>
                                <br/>
                                <small class="text-white-50">@lang('of your total amount to transfer balance.')</small>
                                <div id="after-balance" class="d-block mt-2 fw-bold"></div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label text-white-50">@lang('Username / Email To Send Amount') </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-user"></i></span>
                                <input class="form-control bg-transparent text-white border-secondary" id="username" name="username" type="text" placeholder="@lang('username / email')" required autocomplete="off">
                            </div>
                            <span id="position-test"></span>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label text-white-50" for="InputMail">@lang('Transfer Amount')</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-secondary">{{ __(gs('cur_text')) }}</span>
                                <input class="form-control bg-transparent text-white border-secondary" type="number" step="any" id="amount" name="amount"
                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" autocomplete="off"
                                    placeholder="@lang('0.00')" required>
                            </div>
                            <span id="balance-message"></span>
                        </div>

                        <button class="btn btn-primary w-100 pulse-animation" type="submit" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600; font-size: 16px;">
                            <i class="las la-exchange-alt me-1"></i> @lang('Transfer Balance')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $(document).on('focusout', '#username', function() {
                var username = $('#username').val();
                var token = "{{ csrf_token() }}";
                if (username) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.search.user') }}",
                        data: {
                            'username': username,
                            '_token': token
                        },
                        success: function(data) {
                            if (data.success) {
                                $('#position-test').html('<div class="text-success mt-1"><i class="las la-check-circle"></i> @lang('User found')</div>');
                            } else {
                                $('#position-test').html('<div class="text-danger mt-2"><i class="las la-times-circle"></i> @lang('User not found')</div>');
                            }
                        }
                    });
                } else {
                    $('#position-test').html('');
                }
            });
            $(document).on('keyup', '#amount', function() {
                var amount = parseFloat($('#amount').val());
                var balance = parseFloat("{{ Auth::user()->balance + 0 }}");
                var fixed_charge = parseFloat("{{ gs('bal_trans_fixed_charge') + 0 }}");
                var percent_charge = parseFloat("{{ gs('bal_trans_per_charge') + 0 }}");
                var percent = (amount * percent_charge) / 100;
                var with_charge = amount + fixed_charge + percent;
                if (with_charge > balance) {
                    $('#after-balance').html('<span class="text-danger">' + with_charge.toFixed(2) + ' {{ gs('cur_text') }} ' +
                        ' {{ __('will be subtracted from your balance') }}' + '</span>');
                    $('#balance-message').html('<small class="text-danger"><i class="las la-exclamation-circle"></i> Insufficient Balance!</small>');
                } else if (with_charge <= balance) {
                    $('#after-balance').html('<span class="text-warning">' + with_charge.toFixed(2) + ' {{ gs('cur_text') }} ' +
                        ' {{ __('will be subtracted from your balance') }}' + '</span>');
                    $('#balance-message').html('');
                }
            });
        })(jQuery)
    </script>
@endpush
