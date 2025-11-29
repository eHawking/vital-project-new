@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Include Modern Auth Theme CSS -->
    @include($activeTemplate . 'css.modern-auth')

    @php
        $contactContent = getContent('contact_us.content', true);
        $contactElement = getContent('contact_us.element');
    @endphp

    <section class="contact-section padding-top padding-bottom">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="contact-thumb rtl pe-lg-5">
                        <img src="{{ frontendImage('contact_us', @$contactContent->data_values->image, '700x600') }}" alt="thumb" class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="title text-white mb-4 display-6 fw-bold">{{ __(@$contactContent->data_values->title) }}</h3>
                        <form class="contact-form verify-gcaptcha" method="post">
                            @csrf
                            <div class="form--group mb-3">
                                <label class="form--label">@lang('Name')</label>
                                <input class="form-control form--control bg-transparent text-white border-secondary" name="name" type="text" value="{{ old('name', @$user->fullname) }}"
                                    placeholder="@lang('Enter Your Full Name')" @if ($user && $user->profile_complete) readonly @endif required>
                            </div>
                            <div class="form--group mb-3">
                                <label class="form--label">@lang('Email Address')</label>
                                <input class="form-control form--control bg-transparent text-white border-secondary" name="email" type="email" value="{{ old('email', @$user->email) }}"
                                    placeholder="@lang('Enter Your Email Address')" @if ($user) readonly @endif required>
                            </div>
                            <div class="form--group mb-3">
                                <label class="form--label">@lang('Subject')</label>
                                <input class="form-control form--control bg-transparent text-white border-secondary" name="subject" type="text" value="{{ old('subject') }}" placeholder="@lang('Enter Your Subject')"
                                    required>
                            </div>
                            <div class="form--group mb-4">
                                <label class="form--label" for="msg">@lang('Your Message')</label>
                                <textarea class="form-control form--control bg-transparent text-white border-secondary" id="msg" name="message" rows="5" placeholder="@lang('Enter Your Message')" required>{{ old('message') }}</textarea>
                            </div>
                            @php
                                $custom = true;
                            @endphp
                            <x-captcha :custom="$custom" />
                            <div class="form--group">
                                <button class="account--btn" type="submit">@lang('Send Us Message')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-info padding-bottom">
        <div class="container">
            <div class="row gy-5 justify-content-center">
                <div class="col-lg-6 col-xl-5">
                    <div class="contact-info-wrapper row gy-4 justify-content-center">
                        @foreach ($contactElement as $information)
                            <div class="col-lg-12 col-md-6 col-sm-10">
                                <div class="premium-card h-100 d-flex align-items-center p-4 hover-lift" style="background: var(--card-bg); border: 1px solid var(--glass-border);">
                                    <div class="thumb me-4 bg-white p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <img src="{{ frontendImage('contact_us', $information->data_values->image) }}" alt="icon" class="img-fluid">
                                    </div>
                                    <div class="content">
                                        <h5 class="title text-white mb-1">{{ __(@$information->data_values->title) }} :</h5>
                                        <span class="text-white-50">{{ __(@$information->data_values->content) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6 col-xl-7">
                    <div class="map-wrapper h-100 rounded-4 overflow-hidden shadow-lg border border-secondary border-opacity-25">
                        <iframe class="map w-100 h-100" src="{{ __(@$contactContent->data_values->map_iframe_url) }}" style="border:0; min-height: 400px;" allowfullscreen=""
                            loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
    
    @push('style')
    <style>
        .hover-lift {
            transition: transform 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
        }
    </style>
    @endpush
@endsection
