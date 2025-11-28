@extends('payment.layouts.master')

@section('content')
    <div class="card shadow p-4 mt-5">
        <div class="row">
            <div class="col-12">
                <h3 id="statusMessage">Request is being processed, please wait...</h3>
                <input id="Key1" name="Key1" type="hidden" value="{{ $config->key_1 }}">
                <input id="Key2" name="Key2" type="hidden" value="{{ $config->key_2 }}">

                <form action="https://payments.bankalfalah.com/HS/HS/HS" id="HandshakeForm" method="post">
                    <input id="HS_RequestHash" name="HS_RequestHash" type="hidden" value="">
                    <input id="HS_IsRedirectionRequest" name="HS_IsRedirectionRequest" type="hidden" value="0">
                    <input id="HS_ChannelId" name="HS_ChannelId" type="hidden" value="{{ $config->channel_id }}">
                    <input id="HS_ReturnURL" name="HS_ReturnURL" type="hidden" value="{{ $config->return_URL }}">
                    <input id="HS_MerchantId" name="HS_MerchantId" type="hidden" value="{{ $config->merchant_id }}">
                    <input id="HS_StoreId" name="HS_StoreId" type="hidden" value="{{ $config->store_id }}">
                    <input id="HS_MerchantHash" name="HS_MerchantHash" type="hidden" value="{{ $config->merchant_hash }}">
                    <input id="HS_MerchantUsername" name="HS_MerchantUsername" type="hidden" value="{{ $config->merchant_username }}">
                    <input id="HS_MerchantPassword" name="HS_MerchantPassword" type="hidden" value="{{ $config->merchant_password }}">
                    <input id="HS_TransactionReferenceNumber" name="HS_TransactionReferenceNumber" type="hidden" value="{{ $data->id }}">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="https://payments.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post">
                    <input id="AuthToken" name="AuthToken" type="hidden" value="">
                    <input id="RequestHash" name="RequestHash" type="hidden" value="">
                    <input id="HS_IsRedirectionRequest" name="HS_IsRedirectionRequest" type="hidden" value="0">
                    <input id="ChannelId" name="ChannelId" type="hidden" value="{{ $config->channel_id }}">
                    <input id="Currency" name="Currency" type="hidden" value="PKR">
                    <input id="ReturnURL" name="ReturnURL" type="hidden" value="{{ $config->return_URL }}">
                    <input id="MerchantId" name="MerchantId" type="hidden" value="{{ $config->merchant_id }}">
                    <input id="StoreId" name="StoreId" type="hidden" value="{{ $config->store_id }}">
                    <input id="MerchantHash" name="MerchantHash" type="hidden" value="{{ $config->merchant_hash }}">
                    <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="{{ $config->merchant_username }}">
                    <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="{{ $config->merchant_password }}">
                    <input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="3">
                    <input id="TransactionReferenceNumber" name="TransactionReferenceNumber" type="hidden" value="{{ $data->id }}">
                    <input id="TransactionAmount" name="TransactionAmount" type="hidden" value="{{ $data->payment_amount }}">
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            window.onload = function() {
                submitRequest("HandshakeForm");

                var myData = {
                    HS_MerchantId: $("#HS_MerchantId").val(),
                    HS_StoreId: $("#HS_StoreId").val(),
                    HS_MerchantHash: $("#HS_MerchantHash").val(),
                    HS_MerchantUsername: $("#HS_MerchantUsername").val(),
                    HS_MerchantPassword: $("#HS_MerchantPassword").val(),
                    HS_IsRedirectionRequest: $("#HS_IsRedirectionRequest").val(),
                    HS_ReturnURL: $("#HS_ReturnURL").val(),
                    HS_RequestHash: $("#HS_RequestHash").val(),
                    HS_ChannelId: $("#HS_ChannelId").val(),
                    HS_TransactionReferenceNumber: $("#HS_TransactionReferenceNumber").val(),
                };

                $.ajax({
                    type: 'POST',
                    url: 'https://payments.bankalfalah.com/HS/HS/HS',
                    contentType: "application/x-www-form-urlencoded",
                    data: myData,
                    dataType: "json",
                    success: function(r) {
                        if (r && r.success == "true") {
                            $("#AuthToken").val(r.AuthToken);
                            $("#ReturnURL").val(r.ReturnURL);
                            submitRequest("PageRedirectionForm");
                            document.getElementById("PageRedirectionForm").submit();
                        } else {
                            $("#statusMessage").text('Something went wrong, please try again.');
                        }
                    },
                    error: function() {
                        $("#statusMessage").text('An error occurred, please try again.');
                    }
                });
            };
        });

        function submitRequest(formName) {
            var mapString = '';
            var hashName = formName == "HandshakeForm" ? 'HS_RequestHash' : 'RequestHash';

            $("#" + formName + " :input").each(function() {
                if ($(this).attr('id') !== '') {
                    mapString += $(this).attr('id') + '=' + $(this).val() + '&';
                }
            });

            $("#" + hashName).val(CryptoJS.AES.encrypt(
                CryptoJS.enc.Utf8.parse(mapString.slice(0, -1)),
                CryptoJS.enc.Utf8.parse("{{ $config->key_1 }}"), {
                    keySize: 128 / 8,
                    iv: CryptoJS.enc.Utf8.parse("{{ $config->key_2 }}"),
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7
                }
            ));
        }
    </script>
@endpush