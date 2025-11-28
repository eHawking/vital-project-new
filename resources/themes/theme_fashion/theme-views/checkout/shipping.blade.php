@extends('theme-views.layouts.app')

@section('title', translate('shopping_details').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')

<section class="breadcrumb-section pt-20px">
    <div class="container">
        <div class="section-title mb-4">
            <div
                class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{route('home')}}">{{translate('home')}}</a>
                    </li>
                    <li>
                        <a href="{{route('shop-cart')}}">{{translate('cart')}}</a>
                    </li>
                    <li>
                        <a href="{{url()->current()}}" class="text-base custom-text-link">{{translate('checkout')}}</a>
                    </li>
                </ul>
                <div class="ms-auto ms-md-0">
                    <a href="{{route('shop-cart')}}" class="text-base custom-text-link">{{ translate('check_All_CartList') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="checkout-section pt-4 section-gap">
    <div class="container">
        <h3 class="mb-3 mb-lg-4 d-flex justify-content-center justify-content-sm-start">{{translate('checkout')}}</h3>
        <div class="row g-4">
            <div class="col-lg-7 col-xl-8">
                <ul class="checkout-flow">
                    <li class="checkout-flow-item active">
                        <a href="javascript:">
                            <span class="serial">{{ translate('1') }}</span>
                            <span class="icon">
                                <i class="bi bi-check"></i>
                            </span>
                            <span class="text thisIsALinkElement" data-linkpath="{{route('shop-cart')}}">{{translate('cart')}}</span>
                        </a>
                    </li>
                    <li class="line"></li>
                    <li class="checkout-flow-item active current">
                        <a href="javascript:">
                            <span class="serial">{{ translate('2') }}</span>
                            <span class="icon">
                                <i class="bi bi-check"></i>
                            </span>
                            <span class="text text-capitalize">{{translate('shipping_details')}}</span>
                        </a>
                    </li>
                    <li class="line"></li>
                    <li class="checkout-flow-item">
                        <a href="javascript:">
                            <span class="serial">{{ translate('3') }}</span>
                            <span class="icon">
                                <i class="bi bi-check"></i>
                            </span>
                            <span class="text">{{translate('payment')}}</span>
                        </a>
                    </li>
                </ul>

                @php
                    $hasPhysical = $hasPhysicalProduct ?? false;
                    $hasDigital = $hasDigitalProduct ?? false;

                    $formTitle = '';
                    if ($hasPhysical && !$hasDigital) {
                        $formTitle = translate('Shipping_Address');
                    } elseif (!$hasPhysical && $hasDigital) {
                        $formTitle = translate('Billing_Address');
                    } elseif ($hasPhysical && $hasDigital) {
                        $formTitle = translate('Billing_and_Shipping_Address');
                    }
                @endphp

                <input type="hidden" id="has_physical_product" value="{{ $hasPhysical ? 'true' : 'false' }}">
                <input type="hidden" id="has_digital_product" value="{{ $hasDigital ? 'true' : 'false' }}">
                <input type="hidden" id="physical_product" name="physical_product" value="{{ $hasPhysical ? 'yes':'no'}}">
                <input type="hidden" id="billing_input_enable" name="billing_input_enable" value="{{ $billing_input_by_customer }}">

                <div class="delivery-information">
                    <h4 class="font-bold letter-spacing-0 title text-capitalize mb-20px">
                        {{ translate('delivery_information_details') }}
                    </h4>
                </div>

                @if($hasPhysical || $hasDigital)
                    <form method="post" id="address-form">
                        {{-- Moved Latitude and Longitude hidden inputs here from the removed modal --}}
                        <input type="hidden" id="latitude" name="latitude" value="{{$default_location?$default_location['lat']:0}}">
                        <input type="hidden" id="longitude" name="longitude" value="{{$default_location?$default_location['lng']:0}}">

                        <div class="delivery-information mt-32px">
                            <div class="d-flex flex-wrap row-gap-3 column-gap-4 mb-20px align-items-end">
                                <div class="font-bold letter-spacing-0 title m-0 text-capitalize">{{ $formTitle }}</div>
                                @if(auth('customer')->check())
                                    <div class="ms-auto text-base text-capitalize" type="button" data-bs-target="#shipping_addresses" data-bs-toggle="modal">
                                        {{translate('select_from_saved')}}
                                    </div>
                                @endif
                                @if(getWebConfig('map_api_status')== 1)
                                    <div id="auto-fill-location-btn" class="@if(!auth('customer')->check()) ms-auto @endif text-base text-capitalize cursor-pointer">
                                        {{translate('auto_select_current_location')}} <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-@if(auth('customer')->check()) '6' @else '12' @endif">
                                    <input type="text" placeholder="{{translate('contact_person_name')}}" id="name" name="contact_person_name" class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                </div>
                                <div class="col-sm-6">
                                    <input type="tel" placeholder="{{translate('phone')}}" id="phone_number" class="form-control phone-input-with-country-picker-shipping" {{$shipping_addresses->count()==0?'required':''}}>
                                    <input type="hidden" class="country-picker-phone-number-shipping w-50" name="phone" id="phone_hidden" readonly>
                                </div>
                                @if(!auth('customer')->check())
                                    <div class="col-sm-6">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ translate('email') }}" required>
                                    </div>
                                @endif
                                <div class="col-sm-6">
                                    <select name="country" id="country" class="form-control select2-init" required>
                                        <option value="">{{translate('select_country')}}</option>
                                        @forelse($countries as $country)
                                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                        @empty
                                            <option value="">{{translate('no_country_to_deliver') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="city" id="city" placeholder="{{translate('city')}}" class="form-control"  {{$shipping_addresses->count()==0?'required':''}}>
                                </div>

                                <div class="col-sm-12">
                                    <select name="address_type" id="address_type" class="form-select form-control ">
                                        <option value="permanent">{{ translate('permanent')}}</option>
                                        <option value="home">{{ translate('home')}}</option>
                                        <option value="others">{{ translate('others')}}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    {{-- Removed Compass Icon from here --}}
                                    <input type="text" name="address" id="address" placeholder="{{translate('address')}}" class="form-control" {{$shipping_addresses->count()==0?'required':''}} autocomplete="off">
                                </div>
                                <div class="col-sm-12" >
                                    <label class="form-check m-0" id="save_address_label">
                                        <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="0">
                                        @if(auth('customer')->check())
                                            <input type="checkbox" name="save_address" id="save_address" class="form-check-input dark-form-check-input" checked>
                                            <span class="form-check-label">{{translate('save_this_address')}}</span>
                                        @endif
                                    </label>

                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                

                <div style="display:none !important">
                    <form id="billing-address-form"></form>
                </div>
            </div>
            <div class="col-lg-5 col-xl-4">
                @include('theme-views.partials._order-summery')
            </div>

        </div>
    </div>
</section>

<div class="modal fade" id="shipping_addresses">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Saved_Addresses') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mapouter">
                    <div class="row ">
                        @if (auth('customer')->check() && $shipping_addresses->count()>0)
                            @foreach($shipping_addresses as $key=>$address)
                                <div class="col-md-12">
                                    <div class="address-card mb-20px ">
                                        <div class="address-card-header bg-transparent d-flex justify-content-between align-items-center">
                                            <label class="d-flex align-items-start gap-3 cursor-pointer mb-0 w-100">
                                                <input class="s-16px form-check-input mt-1" type="radio" name="shipping_method_id" value="{{$address['id']}}" {{$key==0?'checked':''}}>
                                                <div class="__border-base"></div>
                                                <div class="w-0 flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center column-gap-4">
                                                        <h6 class="text-capitalize">{{$address['address_type']}}</h6>
                                                        <a href="{{route('address-edit',$address->id)}}" >
                                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit.png') }}" alt="{{ translate('edit') }}">
                                                        </a>
                                                    </div>
                                                    <div class="address-card-body pb-0 px-0 text-start">
                                                        <ul>
                                                            <li>
                                                                <span class="form--label w-70px">{{ translate('name') }}</span>
                                                                <span class="info ps-2 shipping-contact-person">{{$address['contact_person_name']}}</span>
                                                            </li>
                                                            <li>
                                                                <span class="form--label w-70px">{{ translate('phone') }}</span>
                                                                <span class="info ps-2 shipping-contact-phone">{{$address['phone']}}</span>
                                                            </li>
                                                            <li>
                                                                <span class="form--label w-70px">{{ translate('address') }}</span>
                                                                <span class="info ps-2 shipping-contact-address">{{$address['address']}}</span>
                                                            </li>
                                                            <span class="shipping-contact-address d-none">{{ $address['address'] }}</span>
                                                            <span class="shipping-contact-city d-none">{{ $address['city'] }}</span>
                                                            <span class="shipping-contact-country d-none">{{ $address['country'] }}</span>
                                                            <span class="shipping-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/address.svg') }}" alt="{{ translate('address') }}" class="w-25">
                                <h5 class="my-3 pt-1 text-muted">
                                    {{ translate('no_address_is_saved') }}!
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (auth('customer')->check() && $shipping_addresses->count() > 0)
                <div class="modal-footer p-3">
                    <button type="button" class="btn rounded btn-reset text-title"
                        data-bs-dismiss="modal">{{translate('close')}}</button>
                    <button type="button" data-bs-dismiss="modal" class="btn rounded btn-base">
                        {{translate('select')}}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>


<div class="modal fade" id="billing_addresses">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize">{{translate('saved_addresses')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mapouter">
                    <div class="row ">
                        @if (auth('customer')->check() && $billing_addresses->count()>0)
                         @foreach($billing_addresses as $key=>$address)
                            <div class="col-md-12">
                                <div class="address-card mb-20px ">
                                    <div class="address-card-header bg-transparent d-flex justify-content-between align-items-center">
                                        <label class="d-flex align-items-start gap-3 cursor-pointer mb-0 w-100">
                                            <input class="s-16px form-check-input mt-1" type="radio" name="billing_method_id" {{$key==0?'checked':''}} value="{{$address['id']}}" >
                                            <div class="__border-base"></div>
                                            <div class="w-0 flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center column-gap-4">
                                                    <h6 class="text-capitalize">{{$address['address_type']}}</h6>
                                                    <a href="{{route('address-edit',$address->id)}}" >
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit.png') }}" alt="{{ translate('edit') }}">
                                                    </a>
                                                </div>
                                                <div class="address-card-body pb-0 px-0 text-start">
                                                    <ul>
                                                        <li>
                                                            <span class="form--label w-70px">{{ translate('name') }}</span>
                                                            <span class="info ps-2 billing-contact-name">{{$address['contact_person_name']}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="form--label w-70px">{{ translate('phone') }}</span>
                                                            <span class="info ps-2 billing-contact-phone">{{$address['phone']}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="form--label w-70px">{{ translate('address') }}</span>
                                                            <span class="info ps-2 billing-contact-address">{{$address['address']}}</span>
                                                        </li>
                                                        <span class="billing-contact-city d-none">{{ $address['city'] }}</span>
                                                        <span class="billing-contact-country d-none">{{ $address['country'] }}</span>
                                                        <span class="billing-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                    </ul>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                            <div class="text-center">
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/address.svg') }}" alt="{{ translate('address') }}" class="w-25">
                                <h5 class="my-3 pt-1 text-muted">
                                        {{translate('no_address_is_saved')}}!
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (auth('customer')->check() && $billing_addresses->count()>0)
                <div class="modal-footer p-3">
                    <button type="button" class="btn rounded btn-reset text-title"
                        data-bs-dismiss="modal">{{translate('close')}}</button>
                    <button type="button" data-bs-dismiss="modal" class="btn rounded btn-base">
                        {{ translate('select') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>


<span id="shippingaddress-storage"
    data-latitude="{{ $default_location ? ($default_location['lat'] ?? 0) : '-33.8688' }}"
    data-longitude="{{ $default_location ? ($default_location['lng'] ?? 0) : '151.2195' }}">
</span>

@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/shipping-page.js') }}"></script>
    @if(getWebConfig('map_api_status') == 1)
       <script
    src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=mapsShopping&loading=async&libraries=places&v=weekly"
    defer>
</script>
    @endif

    <script>
        // Override the mapsShopping function from shipping-page.js to prevent errors
        // since the map canvas and pac-input elements have been removed.
        function mapsShopping() {
            // This function is intentionally left empty.
        }

        $(document).ready(function() {
            $('#auto-fill-location-btn').on('click', function() {
                if (navigator.geolocation) {
                    toastr.info('Fetching your current location...', 'Please wait');
                    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
                } else {
                    toastr.error('Geolocation is not supported by this browser.', 'Error');
                }
            });

            function successCallback(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                $('#latitude').val(lat);
                $('#longitude').val(lng);

                const geocoder = new google.maps.Geocoder();
                const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };

                geocoder.geocode({ 'location': latlng }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            let city = '';
                            let country = '';
                            let fullAddress = results[0].formatted_address;

                            for (const component of results[0].address_components) {
                                if (component.types.includes("country")) {
                                    country = component.long_name;
                                }
                                if (component.types.includes("locality")) {
                                    city = component.long_name;
                                }
                                if (city === '' && component.types.includes('administrative_area_level_1')) {
                                    city = component.long_name;
                                }
                            }

                            $('#address').val(fullAddress);
                            $('#city').val(city);
                            $('#country').val(country).trigger('change');

                            toastr.success('Location has been set successfully!', 'Success');
                        } else {
                            toastr.error('No results found for your location.', 'Error');
                        }
                    } else {
                        toastr.error('Geocoder failed due to: ' + status, 'Error');
                    }
                });
            }

            function errorCallback(error) {
                let errorMessage = 'An unknown error occurred.';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'You have denied the request for Geolocation.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'The request to get user location timed out.';
                        break;
                }
                toastr.error(errorMessage, 'Location Error');
            }
        });
    </script>
@endpush