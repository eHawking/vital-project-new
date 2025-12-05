@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <style>
        /* Mobile Full Width Adjustments */
        @media (max-width: 768px) {
            .user-data-container {
                padding: 10px !important;
            }
            .premium-form-card {
                border-radius: 16px !important;
            }
        }

        /* Premium Form Card */
        .premium-form-card {
            background: #1a1f2e;
            border: 1px solid rgba(128,128,128,0.2);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        [data-theme="light"] .premium-form-card {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.1);
        }

        /* Premium Header */
        .premium-form-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
            padding: 30px;
            text-align: center;
        }
        .premium-form-header h4 {
            color: #fff !important;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .premium-form-header p {
            color: rgba(255,255,255,0.8);
            margin: 0;
        }
        .premium-form-header .header-icon {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        .premium-form-header .header-icon i {
            font-size: 32px;
            color: #fff;
        }

        /* Form Body */
        .premium-form-body {
            padding: 30px;
            background: #1a1f2e;
        }
        [data-theme="light"] .premium-form-body {
            background: #ffffff;
        }

        /* Form Labels */
        .premium-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        /* Form Inputs */
        .premium-input {
            background: #242938 !important;
            border: 1px solid rgba(128,128,128,0.2) !important;
            color: var(--text-primary) !important;
            border-radius: 10px !important;
            padding: 12px 15px !important;
            transition: all 0.3s ease;
        }
        [data-theme="light"] .premium-input {
            background: #f8f9fa !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
        }
        .premium-input:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(var(--rgb-primary), 0.15) !important;
        }
        .premium-input::placeholder {
            color: var(--text-muted) !important;
        }

        /* Input Group */
        .premium-input-group .input-group-text {
            background: #242938 !important;
            border: 1px solid rgba(128,128,128,0.2) !important;
            border-right: none !important;
            color: var(--text-primary) !important;
            border-radius: 10px 0 0 10px !important;
            padding: 12px 15px;
        }
        [data-theme="light"] .premium-input-group .input-group-text {
            background: #f8f9fa !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
            border-right: none !important;
        }
        .premium-input-group .premium-input {
            border-radius: 0 10px 10px 0 !important;
        }

        /* Select2 Styling */
        .select2-container--default .select2-selection--single {
            background: #242938 !important;
            border: 1px solid rgba(128,128,128,0.2) !important;
            border-radius: 10px !important;
            height: 48px !important;
            padding: 8px 15px;
        }
        [data-theme="light"] .select2-container--default .select2-selection--single {
            background: #f8f9fa !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-primary) !important;
            line-height: 30px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .select2-dropdown {
            background: #242938 !important;
            border: 1px solid rgba(128,128,128,0.2) !important;
        }
        [data-theme="light"] .select2-dropdown {
            background: #ffffff !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
        }
        .select2-container--default .select2-results__option {
            color: var(--text-primary) !important;
            padding: 10px 15px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: var(--color-primary) !important;
            color: #fff !important;
        }

        /* Submit Button */
        .premium-submit-btn {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%) !important;
            border: none !important;
            padding: 14px 30px !important;
            font-weight: 600 !important;
            border-radius: 12px !important;
            color: #fff !important;
            transition: all 0.3s ease;
        }
        .premium-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(var(--rgb-primary), 0.4);
        }

        /* Error State */
        .premium-input.is-invalid {
            border-color: #dc3545 !important;
        }
    </style>

    <div class="container py-5 user-data-container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div class="premium-form-card">
                    <!-- Premium Header -->
                    <div class="premium-form-header">
                        <div class="header-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h4>@lang('Complete Your Profile')</h4>
                        <p>@lang('Please provide your details to continue')</p>
                    </div>

                    <!-- Form Body -->
                    <div class="premium-form-body">
                        <form method="POST" action="{{ route('user.data.submit') }}">
                            @csrf
                            <div class="row g-4">
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="premium-label">@lang('Country')</label>
                                        <select name="country" class="form-control premium-input select2" required>
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="premium-label">@lang('Mobile')</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text mobile-code"></span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control premium-input checkUser" required>
                                        </div>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="premium-label">@lang('Address')</label>
                                        <input type="text" class="form-control premium-input" name="address" value="{{ old('address') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="premium-label">@lang('State')</label>
                                        <select name="state" class="form-control premium-input select2" required>
                                            <option value="" disabled selected>@lang('Select State/Province')</option>
                                            <option value="Punjab" {{ old('state')=='Punjab' ? 'selected' : '' }}>Punjab</option>
                                            <option value="Sindh" {{ old('state')=='Sindh' ? 'selected' : '' }}>Sindh</option>
                                            <option value="Khyber Pakhtunkhwa" {{ old('state')=='Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
                                            <option value="Balochistan" {{ old('state')=='Balochistan' ? 'selected' : '' }}>Balochistan</option>
                                            <option value="Islamabad Capital Territory" {{ old('state')=='Islamabad Capital Territory' ? 'selected' : '' }}>Islamabad Capital Territory</option>
                                            <option value="Azad Jammu and Kashmir" {{ old('state')=='Azad Jammu and Kashmir' ? 'selected' : '' }}>Azad Jammu and Kashmir</option>
                                            <option value="Gilgit-Baltistan" {{ old('state')=='Gilgit-Baltistan' ? 'selected' : '' }}>Gilgit-Baltistan</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="premium-label">@lang('City')</label>
                                        <input type="text" class="form-control premium-input" name="city" value="{{ old('city') }}" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="premium-label">@lang('CNIC Number')</label>
                                        <input type="text" id="cnic_display" class="form-control premium-input checkUser" placeholder="XXXXX-XXXXXXX-X" maxlength="15" autocomplete="off" required>
                                        <input type="hidden" name="cnicnumber" id="cnicnumber" value="{{ old('cnicnumber') }}">
                                        <small class="text-danger cnicExist"></small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn premium-submit-btn w-100 mt-4">
                                <i class="bi bi-check-circle me-2"></i> @lang('Submit')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {

            @if($mobileCode)
            $(`option[data-code={{ $mobileCode }}]`).attr('selected','');
            @endif

            $('.select2').select2();

            $('select[name=country]').on('change',function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkUser(value,name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            // Flags to prevent submission if duplicate or invalid
            let mobileTaken = false;
            let cnicTaken = false;
            let cnicInvalid = false;
            const $form = $('form[action="{{ route('user.data.submit') }}"]');
            const $mobileInput = $form.find('input[name="mobile"]');
            const $cnicDisplay = $('#cnic_display');
            const $cnicHidden = $('#cnicnumber');
            const $submitBtn = $form.find('button[type="submit"]');
            const initiallyDisabled = $submitBtn.is(':disabled');

            function updateSubmitState() {
                if (mobileTaken) {
                    $submitBtn.attr('disabled', true);
                } else if (!initiallyDisabled) {
                    $submitBtn.attr('disabled', false);
                }
            }

            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                // CNIC visible input uses a hidden field name
                if (this.id === 'cnic_display') {
                    value = $cnicHidden.val();
                    name = 'cnic';
                }
                checkUser(value, name);
            });

            $mobileInput.on('input', function() {
                mobileTaken = false;
                $mobileInput.removeClass('is-invalid');
                updateSubmitState();
            });

            // CNIC formatting to XXXXX-XXXXXXX-X and set hidden 13-digit value
            function formatCnicDigits(digits) {
                const d = digits.substring(0, 13);
                const part1 = d.substring(0,5);
                const part2 = d.substring(5,12);
                const part3 = d.substring(12,13);
                let out = part1;
                if (part2) out += '-' + part2;
                if (part3) out += '-' + part3;
                return out;
            }

            $cnicDisplay.on('input', function(){
                const digits = $(this).val().replace(/\D/g,'');
                $cnicHidden.val(digits);
                $(this).val(formatCnicDigits(digits));
                cnicInvalid = !(digits.length === 13);
                if (!cnicInvalid) {
                    cnicTaken = false;
                    $cnicDisplay.removeClass('is-invalid');
                }
                updateSubmitState();
            });

            function checkUser(value,name){
                var url = '{{ route('user.checkUser') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code:$('.mobile-code').text().substr(1),
                        _token: token
                    }
                } else if (name == 'cnic') {
                    var data = {
                        cnicnumber: value,
                        _token: token
                    }
                }
                
                $.post(url, data, function(response) {
                    if (response.type === 'mobile') {
                        if (response.data != false) {
                            mobileTaken = true;
                            $(`.${response.type}Exist`).text(`${response.field} already exists`);
                            notify('error', 'Mobile number already exists. Please provide a different mobile number.');
                            $mobileInput.addClass('is-invalid');
                        } else {
                            mobileTaken = false;
                            $(`.${response.type}Exist`).text('');
                            $mobileInput.removeClass('is-invalid');
                        }
                    } else if (response.type === 'cnic') {
                        if (response.data != false) {
                            cnicTaken = true;
                            $('.cnicExist').text('CNIC number already exists');
                            notify('error', 'CNIC number already exists. Please enter a different CNIC.');
                            $cnicDisplay.addClass('is-invalid');
                        } else {
                            cnicTaken = false;
                            $('.cnicExist').text('');
                            $cnicDisplay.removeClass('is-invalid');
                        }
                    }
                    updateSubmitState();
                });
            }

            $form.on('submit', function(e) {
                // Validate CNIC again before submitting
                const cnicDigits = $cnicHidden.val().replace(/\D/g,'');
                cnicInvalid = !(cnicDigits.length === 13);

                if (mobileTaken || cnicTaken || cnicInvalid) {
                    e.preventDefault();
                    if (mobileTaken) {
                        notify('error', 'This mobile number is already registered. Please use a different number.');
                        $mobileInput.addClass('is-invalid');
                    }
                    if (cnicInvalid) {
                        notify('error', 'Invalid CNIC format. Please enter 13 digits (e.g., 35202-1234567-1).');
                        $cnicDisplay.addClass('is-invalid');
                    }
                    if (cnicTaken) {
                        notify('error', 'This CNIC is already registered. Please use a different CNIC.');
                        $cnicDisplay.addClass('is-invalid');
                    }
                }
            });
        })(jQuery);
    </script>
@endpush


