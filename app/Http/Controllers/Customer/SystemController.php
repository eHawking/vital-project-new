<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\CartShipping;
use App\Traits\CommonTrait;
use App\Utils\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SecondaryUser;
use App\Models\Cart;

class SystemController extends Controller
{
    use CommonTrait;

    public function setPaymentMethod($name): JsonResponse
    {
        if (auth('customer')->check() || session()->has('mobile_app_payment_customer_id')) {
            session()->put('payment_method', $name);
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);
    }

    public function setShippingMethod(Request $request): JsonResponse
    {
        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids() as $groupId) {
                $request['cart_group_id'] = $groupId;
                self::insertIntoCartShipping($request);
            }
        } else {
            self::insertIntoCartShipping($request);
        }
        return response()->json(['status' => 1]);
    }

    public static function insertIntoCartShipping($request): void
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $request['id'];
        $shipping['shipping_cost'] = ShippingMethod::find($request['id'])->cost;
        $shipping->save();
    }

    /*
     * default theme
     * @return json
     */
    public function getChooseShippingAddress(Request $request): JsonResponse
    {
        $zip_restrict_status = false; // MODIFICATION
        $country_restrict_status = getWebConfig(name: 'delivery_country_restriction');

        $physical_product = $request['physical_product'];
        $shipping = [];
        $billing = [];

        parse_str($request['shipping'], $shipping);
        parse_str($request['billing'], $billing);
        $is_guest = !auth('customer')->check();

        if (isset($shipping['save_address']) && $shipping['save_address'] == 'on') {

            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null || $shipping['country'] == null || ($is_guest && $shipping['email'] == null)) {
                return response()->json([
                    'errors' => translate('Fill_all_required_fields_of_shipping_address')
                ], 403);
            }
            elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping['country'])) {
                return response()->json([
                    'errors' => translate('Delivery_unavailable_in_this_country.')
                ], 403);
            }

            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => auth('customer')->id() ?? ((session()->has('guest_id') ? session('guest_id'):0)),
                'is_guest' => auth('customer')->check() ? 0 : (session()->has('guest_id') ? 1:0),
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'zip' => null,
                'country' => $shipping['country'],
                'phone' => $shipping['phone'],
                'email' => auth('customer')->check() ? null : $shipping['email'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
        else if (isset($shipping['shipping_method_id']) && $shipping['shipping_method_id'] == 0) {

            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null || $shipping['country'] == null || ($is_guest && $shipping['email'] == null)) {
                return response()->json([
                    'errors' => translate('Fill_all_required_fields_of_shipping/billing_address')
                ], 403);
            }
            elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping['country'])) {
                return response()->json([
                    'errors' => translate('Delivery_unavailable_in_this_country')
                ], 403);
            }

            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => auth('customer')->id() ?? ((session()->has('guest_id') ? session('guest_id'):0)),
                'is_guest' => auth('customer')->check() ? 0 : (session()->has('guest_id') ? 1:0),
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'zip' => null,
                'country' => $shipping['country'],
                'phone' => $shipping['phone'],
                'email' => auth('customer')->check() ? null : $shipping['email'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        else {
            if (isset($shipping['shipping_method_id'])) {
                $address = ShippingAddress::find($shipping['shipping_method_id']);
                if (!$address->country) {
                    return response()->json([
                        'errors' => translate('Please_update_country_for_this_shipping_address')
                    ], 403);
                }
                elseif ($country_restrict_status && !self::delivery_country_exist_check($address->country)) {
                    return response()->json([
                        'errors' => translate('Delivery_unavailable_in_this_country')
                    ], 403);
                }
                $address_id = $shipping['shipping_method_id'];
            }else{
                $address_id =  0;
            }
        }

        if ($request->billing_addresss_same_shipping == 'false') {
            if (isset($billing['save_address_billing']) && $billing['save_address_billing'] == 'on') {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null || $billing['billing_country'] == null || ($is_guest && $billing['billing_contact_email'] == null)) {
                    return response()->json([
                        'errors' => translate('Fill_all_required_fields_of_billing_address')
                    ], 403);
                }
                elseif ($country_restrict_status && !self::delivery_country_exist_check($billing['billing_country'])) {
                    return response()->json([
                        'errors' => translate('Delivery_unavailable_in_this_country')
                    ], 403);
                }

                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => auth('customer')->id() ?? ((session()->has('guest_id') ? session('guest_id'):0)),
                    'is_guest' => auth('customer')->check() ? 0 : (session()->has('guest_id') ? 1:0),
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],
                    'city' => $billing['billing_city'],
                    'zip' => null,
                    'country' => $billing['billing_country'],
                    'phone' => $billing['billing_phone'],
                    'email' => auth('customer')->check() ? null : $billing['billing_contact_email'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


            }
            elseif ($billing['billing_method_id'] == 0) {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null || $billing['billing_country'] == null || ($is_guest && $billing['billing_contact_email'] == null)) {
                    return response()->json([
                        'errors' => translate('Fill_all_required_fields_of_billing_address')
                    ], 403);
                }
                elseif ($country_restrict_status && !self::delivery_country_exist_check($billing['billing_country'])) {
                    return response()->json([
                        'errors' => translate('Delivery_unavailable_in_this_country')
                    ], 403);
                }

                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => auth('customer')->id() ?? ((session()->has('guest_id') ? session('guest_id'):0)),
                    'is_guest' => auth('customer')->check() ? 0 : (session()->has('guest_id') ? 1:0),
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],
                    'city' => $billing['billing_city'],
                    'zip' => null,
                    'country' => $billing['billing_country'],
                    'phone' => $billing['billing_phone'],
                    'email' => auth('customer')->check() ? null : $billing['billing_contact_email'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            else {
                $address = ShippingAddress::find($billing['billing_method_id']);
                if ($physical_product == 'yes') {
                    if (!$address->country) {
                        return response()->json([
                            'errors' => translate('Update_country_for_this_billing_address')
                        ], 403);
                    }
                    elseif ($country_restrict_status && !self::delivery_country_exist_check($address->country)) {
                        return response()->json([
                            'errors' => translate('Delivery_unavailable_in_this_country')
                        ], 403);
                    }
                }
                $billing_address_id = $billing['billing_method_id'];
            }
        }
        else {
            $billing_address_id = $address_id;
        }

        session()->put('address_id', $address_id);
        session()->put('billing_address_id', $billing_address_id);

        return response()->json([], 200);
    }

    public function getChooseShippingAddressOther(Request $request): JsonResponse
    {
        // <<< START: New Active BTP User Check >>>
        $user = auth('customer')->user();
        if ($user && isset($user->username)) {
            $secondaryUser = SecondaryUser::where('username', $user->username)->first();

            if ($secondaryUser && $secondaryUser->is_btp == 1) {
                $checkedCartItems = CartManager::getCartListQuery(type: 'checked');
                $btpProductFound = false;
                $removedProductName = '';

                foreach ($checkedCartItems as $cartItem) {
                    if (isset($cartItem->product) && $cartItem->product->is_btp == 1) {
                        $removedProductName = $cartItem->product->name;
                        Cart::where('id', $cartItem->id)->delete();
                        $btpProductFound = true;
                        break; // Stop after removing the first BTP product
                    }
                }

                if ($btpProductFound) {
                    return response()->json([
                        'errors' => 'You are already an active BTP user. The product "' . $removedProductName . '" has been removed from your items. Please review your cart.'
                    ], 403);
                }
            }
        }
        // <<< END: New Active BTP User Check >>>

        $cart = CartManager::getCartListQuery(type: 'checked');
        $hasPhysicalProduct = false;
        $hasDigitalProduct = false;
        foreach ($cart as $cartItem) {
            if (isset($cartItem->product) && $cartItem->product->product_type == 'physical') {
                $hasPhysicalProduct = true;
            }
            if (isset($cartItem->product) && $cartItem->product->product_type == 'digital') {
                $hasDigitalProduct = true;
            }
        }

        $shipping = [];
        $billing = [];

        parse_str($request['shipping'], $shipping);

        if (!$hasPhysicalProduct && $hasDigitalProduct) {
            $billing['billing_contact_person_name'] = $shipping['contact_person_name'] ?? null;
            $billing['billing_phone'] = $shipping['phone'] ?? null;
            $billing['billing_contact_email'] = $shipping['email'] ?? null;
            $billing['billing_country'] = $shipping['country'] ?? null;
            $billing['billing_city'] = $shipping['city'] ?? null;
            $billing['billing_address_type'] = $shipping['address_type'] ?? null;
            $billing['billing_address'] = $shipping['address'] ?? null;
            $billing['billing_latitude'] = $shipping['latitude'] ?? null;
            $billing['billing_longitude'] = $shipping['longitude'] ?? null;
            $billing['billing_method_id'] = $shipping['shipping_method_id'] ?? 0;
            if (isset($shipping['save_address'])) {
                $billing['save_address_billing'] = $shipping['save_address'];
            }
            if (isset($shipping['update_address'])) {
                $billing['update_billing_address'] = $shipping['update_address'];
            }
            $shipping = [];
            $request->merge(['billing_addresss_same_shipping' => 'false']);
        } else {
            $request->merge(['billing_addresss_same_shipping' => 'true']);
            parse_str($request['billing'], $billing);
        }

        if (isset($shipping['phone'])) {
            $shippingPhoneValue = preg_replace('/[^0-9]/', '', $shipping['phone']);
            $shippingPhoneLength = strlen($shippingPhoneValue);
            if ($shippingPhoneLength < 4 || $shippingPhoneLength > 20) {
                return response()->json(['errors' => translate('The_phone_number_must_be_at_least_4_or_may_not_be_greater_than_20_characters')], 403);
            }
        }

        if ($request['billing_addresss_same_shipping'] == 'false' && isset($billing['billing_phone'])) {
            $billingPhoneValue = preg_replace('/[^0-9]/', '', $billing['billing_phone']);
            $billingPhoneLength = strlen($billingPhoneValue);
            if ($billingPhoneLength < 4 || $billingPhoneLength > 20) {
                return response()->json(['errors' => translate('The_phone_number_must_be_at_least_4_or_may_not_be_greater_than_20_characters')], 403);
            }
        }

        $countryRestrictStatus = getWebConfig(name: 'delivery_country_restriction');
        $isGuestCustomer = !auth('customer')->check();
        $addressId = 0;
        $billingAddressId = 0;

        $addressDataPayload = [
            'customer_id' => auth('customer')->id() ?? (session('guest_id') ?? 0),
            'is_guest' => $isGuestCustomer ? 1 : 0,
            'zip' => null,
        ];

        if ($hasPhysicalProduct) {
            if (empty($shipping['contact_person_name']) || empty($shipping['address_type']) || empty($shipping['address']) || empty($shipping['city']) || empty($shipping['country']) || empty($shipping['phone']) || ($isGuestCustomer && empty($shipping['email']))) {
                return response()->json(['errors' => translate('Fill_all_required_fields_of_shipping_address')], 403);
            } elseif ($countryRestrictStatus && !self::delivery_country_exist_check($shipping['country'])) {
                return response()->json(['errors' => translate('Delivery_unavailable_in_this_country.')], 403);
            }
            $addressDataPayload += [
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'country' => $shipping['country'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'email' => $isGuestCustomer ? $shipping['email'] : null,
            ];
        }

        if (isset($shipping['save_address']) && $shipping['save_address'] == 'on') {
            if ($hasPhysicalProduct) {
                $addressId = ShippingAddress::insertGetId($addressDataPayload + ['is_billing' => 0]);
            }
            if ($hasDigitalProduct) {
                $billingAddressId = ShippingAddress::insertGetId($addressDataPayload + ['is_billing' => 1]);
            }
        } elseif (isset($shipping['update_address']) && $shipping['update_address'] == 'on') {
            $addressId = $shipping['shipping_method_id'];
            $originalShippingAddress = ShippingAddress::find($addressId);

            if ($originalShippingAddress && $hasPhysicalProduct && $hasDigitalProduct) {
                $correspondingBillingAddress = ShippingAddress::where('customer_id', $originalShippingAddress->customer_id)
                    ->where('is_billing', 1)
                    ->where('contact_person_name', $originalShippingAddress->contact_person_name)
                    ->where('address_type', $originalShippingAddress->address_type)
                    ->where('address', $originalShippingAddress->address)
                    ->where('city', $originalShippingAddress->city)
                    ->where('country', $originalShippingAddress->country)
                    ->where('phone', $originalShippingAddress->phone)
                    ->first();

                ShippingAddress::where('id', $addressId)->update($addressDataPayload);

                if ($correspondingBillingAddress) {
                    $correspondingBillingAddress->update($addressDataPayload);
                    $billingAddressId = $correspondingBillingAddress->id;
                } else {
                    $billingAddressId = ShippingAddress::insertGetId($addressDataPayload + ['is_billing' => 1]);
                }
            } else {
                ShippingAddress::where('id', $addressId)->update($addressDataPayload);
                if ($hasDigitalProduct) {
                    $billingAddressId = $addressId;
                }
            }
        } else {
            $addressId = $shipping['shipping_method_id'] ?? 0;
            if ($addressId == 0 && $hasPhysicalProduct) {
                $addressId = ShippingAddress::insertGetId($addressDataPayload + ['is_billing' => 0]);
            }
        }

        $billingAddressId = $billingAddressId ?: $addressId;

        if ($request['billing_addresss_same_shipping'] == 'false') {
            if (empty($billing['billing_contact_person_name']) || empty($billing['billing_address_type']) || empty($billing['billing_address']) || empty($billing['billing_city']) || empty($billing['billing_country']) || empty($billing['billing_phone']) || ($isGuestCustomer && empty($billing['billing_contact_email']))) {
                return response()->json(['errors' => translate('Fill_all_required_fields_of_billing_address')], 403);
            } elseif ($countryRestrictStatus && !self::delivery_country_exist_check($billing['billing_country'])) {
                return response()->json(['errors' => translate('Delivery_unavailable_in_this_country')], 403);
            }

            $billingAddressPayload = [
                'customer_id' => auth('customer')->id() ?? (session('guest_id') ?? 0),
                'is_guest' => $isGuestCustomer ? 1 : 0,
                'contact_person_name' => $billing['billing_contact_person_name'],
                'address_type' => $billing['billing_address_type'],
                'address' => $billing['billing_address'],
                'city' => $billing['billing_city'],
                'zip' => null,
                'country' => $billing['billing_country'],
                'phone' => $billing['billing_phone'],
                'email' => $isGuestCustomer ? $billing['billing_contact_email'] : null,
                'latitude' => $billing['billing_latitude'] ?? '',
                'longitude' => $billing['billing_longitude'] ?? '',
                'is_billing' => 1,
            ];

            if (isset($billing['save_address_billing']) && $billing['save_address_billing'] == 'on') {
                $billingAddressId = ShippingAddress::insertGetId($billingAddressPayload);
            } elseif (isset($billing['update_billing_address']) && $billing['update_billing_address'] == 'on') {
                $billingAddressId = $billing['billing_method_id'];
                ShippingAddress::where('id', $billingAddressId)->update($billingAddressPayload);
            } else {
                $billingAddressId = $billing['billing_method_id'] ?? 0;
                if ($billingAddressId == 0) {
                    $billingAddressId = ShippingAddress::insertGetId($billingAddressPayload);
                }
            }
        }

        session()->put('address_id', $addressId);
        session()->put('billing_address_id', $billingAddressId);

        if ($request['is_check_create_account'] && $isGuestCustomer) {
            if (empty($request['customer_password']) || empty($request['customer_confirm_password']) || strlen($request['customer_password']) < 8) {
                return response()->json(['errors' => translate('Password_is_required_and_must_be_at_least_8_characters')], 403);
            }
            if ($request['customer_password'] != $request['customer_confirm_password']) {
                return response()->json(['errors' => translate('The_password_and_confirm_password_must_match')], 403);
            }

            $customerData = $hasPhysicalProduct ? $shipping : $billing;
            $customerEmail = $hasPhysicalProduct ? ($customerData['email'] ?? null) : ($customerData['billing_contact_email'] ?? null);
            $customerPhone = $hasPhysicalProduct ? ($customerData['phone'] ?? null) : ($customerData['billing_phone'] ?? null);

            if (User::where('email', $customerEmail)->orWhere('phone', $customerPhone)->exists()) {
                return response()->json(['errors' => translate('An_account_with_this_email_or_phone_already_exists')], 403);
            }

            $newCustomerRegister = [
                'name' => $hasPhysicalProduct ? $customerData['contact_person_name'] : $customerData['billing_contact_person_name'],
                'email' => $customerEmail,
                'phone' => $customerPhone,
                'password' => $request['customer_password'],
            ];
            session()->put('newCustomerRegister', self::getRegisterNewCustomer(request: $request, address: $newCustomerRegister));
        } else {
            session()->forget('newCustomerRegister');
        }

        if (!$hasPhysicalProduct && !$hasDigitalProduct) {
        } elseif (!$hasPhysicalProduct) {
            if (!session('billing_address_id')) {
                 return response()->json(['errors' => translate('Please_update_address_information')], 403);
            }
        } else {
            if (!session('address_id') && !session('billing_address_id')) {
                return response()->json(['errors' => translate('Please_update_address_information')], 403);
            }
        }

        return response()->json([], 200);
    }

    function getRegisterNewCustomer($request, $address): array
    {
        return [
            'name' => $address['name'],
            'f_name' => $address['name'],
            'l_name' => '',
            'email' => $address['email'],
            'phone' => $address['phone'],
            'is_active' => 1,
            'password' => $address['password'],
            'referral_code' => Helpers::generate_referer_code(),
            'shipping_id' => session('address_id'),
            'billing_id' => session('billing_address_id'),
        ];
    }

}