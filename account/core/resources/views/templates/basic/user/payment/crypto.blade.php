@extends($activeTemplate.'layouts.master')

@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="premium-card text-center">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                        <h3 class="text-white m-0">@lang('Payment Preview')</h3>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="my-3 text-white"> @lang('PLEASE SEND EXACTLY') <span class="text-success"> {{ $data->amount }}</span> {{__($data->currency)}}</h4>
                        <h5 class="mb-4 text-white-50">@lang('TO') <span class="text-info"> {{ $data->sendto }}</span></h5>
                        <div class="bg-white p-3 d-inline-block rounded mb-4">
                            <img src="{{$data->img}}" alt="Image" class="img-fluid">
                        </div>
                        <h4 class="text-white fw-bold my-4">@lang('SCAN TO SEND')</h4>
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
