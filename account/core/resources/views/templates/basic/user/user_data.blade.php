@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container padding-bottom padding-top">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div class="card custom--card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.data.submit') }}">
                            @csrf
                            <div class="row">
                               
                                <div class="col-md-6">
                                    <div class="form--group">
                                        <label class="form--label">@lang('Country')</label>
                                        <select name="country" class="form-control form--control select2" required>
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form--group">
                                        <label class="form--label">@lang('Mobile')</label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code">

                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser"
                                                required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                </div>
                                <div class="form--group col-sm-12">
                                    <label class="form--label">@lang('Address')</label>
                                    <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}" required>
                                </div>
                                <div class="form--group col-sm-6">
                                    <label class="form--label">@lang('State')</label>
                                    <select name="state" class="form-control form--control select2" required>
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
                               
                                <div class="form--group col-sm-6">
                                    <label class="form--label">@lang('City')</label>
                                    <input type="text" class="form-control form--control" name="city" value="{{ old('city') }}" required>
                                </div>
                                <div class="form--group col-sm-12">
                                    <label class="form--label">@lang('CNIC Number')</label>
                                    <div class="position-relative">
                                        <input type="text" id="cnic_display" class="form-control form--control checkUser" placeholder="XXXXX-XXXXXXX-X" maxlength="15" autocomplete="off" required>
                                        <input type="hidden" name="cnicnumber" id="cnicnumber" value="{{ old('cnicnumber') }}">
                                        <small class="text--danger cnicExist"></small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn--base w-100">
                                @lang('Submit')
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


