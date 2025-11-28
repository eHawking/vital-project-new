"use strict";

$(document).ready(function () {
    getVariantPrice(".add-to-cart-details-form");
    actionRequestForProductRestockFunctionality();
});

$('.add-to-cart-details-form input').on('change', function () {
    getVariantPrice(".add-to-cart-details-form");
});

$('.add-to-cart-details-form').on('submit', function (e) {
    e.preventDefault();
});


$('.addCompareList_quick_view').on('click', function () {
    let id = $(this).data('id');
    addCompareList(id);
});

$('.addWishlist_function_btn').on('click', function () {
    let productId = $('#quick-view-product-id').data('product-id');
    addWishlist_function(productId);
});

$('.product-add-to-cart-button').on('click', function () {
    let parentElement = $(this).closest('.product-cart-option-container');
    let productCartForm = parentElement.find('.addToCartDynamicForm')
    addToCart(productCartForm);
});

$('.product-buy-now-button').on('click', function (e) {
    // Check for referral link first
    const referralUsername = localStorage.getItem('referralUsername');
    const referralPosition = localStorage.getItem('referralPosition');
    let redirectStatus = $(this).data("auth").toString();
    
    // If guest user with referral, redirect to register page
    if (redirectStatus === "false" && referralUsername && referralPosition) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        // Create form for reliable redirect
        const registerUrl = window.location.origin + '/account/user/register';
        const form = $('<form>', {
            'method': 'GET',
            'action': registerUrl
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': 'ref',
            'value': referralUsername
        }));
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': 'position',
            'value': referralPosition
        }));
        
        $('body').append(form);
        console.log('Quick View Buy Now: Redirecting to register with referral');
        form.submit();
        return false;
    }
    
    // Normal buy now flow
    let url = $(this).data("route");
    let parentElement = $(this).closest('.product-cart-option-container');
    let productCartForm = parentElement.find('.addToCartDynamicForm')
    addToCart(productCartForm, redirectStatus, url);
    if(redirectStatus === "false") {
        $("#quickViewModal").modal("hide");
        customerLoginRegisterModalCall()
        toastr.warning($('.login-warning').data('login-warning-message'));
    }
});
