"use strict";

$(document).ready(function() {
    // Keep saved address selection logic
    if($('input[name="shipping_method_id"]').is(':checked')){
        let cardBody = $('[name="shipping_method_id"]:checked').parents('.address-card').find('.address-card-body');
        shipping_method_select(cardBody);
    }
    if($('input[name="billing_method_id"]').is(':checked')){
        let cardBody = $('[name="billing_method_id"]:checked').parents('.address-card').find('.address-card-body');
        billing_method_select(cardBody);
    }

    // Keep phone number initialization
    try {
        initializePhoneInput(".phone-input-with-country-picker-shipping", ".country-picker-phone-number-shipping");
    } catch (error) {}

    try {
        initializePhoneInput(".phone-input-with-country-picker-billing", ".country-picker-phone-number-billing");
    } catch (error) {}

});

$('[name="shipping_method_id"]').on('change', function(){
    let cardBody = $(this).parents('.address-card').find('.address-card-body');
    shipping_method_select(cardBody);
});


$('[name="billing_method_id"]').on('change', function(){
    let cardBody = $(this).parents('.address-card').find('.address-card-body');
    billing_method_select(cardBody);
});

function shipping_method_select(cardBody){
    let update_this_address = $('.text-custom-storage').data('update-this-address');
    let shipping_method_id = $('[name="shipping_method_id"]:checked').val();
    let shipping_person = cardBody.find('.shipping-contact-person').text();
    let shipping_phone = cardBody.find('.shipping-contact-phone').text();
    let shipping_address = cardBody.find('.shipping-contact-address').text();
    let shipping_city = cardBody.find('.shipping-contact-city').text();
    let shipping_country = cardBody.find('.shipping-contact-country').text();
    let shipping_contact_address_type = cardBody.find('.shipping-contact-address_type').text();
    let update_address = `
        <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="${shipping_method_id}">
        <input type="checkbox" name="update_address" id="update_address" class="form-check-input dark-form-check-input"> ${update_this_address}`;

    $('#name').val(shipping_person);
    $('#phone_number').val(shipping_phone);
    $('#phone_hidden').val(shipping_phone);
    $('#address').val(shipping_address);
    $('#city').val(shipping_city);
    $('#country').val(shipping_country).trigger('change');
    $('#address_type').val(shipping_contact_address_type);
    $('#save_address_label').html(update_address);
}

function billing_method_select(cardBody){
    let update_this_address = $('.text-custom-storage').data('update-this-address');
    let billing_method_id = $('[name="billing_method_id"]:checked').val();
    let billing_person = cardBody.find('.billing-contact-name').text();
    let billing_phone = cardBody.find('.billing-contact-phone').text();
    let billing_address = cardBody.find('.billing-contact-address').text();
    let billing_city = cardBody.find('.billing-contact-city').text();
    let billing_country = cardBody.find('.billing-contact-country').text();
    let billing_contact_address_type = cardBody.find('.billing-contact-address_type').text();
    let update_address_billing = `
        <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="${billing_method_id}">
        <input type="checkbox" name="update_address" id="update_address" class="form-check-input dark-form-check-input"> ${update_this_address}`;

    $('#name').val(billing_person);
    $('#phone_number').val(billing_phone);
    $('#phone_hidden').val(billing_phone);
    $('#address').val(billing_address);
    $('#city').val(billing_city);
    $('#country').val(billing_country).trigger('change');
    $('#address_type').val(billing_contact_address_type);
    $('#save_address_label').html(update_address_billing);
}


$(document).on("keydown", "input", function(e) {
    if (e.which==13) e.preventDefault();
});

$('#proceed_to_next_action').on('click', function(){
    let has_physical = $('#has_physical_product').val() === 'true';

    let allAreFilled = true;
    if ($('#address-form').length) {
        document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
            if (!allAreFilled) return;
            if (!i.value) allAreFilled = false;
            if (i.type === "radio") {
                let radioValueCheck = false;
                document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                    if (r.checked) radioValueCheck = true;
                });
                allAreFilled = radioValueCheck;
            }
        });
    }

    if(!allAreFilled){
        toastr.error('Please fill all required fields');
        return;
    }

    let billing_addresss_same_shipping = has_physical;
    let redirect_url = $(this).data('checkoutpayment');
    let form_url = $(this).data('gotocheckout');

    let isCheckCreateAccount = $('#is_check_create_account');
    let customerPassword = $('#customer_password');
    let customerConfirmPassword = $('#customer_confirm_password');

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.post({
        url: form_url,
        data: {
            physical_product: has_physical ? 'yes' : 'no',
            shipping: $('#address-form').serialize(),
            billing: '',
            billing_addresss_same_shipping: billing_addresss_same_shipping,
            is_check_create_account: isCheckCreateAccount && isCheckCreateAccount.prop("checked") ? 1 : 0,
            customer_password: customerPassword ? customerPassword.val() : null,
            customer_confirm_password: customerConfirmPassword ? customerConfirmPassword.val() : null,
        },

        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            console.log(data)
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                location.href = redirect_url;
            }
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
        error: function (data) {
            let error_msg = data.responseJSON.errors;
            toastr.error(error_msg, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});

$('#is_check_create_account').on('change', function() {
    if($(this).is(':checked')) {
        $('.is_check_create_account_password_group').fadeIn();
    } else {
        $('.is_check_create_account_password_group').fadeOut();
    }
});