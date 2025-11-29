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
                        <h5 class="title text-white m-0">@lang('KYC Data')</h5>
                    </div>
                    <div class="card-body">
                        @if ($user->kyc_data)
                            <ul class="list-group list-group-flush bg-transparent">
                                @foreach ($user->kyc_data as $val)
                                    @continue(!$val->value)
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-secondary border-opacity-25 text-white">
                                        <span class="fw-bold text-white-50">{{ __($val->name) }}</span>
                                        <span>
                                            @if ($val->type == 'checkbox')
                                                {{ implode(',', $val->value) }}
                                            @elseif($val->type == 'file')
                                                <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="text-info"><i
                                                        class="fa-regular fa-file"></i> @lang('Attachment') </a>
                                            @else
                                                <p class="mb-0">{{ __($val->value) }}</p>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center p-4">
                                <i class="bi bi-file-earmark-x text-white-50" style="font-size: 3rem;"></i>
                                <h5 class="text-white-50 mt-2">@lang('KYC data not found')</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush
