@extends('layouts.admin.app')

@section('title', translate('Shipping Setup'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <i class="fi fi-sr-truck-side"></i>
                {{ translate('shipping_setup') }}
            </h2>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="tio-settings-outlined"></i>
                    {{translate('Shipping Weight Settings')}}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.shipping-setup.store') }}" method="post">
                    @csrf
                    <div class="p-12 p-sm-20 bg-section rounded">
                        <div class="form-group mb-20">
                            <div class="bg-white p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0">{{translate('Shipping by Weight')}}</h6>
                                        <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                              data-bs-title="{{ translate('Enable this to calculate shipping cost based on the total weight of the order.') }}">
                                            <i class="fi fi-sr-info"></i>
                                        </span>
                                    </div>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="shipping_by_weight"
                                               id="shipping_by_weight_status" value="1" {{ $shipping->shipping_by_weight ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="free_shipping_over_weight">{{translate('Free shipping Over Weight')}} ({{translate('in KG')}})
                                        <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                              data-bs-title="{{ translate('Set a weight limit (in KG). Orders exceeding this weight will have free shipping.') }}">
                                            <i class="fi fi-sr-info"></i>
                                        </span>
                                    </label>
                                    <input type="number" step="0.01" class="form-control" name="free_shipping_over_weight"
                                           id="free_shipping_over_weight" value="{{ old('free_shipping_over_weight', $shipping->free_shipping_over_weight) }}" placeholder="{{translate('Ex: 10')}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="shipping_cost">{{translate('Default Shipping Cost')}}
                                         <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                              data-bs-title="{{ translate('This is the base shipping cost for orders that do not qualify for free shipping.') }}">
                                            <i class="fi fi-sr-info"></i>
                                        </span>
                                    </label>
                                    <input type="number" step="0.01" class="form-control" name="shipping_cost"
                                           id="shipping_cost" value="{{ old('shipping_cost', $shipping->shipping_cost) }}" placeholder="{{translate('Ex: 5')}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="shipping_cost_over_weight">{{translate('Shipping Cost Per KG')}}
                                         <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                              data-bs-title="{{ translate('Set the additional shipping cost for each KG of weight.') }}">
                                            <i class="fi fi-sr-info"></i>
                                        </span>
                                    </label>
                                    <input type="number" step="0.01" class="form-control" name="shipping_cost_over_weight"
                                           id="shipping_cost_over_weight" value="{{ old('shipping_cost_over_weight', $shipping->shipping_cost_over_weight) }}" placeholder="{{translate('Ex: 2')}}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end flex-wrap gap-3 mt-4">
                            <button type="reset" class="btn btn-secondary px-4 w-120">{{ translate('Reset') }}</button>
                            <button type="submit" class="btn btn-primary px-4 w-120">{{ translate('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF TOOLTIPS
        // =======================================================
        $('.tooltip-icon').tooltip();
    });
</script>
@endpush
