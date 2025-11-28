<?php

use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\Review;
use App\Utils\ProductManager;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getOverallRating')) {
    function getOverallRating(object|array $reviews): array
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }
}

if (!function_exists('getRating')) {
    function getRating(object|array $reviews): array
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                $rating5 += 1;
            }
            if ($review->rating == 4) {
                $rating4 += 1;
            }
            if ($review->rating == 3) {
                $rating3 += 1;
            }
            if ($review->rating == 2) {
                $rating2 += 1;
            }
            if ($review->rating == 1) {
                $rating1 += 1;
            }
        }
        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }
}

if (!function_exists('getProductDiscount')) {
    /**
     * @param object|array $product
     * @param string|float|int $price
     * @return float
     */
    function getProductDiscount(object|array $product, string|float|int $price): float
    {
        $discount = 0;
        if ($product['discount_type'] == 'percent') {
            $discount = ($price * $product['discount']) / 100;
        } elseif ($product['discount_type'] == 'flat') {
            $discount = $product['discount'];
        }

        return floatval($discount);
    }
}

if (!function_exists('getPriceRangeWithDiscount')) {
    function getPriceRangeWithDiscount(array|object $product, string|null $type = 'web'): float|string
    {
        $is_guest = !auth('customer')->check();

        // --- Determine Base Price & Original Price based on variations ---
        $base_price = $product->unit_price;
        $guest_price = $product->guest_price ?? null;

        if (isset($product->variation) && $product->variation != null && count(json_decode($product->variation)) > 0) {
            $first_variation = json_decode($product->variation)[0];
            $base_price = $first_variation->price;
            $guest_price = $first_variation->guest_price ?? null;
        }

        if (isset($product->digitalVariation) && $product->digitalVariation && count($product->digitalVariation) > 0) {
            $first_variation = $product->digitalVariation[0];
            $base_price = $first_variation->price;
            $guest_price = $first_variation->guest_price ?? null;
        }
        // --- End of Base Price Determination ---


        // --- Main Logic: Calculate final price and determine if there's a discount to show ---
        $final_price = $base_price;
        $original_price_for_del_tag = $base_price;
        $has_discount = false;

        if ($is_guest && isset($guest_price)) {
            // For guests, the guest price is the final price.
            $final_price = $guest_price;
            // Show a slash-through if the guest price is a special deal.
            if ($guest_price < $base_price) {
                $has_discount = true;
                $original_price_for_del_tag = $base_price;
            }
        } else {
            // For logged-in users, apply regular discounts.
            if (isset($product['clearanceSale']) && $product['clearanceSale']) {
                $discount_amount = getProductPriceByType(product: $product, type: 'discounted_amount', result: 'value', price: $base_price);
                $final_price = $base_price - $discount_amount;
                $has_discount = $discount_amount > 0;
            } elseif ($product->discount > 0) {
                $discount_amount = getProductDiscount(product: $product, price: $base_price);
                $final_price = $base_price - $discount_amount;
                $has_discount = $discount_amount > 0;
            }
        }
        // --- End of Main Logic ---


        // --- Render HTML ---
        if ($type == 'panel') {
            $final_price_formatted = setCurrencySymbol(amount: usdToDefaultCurrency(amount: $final_price), currencyCode: getCurrencyCode());
            $original_price_formatted = setCurrencySymbol(amount: usdToDefaultCurrency(amount: $original_price_for_del_tag), currencyCode: getCurrencyCode());
        } else {
            $final_price_formatted = webCurrencyConverter(amount: $final_price);
            $original_price_formatted = webCurrencyConverter(amount: $original_price_for_del_tag);
        }

        $html_string = '<span class="discounted-unit-price fs-24 font-bold">' . $final_price_formatted . '</span>';

        if ($has_discount) {
            $html_string .= '<del class="product-total-unit-price align-middle text-muted fs-18 font-semibold">' . $original_price_formatted . '</del>';
        }

        return $html_string;
        // --- End of Render ---
    }
}


if (!function_exists('getRatingCount')) {
    function getRatingCount($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->whereNull('delivery_man_id')->count();
    }
}

if (!function_exists('units')) {
    function units(): array
    {
        return ['kg', 'pc', 'gms', 'ltrs','pair','oz','lb'];
    }
}

if (!function_exists('getVendorProductsCount')) {
    function getVendorProductsCount(string $type):int
    {
        $products = \Illuminate\Support\Facades\DB::table('products')->where(['added_by'=>'seller'])->get();
        return match ($type) {
            'new-product' => $products->where('request_status', 0)->count(),
            'product-updated-request' => $products->whereNotNull('is_shipping_cost_updated')->where('is_shipping_cost_updated', 0)->count(),
            'approved' => $products->where('request_status', 1)->count(),
            'denied' => $products->where('request_status', 2)->where('status' , 0)->count(),
        };
    }
}
if (!function_exists('getAdminProductsCount')) {
    function getAdminProductsCount(string $type):int
    {
        $products = \Illuminate\Support\Facades\DB::table('products')->where(['added_by'=>'admin'])->get();
        return match ($type) {
            'all' => $products->count(),
            'new-product' => $products->where('request_status', 0)->count(),
            'product-updated-request' => $products->whereNotNull('is_shipping_cost_updated')->where('is_shipping_cost_updated', 0)->count(),
            'approved' => $products->where('request_status', 1)->count(),
            'denied' => $products->where('request_status', 2)->where('status' , 0)->count(),
        };
    }
}


if (!function_exists('getRestockProductFCMTopic')) {
    function getRestockProductFCMTopic(array|object $restockRequest): string
    {
        return 'restock_'.$restockRequest['id'].'_product_restock_'.$restockRequest->product_id.'_topic';
    }
}


if (!function_exists('isProductInWishList')) {
    function isProductInWishList(string|int $productId): bool
    {
        if (session('wish_list') && in_array($productId, session('wish_list'))) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isProductInCompareList')) {
    function isProductInCompareList(string|int $productId): bool
    {
        if (session('compare_list') && in_array($productId, session('compare_list'))) {
            return true;
        }
        return false;
    }
}


if (!function_exists('getProductMaxUnitPriceRange')) {
    function getProductMaxUnitPriceRange($type = null): int
    {
        $maxUnitPrice = Cache::remember(CACHE_FOR_PRODUCTS_MAX_UNIT_PRICE, CACHE_FOR_3_HOURS, function () {
            return Product::all()->max('unit_price');
        });

        if ($type == 'web') {
            $maxUnitPrice = webCurrencyConverterOnlyDigit(amount: $maxUnitPrice);
        }

        $ranges = [
            1000 => 500,
            10000 => 1000,
            100000 => 5000,
            1000000 => 10000,
            PHP_INT_MAX => 50000
        ];

        foreach ($ranges as $max => $increment) {
            if ($maxUnitPrice <= $max) {
                return ceil($maxUnitPrice / $increment) * $increment;
            }
        }
        return 0;
    }
}

if (!function_exists('getProductMinUnitPriceRange')) {
    function getProductMinUnitPriceRange($type = null): int
    {
        $minUnitPrice = Cache::remember(CACHE_FOR_PRODUCTS_MIN_UNIT_PRICE, CACHE_FOR_3_HOURS, function () {
            return Product::all()->min('unit_price');
        });

        if ($type == 'web') {
            $minUnitPrice = webCurrencyConverterOnlyDigit(amount: $minUnitPrice);
        }

        if ($minUnitPrice < 10) {
            return 0;
        }

        $ranges = [
            100 => 10,
            1000 => 50,
            10000 => 100,
            100000 => 1000,
            PHP_INT_MAX => 5000
        ];

        foreach ($ranges as $max => $increment) {
            if ($minUnitPrice <= $max) {
                return floor($minUnitPrice / $increment) * $increment;
            }
        }

        return 0;
    }
}

if (!function_exists('getFeaturedDealsProductList')) {
    function getFeaturedDealsProductList()
    {
        $cacheKey = 'cache_for_Featured_deals_products_list_'.getDefaultLanguage();
        $cacheKeys = Cache::get(CACHE_FOR_FEATURED_DEAL_PRODUCTS_LIST, []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put(CACHE_FOR_FEATURED_DEAL_PRODUCTS_LIST, $cacheKeys, CACHE_FOR_3_HOURS);
        }

        return Cache::remember($cacheKey, CACHE_FOR_3_HOURS, function () {
            $featuredDealID = FlashDeal::where(['deal_type' => 'feature_deal', 'status' => 1])
                ->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->pluck('id')->first();
            $featuredDealProductIDs = $featuredDealID ? FlashDealProduct::where('flash_deal_id', $featuredDealID)->pluck('product_id')->toArray() : [];
            return ProductManager::getPriorityWiseFeatureDealQuery(
                query: Product::active()->with(['category', 'clearanceSale' => function($query) {
                    return $query->active();
                }])->whereIn('id', $featuredDealProductIDs),
                dataLimit: 'all'
            );
        });
    }
}