<link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/pos-invoice.css') }}">
<?php
$orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order);
?>
<div class="width-363px">
    <div class="text-center pt-4 mb-3">
        <h2 class="line-height-1">{{ getWebConfig('company_name') }}</h2>
        <h5 class="line-height-1 font-size-16px">
            {{ translate('phone') }} : {{ getWebConfig('company_phone') }}
        </h5>
    </div>

    <span class="dashed-hr"></span>
    <div class="row mt-3">
        <div class="col-6">
            <h5>{{ translate('order_ID') }} : {{ $order['id'] }}</h5>
        </div>
        <div class="col-6">
            <h5 class="">
                {{ date('d/M/Y h:i a', strtotime($order['created_at'])) }}
            </h5>
        </div>
        @if($order->customer)
            <div class="col-12">
                <h5 class="text-capitalize">{{ translate('customer_name') }} : {{$order->customer['f_name'].' '.$order->customer['l_name']}} </h5> <h5>DDS ID: {{strtoupper($order->customer['username'])}}</h5>
                @if ($order->customer->id !=0)
                    <h5>{{ translate('phone') }} : {{$order->customer['phone']}}</h5>
                @endif

            </div>
        @endif
    </div>
    <h5 class="text-uppercase"></h5>
    <span class="dashed-hr"></span>
    <table class="table table-bordered mt-3 text-left width-99">
        <thead>
        <tr>
            <th class="text-center text-uppercase">{{ translate('qty') }}</th>
            <th class="text-left text-uppercase">{{ translate('desc') }}</th>
            <th class="text-center text-uppercase">{{ translate('PV') }}</th>
            <th class="text-center text-uppercase">{{ translate('BV') }}</th>
            <th class="text-center">{{ translate('price') }}</th>
        </tr>
        </thead>

        <tbody>
        @php($sub_total=0)
        @php($total_tax=0)
        @php($total_dis_on_pro=0)
        @php($product_price=0)
        @php($total_product_price=0)
        @php($ext_discount=0)
        @php($coupon_discount=0)
        @php($total_pv = 0)
        @php($total_bv = 0)
        @foreach($order->details as $detail)
            @if($detail->product)
                <tr>
                    <td class="text-left">
                        {{$detail['qty']}}
                    </td>
                    <td class="text-left">
                        <span> {{ Str::limit($detail->product['name'], 200) }}</span><br>
                        @if($detail->product->product_type == 'physical' && count(json_decode($detail['variation'],true))>0)
                            <strong><u>{{ translate('variation') }} : </u></strong>
                            @foreach(json_decode($detail['variation'],true) as $key1 =>$variation)
                                <div class="font-size-sm text-body color-black">
                                    <span>{{ translate($key1) }} :  </span>
                                    <span
                                        class="font-weight-bold">{{$variation}} </span>
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">
                        {{($detail->product['pv'] ?? 0) * $detail['qty']}}
                    </td>
                     <td class="text-center">
                        {{($detail->product['bv'] ?? 0) * $detail['qty']}}
                    </td>
                    <td class="text-right">
                        @php($amount=$detail['price']*$detail['qty'])
                        @php($product_price = $detail['price']*$detail['qty'])
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $amount), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                @php($sub_total+=$amount)
                @php($total_product_price+=$product_price)
                @php($total_tax+=$detail['tax'])
                @php($total_pv += ($detail->product['pv'] ?? 0) * $detail['qty'])
                @php($total_bv += ($detail->product['bv'] ?? 0) * $detail['qty'])
            @endif
        @endforeach
        </tbody>
    </table>
    <span class="dashed-hr"></span>

    <table class="w-100 color-black">
        <tr>
            <td colspan="3"></td>
            <td class="text-right">{{ translate('items_Price') }}:</td>
            <td class="text-right">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['itemPrice']), currencyCode: getCurrencyCode()) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-right">{{ translate('subtotal') }}:</td>
            <td class="text-right">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $sub_total), currencyCode: getCurrencyCode()) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-right">{{ translate('total_PV') }}:</td>
            <td class="text-right">{{$total_pv}}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-right">{{ translate('total_BV') }}:</td>
            <td class="text-right">{{$total_bv}}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-right font-size-20px">
                {{ translate('total') }}:
            </td>
            <td class="text-right font-size-20px">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['totalAmount']), currencyCode: getCurrencyCode()) }}
            </td>
        </tr>
        @if ($order->order_type == 'pos' || $order->order_type == 'POS')
            <tr>
                <td colspan="5">
                    <span class="dashed-hr"></span>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-right">
                    {{ translate('Paid_Amount') }}:
                </td>
                <td class="text-right">
                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['paidAmount']), currencyCode: getCurrencyCode()) }}
                </td>
            </tr>

            <tr>
                <td colspan="3"></td>
                <td class="text-right">
                    {{ translate('Change_Amount') }}:
                </td>
                <td class="text-right">
                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['changeAmount']), currencyCode: getCurrencyCode()) }}
                </td>
            </tr>
        @endif
    </table>


    <div class="d-flex flex-row justify-content-between border-top">
        <span>{{ translate('paid_by') }}: {{ translate($order->payment_method) }}</span>
    </div>
    <span class="dashed-hr"></span>
    <h5 class="text-center pt-3 text-uppercase">
        """{{ translate('thank_you') }}"""
    </h5>
    <span class="dashed-hr"></span>
</div>