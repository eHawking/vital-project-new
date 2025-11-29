@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="premium-card">
        <div class="card-body">
            <form class="register" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Profile Image Section -->
                    <div class="col-12 text-center mb-4">
                        <div class="profile-image-wrapper position-relative d-inline-block">
                            <div class="profile-image" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 4px solid var(--border-card); box-shadow: 0 0 20px rgba(0,0,0,0.5);">
                                <img id="output" src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="profile" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <label for="file-upload" class="upload-btn position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center cursor-pointer" style="width: 35px; height: 35px; cursor: pointer; border: 2px solid #1e293b;">
                                <i class="las la-camera"></i>
                            </label>
                            <input id="file-upload" class="d-none" name="image" type="file" accept=".png, .jpg, .jpeg" onchange="loadFile(event)">
                        </div>
                        <small class="text-white-50 d-block mt-2">@lang('Supported Files: .png, .jpg, .jpeg (350x300px)')</small>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('First Name')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="firstname" type="text" value="{{ $user->firstname }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('Last Name')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="lastname" type="text" value="{{ $user->lastname }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('E-mail Address')</label>
                            <input class="form-control bg-transparent text-white border-secondary" value="{{ $user->email }}" readonly disabled style="opacity: 0.6;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('Mobile Number')</label>
                            <input class="form-control bg-transparent text-white border-secondary" value="{{ $user->mobile }}" readonly disabled style="opacity: 0.6;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('Address')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="address" type="text" value="{{ @$user->address }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('State')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="state" type="text" value="{{ @$user->state }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('Zip Code')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="zip" type="text" value="{{ @$user->zip }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('City')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="city" type="text" value="{{ @$user->city }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label text-white-50">@lang('Country')</label>
                            <input class="form-control bg-transparent text-white border-secondary" value="{{ @$user->country_name }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-primary w-100 pulse-animation" type="submit" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600; letter-spacing: 1px;">
                            @lang('Update Profile')
                        </button>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';

        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src)
            }
        };
    </script>
@endpush
