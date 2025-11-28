<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ManualOrder;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProManualOrderRule;

class ManualOrderController extends Controller
{
    // Method to show the manual order form
    public function create()
    {
        // Get the authenticated customer
        $user = auth('customer')->user();

        // Check if the user meets the necessary conditions (is_pro, is_vip, or is_adult)
        if ($user->is_pro == 1 || $user->is_vip == 1 || $user->is_adult == 1) {
            // If the user has one of the required roles, allow them to view the page
           if ($user->is_adult == 1) {
            $products = Product::limit(100)->get(); 
        } else {
            
            $products = Product::where('is_adult', 0)->limit(100)->get();
        }

			
			// Fetch the maximum profit for Pakistan and UAE from ProManualOrderRule
        $omanMaxProfit = ProManualOrderRule::where('country', 'Pakistan')->max('max_profit');
        $uaeMaxProfit = ProManualOrderRule::where('country', 'United Arab Emirates')->max('max_profit');

        // Pass maximum profit values to the view
        return view('theme-views.users-profile.manual-order', compact('products', 'omanMaxProfit', 'uaeMaxProfit'));
        } else {
            // Otherwise, redirect them to the home page or any other page you prefer
            return redirect('/')->with('error', 'You do not have access to this page.');
        }
    }
	
	public function searchProducts(Request $request)
{
    $search = $request->input('search');
    
    // Query the products matching the search term
    $products = Product::where('name', 'LIKE', '%' . $search . '%')
                        ->take(5) // Limit the results to avoid excessive data
                        ->get(['id', 'name']); // Only fetch the product name and ID

    // Return the product names and IDs
    return response()->json(['products' => $products]);
}
	
	/**
     * Apply rules based on the user's pro status and country during manual order creation.
     * This method would typically be used on the front-end to display the calculated profit
     * during order creation for pro users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyRulesToManualOrder(Request $request)
    {
        $user = auth('customer')->user();  // Authenticated user
        $country = $request->country;

        // If the country is not eligible, return an error
        if (!in_array($country, ['United Arab Emirates', 'Pakistan'])) {
            return response()->json(['success' => false, 'message' => 'Country is not eligible for pro rules.']);
        }

        $retailPrice = $request->retail_price;
        $quantity = $request->quantity;

        // Apply the rule based on the country, retail price, and quantity
        $profit = $this->applyRule($country, $retailPrice, $quantity);

        return response()->json(['success' => true, 'profit' => $profit]);
    }

    /**
     * Apply the rules for the specified country, retail price, and quantity
     * and return the applicable profit.
     *
     * @param  string  $country
     * @param  float  $retailPrice
     * @param  int  $quantity
     * @return float
     */
    protected function applyRule($country, $retailPrice, $quantity)
    {
        // Find the matching rule for the specified country, retail price, and quantity
        $rule = ProManualOrderRule::where('country', $country)
            ->where('min_retail_price', '<=', $retailPrice)
            ->where(function ($query) use ($retailPrice) {
                $query->where('max_retail_price', '>=', $retailPrice)
                      ->orWhereNull('max_retail_price');
            })
            ->where('quantity', $quantity)
            ->first();

        // If the rule is found, return the profit amount (apply max profit if applicable)
        if ($rule) {
            return (!is_null($rule->max_profit)) ? min($rule->profit_amount, $rule->max_profit) : $rule->profit_amount;
        }

        // If no rule matches, return a default profit of 0
        return 0;
    }

	public function getProductDetails(Request $request)
{
    $productId = $request->input('id');
    $product = Product::find($productId);

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Truncate product name to the first two words
    $fullName = $product->name;
    $words = explode(' ', $fullName);
    $shortName = implode(' ', array_slice($words, 0, 2));

    // Prepare the product data for the response
    $productDetails = [
        'name' => $shortName,
        'image' => getStorageImages($product->thumbnail_full_url, 'product'),
        'currency_symbol' => getCurrencySymbol(getCurrencyCode()),
        'price' => number_format($product->manual_price, 2),
        'company_fee' => number_format($product->company_fee, 2),
        'delivery_fee' => number_format($product->delivery_fee, 2),
        'vat' => $product->vat > 0 ? number_format($product->vat, 2) : 0,
        'retail_price' => number_format($product->manual_price + $product->company_fee + $product->delivery_fee, 2),
    ];

    return response()->json(['product' => $productDetails]);
}


    // Helper function to fetch geocoding data using city and country
    private function getGeocodingData($city, $country)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Store your API key in the .env file
        $formattedAddress = urlencode($city . ', ' . $country);
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$formattedAddress}&key={$apiKey}";

        // Make the API request
        $response = file_get_contents($geocodeUrl);
        if ($response === false) {
            return null;  // Handle failed request
        }

        $responseData = json_decode($response, true);

        if ($responseData['status'] == 'OK') {
            // Get the first result
            $result = $responseData['results'][0];

            // Extract Latitude, Longitude, Postal Code (ZIP), and State
            $latitude = $result['geometry']['location']['lat'];
            $longitude = $result['geometry']['location']['lng'];

            // Extract the postal code (ZIP) and state from address components
            $postalCode = '';
            $state = '';
            foreach ($result['address_components'] as $component) {
                if (in_array('postal_code', $component['types'])) {
                    $postalCode = $component['long_name'];
                }
                if (in_array('administrative_area_level_1', $component['types'])) {
                    $state = $component['long_name'];
                }
            }

            // If no postal code is found, set it to '00000'
            if (empty($postalCode)) {
                $postalCode = '00000';
            }

            return [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'postal_code' => $postalCode,
                'state' => $state
            ];
        } else {
            // Return default postal code and state if no result found
            return [
                'latitude' => null,
                'longitude' => null,
                'postal_code' => '00000',  // Default postal code if none found
                'state' => null
            ];
        }
    }

    // Method to store manual order data
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'country' => 'required|string',
            'city' => 'required|string',
            'delivery_address' => 'required|string',
            'total_order_amount' => 'required|numeric',
            'added_items' => 'required|json',
			'discount_amount' => 'nullable|numeric',   // Validate discount amount
            'discount_type' => 'nullable|string',      // Validate discount type
        ]);

        // Decode cart items
        $cartItems = json_decode($request->input('added_items'), true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($cartItems)) {
            return response()->json(['success' => false, 'message' => 'Invalid or missing cart items.']);
        }

        DB::beginTransaction();

        try {
            // Step 1: Check if the customer already exists
            $customer = User::where('phone', $request->input('phone'))
                ->orWhere('email', $request->input('email'))
                ->first();

            // Step 2: Create new customer if doesn't exist
            if (!$customer) {
                // Split the full name into first name and last name
                $fullName = $request->input('customer_name');
                $nameParts = explode(' ', trim($fullName));
                $f_name = $nameParts[0] ?? '';
                $l_name = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
                $referredBy = auth()->check() ? auth()->id() : null;

                $customer = User::create([
                    'name' => $fullName,
                    'f_name' => $f_name,
                    'l_name' => $l_name,
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email') ?? '',
                    'country' => $request->input('country'),
                    'city' => $request->input('city'),
                    'street_address' => $request->input('delivery_address'),
                    'referral_code' => Helpers::generate_referer_code(),
                    'referred_by' => $referredBy, // Set the referrer if exists
                    'is_active' => 1,
                ]);
            }

            // Step 3: Get geocoding data from the provided city and country
            $city = $request->input('city');
            $country = $request->input('country');
            $geocodingData = $this->getGeocodingData($city, $country);

            if (!$geocodingData || !$geocodingData['latitude'] || !$geocodingData['longitude']) {
                return response()->json(['success' => false, 'message' => 'Unable to fetch geocoding data for the provided city.']);
            }
			
			// Get the discount values from the request
        $discountAmount = $request->input('discount_amount', 0); // Default to 0 if not provided
        $discountType = $request->input('discount_type', 'final_price_discount'); // Default to 'final_price_discount'

        // Apply the discount to the total order amount
        $totalOrderAmount = $request->input('total_order_amount');

            // Step 4: Insert the shipping address into the database and get its ID
            $shippingAddressId = DB::table('shipping_addresses')->insertGetId([
                "customer_id" => $customer->id,
                "is_guest" => false,
                "contact_person_name" => $request->input('customer_name'),
                "email" => $request->input('email') ?? null,
                "address_type" => "permanent",
                "address" => $request->input('delivery_address'),
                "city" => $request->input('city'),
                "zip" => $geocodingData['postal_code'], // Use the postal code from geocoding data
                "phone" => $request->input('phone'),
                "created_at" => now(),
                "updated_at" => now(),
                "state" => $geocodingData['state'], // Use the state from geocoding data
                "country" => $request->input('country'),
                "latitude" => $geocodingData['latitude'],
                "longitude" => $geocodingData['longitude'],
                "is_billing" => false,
            ]);

            // Step 5: Create a new order
            $order = new Order();
            $order->customer_id = $customer->id;
            $order->order_status = 'pending';
            $order->order_amount = $totalOrderAmount; // Use the discounted final amount
            $order->payment_status = 'unpaid';
            $order->payment_method = 'cash_on_delivery';
            $order->shipping_responsibility = 'inhouse_shipping';
            $order->customer_type = 'customer';
            $order->seller_id = '1';
            $order->seller_is = 'admin';
            $order->shipping_address = $shippingAddressId;
            $order->billing_address = $shippingAddressId;
			$order->discount_amount = $discountAmount; // Save the discount amount
            $order->discount_type = $discountType; // Save the discount type

            $shippingData = [
                "id" => $shippingAddressId,
                "customer_id" => $customer->id,
                "is_guest" => false,
                "contact_person_name" => $request->input('customer_name'),
                "email" => $request->input('email') ?? null,
                "address_type" => "permanent",
                "address" => $request->input('delivery_address'),
                "city" => $request->input('city'),
                "zip" => $geocodingData['postal_code'], // Use the postal code from geocoding data
                "phone" => $request->input('phone'),
                "created_at" => now(),
                "updated_at" => now(),
                "state" => $geocodingData['state'], // Use the state from geocoding data
                "country" => $request->input('country'),
                "latitude" => $geocodingData['latitude'],
                "longitude" => $geocodingData['longitude'],
                "is_billing" => false,
            ];

            $billingData = $shippingData;  // If billing and shipping are the same
            $order->shipping_address_data = $shippingData;
            $order->billing_address_data = $billingData;

            // Save the order
            $order->save();

            // Step 6: Validate and insert each cart item into order details
            foreach ($cartItems as $cartItem) {
                $product = Product::where('name', 'like', '%' . $cartItem['name'] . '%')->first();

                if (!$product) {
                    throw new \Exception('Product ' . $cartItem['name'] . ' not found');
                }

                if ($product->current_stock < $cartItem['quantity']) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Not enough stock for product: ' . $product->name
                    ]);
                }

              

                // Create a new order detail entry
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product->id;
                $orderDetail->seller_id = '1'; // Assuming seller is admin
                $orderDetail->product_details = json_encode($product); // Save product details as JSON
                $orderDetail->variant = ''; // Update if there's a variant
                $orderDetail->qty = $cartItem['quantity'];
                $orderDetail->price = $cartItem['yourPrice'];
                $orderDetail->save();

                // Decrease the product stock
                $product->current_stock -= $cartItem['quantity'];
                $product->save();
            }

            // Step 7: Create a manual order
            $manualOrder = new ManualOrder();
            $manualOrder->user_id = $customer->id;
			$manualOrder->order_id = $order->id;
			$manualOrder->created_by_id = $customer->referred_by;
            $manualOrder->full_name = $request->input('customer_name');
            $manualOrder->phone = $request->input('phone');
            $manualOrder->email = $request->input('email');
            $manualOrder->country = $request->input('country');
			$manualOrder->city = $request->input('city');
			$manualOrder->area_name = $request->input('area_name');
            $manualOrder->delivery_address = $request->input('delivery_address');
			$manualOrder->currency = $request->input('currency');
            $manualOrder->total_order_amount = $request->input('total_order_amount');
			$manualOrder->total_order_profit = $request->input('total_profit');
			// Determine currency based on country
            $country = $request->input('country');
            $currency = 'PKR'; // Default currency

            if ($country === 'United Arab Emirates') {
            $currency = 'AED';
            } elseif ($country === 'Pakistan') {
            $currency = 'PKR';
            }
            $manualOrder->currency = $currency; // Set the currency
			
            $manualOrder->cart_items = json_encode($cartItems);
            $manualOrder->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Manual Order created successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
