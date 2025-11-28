@extends('layouts.admin.app')

@section('title', translate('default_Referral_Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_Setup')}}
            </h2>
        </div>
        @include('admin-views.business-settings.business-setup-inline-menu')

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0 d-flex align-items-center gap-2">
                            <i class="tio-gift"></i>
                            {{translate('buy_Now_Referral_Configuration')}}
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            {{translate('Configure default referral for customers who click "Buy Now" without an existing referral link. This will be used as a fallback referral.')}}
                        </p>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.referral-setting.update')}}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default_referral_username">
                                            {{translate('default_Referral_Username')}}
                                            <span class="text-danger">*</span>
                                            <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                                  data-bs-title="{{translate('Enter the username that will be used as the default referrer. This user must exist in the account database.')}}">
                                                <i class="fi fi-sr-info"></i>
                                            </span>
                                        </label>
                                        <input type="text" 
                                               name="default_referral_username" 
                                               id="default_referral_username"
                                               class="form-control" 
                                               placeholder="{{translate('Enter username (e.g., admin_refer)')}}"
                                               value="{{$defaultReferral['username'] ?? ''}}">
                                        <small class="text-muted d-block mt-1">
                                            {{translate('This username must exist in your customer/user database.')}}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default_referral_position">
                                            {{translate('default_Position')}}
                                            <span class="text-danger">*</span>
                                            <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                                  data-bs-title="{{translate('Select the position for the referral tree (left or right)')}}">
                                                <i class="fi fi-sr-info"></i>
                                            </span>
                                        </label>
                                        <select name="default_referral_position" 
                                                id="default_referral_position" 
                                                class="form-control">
                                            <option value="">{{translate('Select Position')}}</option>
                                            <option value="left" {{($defaultReferral['position'] ?? '') == 'left' ? 'selected' : ''}}>
                                                {{translate('Left')}}
                                            </option>
                                            <option value="right" {{($defaultReferral['position'] ?? '') == 'right' ? 'selected' : ''}}>
                                                {{translate('Right')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="switcher mb-0" for="enable_default_referral">
                                                <input type="checkbox" 
                                                       class="switcher_input" 
                                                       id="enable_default_referral"
                                                       name="enable_default_referral" 
                                                       value="1"
                                                       {{($defaultReferral['enabled'] ?? false) ? 'checked' : ''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                            <label class="form-check-label mb-0" for="enable_default_referral">
                                                {{translate('Enable Default Referral')}}
                                            </label>
                                            <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                                  data-bs-title="{{translate('When enabled, customers without a referral link will automatically be assigned this default referrer when they click Buy Now.')}}">
                                                <i class="fi fi-sr-info"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{translate('When enabled, the "Buy Now" button will show a modal with options to register as guest or join with this default referral.')}}
                                        </small>
                                    </div>
                                </div>
            </div>

                            <div class="alert alert-info mt-3">
                                <h6 class="alert-heading d-flex align-items-center gap-2">
                                    <i class="tio-info-outined"></i>
                                    {{translate('How It Works')}}
                                </h6>
                                <ul class="mb-0 pl-3">
                                    <li>{{translate('When a customer clicks "Buy Now" without an existing referral link, a modal will appear.')}}</li>
                                    <li>{{translate('The modal offers two options:')}}</li>
                                    <ul>
                                        <li><strong>{{translate('Register as Guest')}}</strong>: {{translate('Continue without referral benefits')}}</li>
                                        <li><strong>{{translate('Join with Benefits')}}</strong>: {{translate('Get cashback, rewards, discounts, offers, and more using the default referral')}}</li>
                                    </ul>
                                    <li>{{translate('If a customer already has a referral link in their session, they will not see the default referral option.')}}</li>
                                    <li>{{translate('Logged-in customers will not see this modal.')}}</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <button type="reset" class="btn btn-secondary px-4">
                                    {{translate('Reset')}}
                                </button>
                                <button type="submit" class="btn btn-primary px-4">
                                    {{translate('Save_Changes')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($defaultReferral['enabled'] && $defaultReferral['username'] && $defaultReferral['position'])
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <i class="tio-checkmark-circle-outlined text-success"></i>
                                {{translate('Current Configuration')}}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-muted fs-12">{{translate('Status')}}</span>
                                        <span class="badge badge-soft-success">{{translate('Enabled')}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-muted fs-12">{{translate('Default Username')}}</span>
                                        <span class="fw-medium">{{$defaultReferral['username']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-muted fs-12">{{translate('Position')}}</span>
                                        <span class="badge badge-soft-info">{{ucfirst($defaultReferral['position'])}}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-label mb-0">{{translate('Registration URL')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly value="{{url('/account/user/register')}}?ref={{$defaultReferral['username']}}&position={{$defaultReferral['position']}}" id="registration-url">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard()">
                                                    <i class="tio-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize Bootstrap tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        function copyToClipboard() {
            const copyText = document.getElementById('registration-url');
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value).then(function() {
                toastr.success('{{ translate("URL copied to clipboard!") }}', {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 3000
                });
            }).catch(function(err) {
                console.error('Failed to copy:', err);
            });
        }
    </script>
@endpush
