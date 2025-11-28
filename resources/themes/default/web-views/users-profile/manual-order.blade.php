@extends('theme-views.layouts.app')


@section('title', translate('Manual Order Creation').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')

<div class="container section-gap pt-0">
    @include('theme-views.partials._profile-aside')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Manual Order</h1>
    </div>

    <!-- Customer Information Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Customer Details</h6>
        </div>
        <div class="card-body">
            <form id="manual-order-form" method="POST">
                @csrf
                <!-- Customer Information -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="customer_name">Full Name</label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Enter customer full name" required>
                    </div>
     <div class="col-md-6 mb-3">
    <label for="phone">Phone Number</label>
    <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter your phone number" required>
</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email">Customer Email (Optional)</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email (optional)">
                    </div>
                    <div class="col-md-6 mb-3">
    <label for="country">Country</label>
    <input list="country-options" class="form-control" name="country" id="country" placeholder="Select or enter country" required>
    <datalist id="country-options">
        <option value="United Arab Emirates">
        <option value="Pakistan">
    </datalist>
</div>
                </div>

                <!-- New fields for City and Area Name -->
                <div class="row">
                     <div class="col-md-6 mb-3">
    <label for="city">City</label>
    <input list="city-options" class="form-control" name="city" id="city" placeholder="Select or enter city" required>
    <datalist id="city-options">
        <option value="Abu Dhabi">
        <option value="Dubai">
        <option value="Sharjah">
        <option value="Ajman">
        <option value="Umm Al Quwain">
        <option value="Ras Al Khaimah">
        <option value="Fujairah">
    </datalist>
</div>
                    <div class="col-md-6 mb-3">
    <label for="area_name">Area Name</label>
    <input list="area-options" class="form-control" name="area_name" id="area_name" placeholder="Select or enter area name" required>
    <datalist id="area-options">
        <!-- Abu Dhabi Areas -->
        <option value="Abu Dhabi Island">
        <option value="Al Ain Region">
        <option value="Al Dhafra Region">
        <option value="Al Bateen">
        <option value="Al Khalidiya">
        <option value="Al Reem Island">
        <option value="Saadiyat Island">
        <option value="Yas Island">
        <option value="Al Mushrif">
        <option value="Al Nahyan">
        <option value="Al Raha Beach">
        <option value="Al Maryah Island">
        <option value="Khalifa City">
        <option value="Al Shamkha">
        <option value="Bani Yas">
        <option value="Mussafah">
        <option value="Al Rowdah">
        <option value="Mohammed Bin Zayed City">
        
        <!-- Dubai Areas -->
        <option value="New Dubai">
        <option value="Old Dubai">
        <option value="Dubai South">
        <option value="Downtown Dubai">
        <option value="Business Bay">
        <option value="Dubai Marina">
        <option value="Palm Jumeirah">
        <option value="Jumeirah Beach Residence (JBR)">
        <option value="Jumeirah Lakes Towers (JLT)">
        <option value="Al Barsha">
        <option value="Deira">
        <option value="Bur Dubai">
        <option value="Jumeirah">
        <option value="Mirdif">
        <option value="Al Quoz">
        <option value="Dubai Silicon Oasis">
        <option value="Dubai Sports City">
        <option value="Arabian Ranches">
        <option value="International City">
        <option value="Al Satwa">
        <option value="Al Karama">
        <option value="Al Qusais">
        <option value="Dubai Investment Park (DIP)">
        
        <!-- Sharjah Areas -->
        <option value="Sharjah City">
        <option value="Central Region">
        <option value="Eastern Region">
        <option value="Al Majaz">
        <option value="Al Nahda (shared with Dubai)">
        <option value="Al Khan">
        <option value="Al Qasimia">
        <option value="Muwailih Commercial">
        <option value="Al Taawun">
        <option value="Al Layyah">
        <option value="Al Yarmook">
        <option value="Al Heera">
        <option value="Al Fisht">
        <option value="Al Mamzar (shared with Dubai)">
        
        <!-- Ajman Areas -->
        <option value="Ajman City">
        <option value="Masfout">
        <option value="Manama">
        <option value="Al Nuaimiya">
        <option value="Al Rashidiya">
        <option value="Al Jurf">
        <option value="Al Rawda">
        <option value="Al Rumaila">
        <option value="Mushairef">
        <option value="Al Hamidiya">
        
        <!-- Umm Al Quwain Areas -->
        <option value="Umm Al Quwain City">
        <option value="Falaj Al Mualla">
        <option value="Al Salamah">
        <option value="Al Raas">
        <option value="Al Shuwaib">
        <option value="Al Haditha">
        
        <!-- Ras Al Khaimah Areas -->
        <option value="Ras Al Khaimah City">
        <option value="Southern Region">
        <option value="Northern Region">
        <option value="Al Nakheel">
        <option value="Al Hamra Village">
        <option value="Mina Al Arab">
        <option value="Al Rams">
        <option value="Khuzam">
        <option value="Julphar">
        <option value="Al Dhait">
        <option value="Marjan Island">
        
        <!-- Fujairah Areas -->
        <option value="Fujairah City">
        <option value="Dibba Region">
        <option value="Al Faseel">
        <option value="Al Hilal City">
        <option value="Dibba Al-Fujairah">
        <option value="Al Aqah">
        <option value="Mirbah">
        <option value="Sakamkam">
    </datalist>
</div>

       
                </div>

                <div class="mb-3">
                    <label for="delivery_address">Delivery Address</label>
                    <textarea class="form-control" name="delivery_address" id="delivery_address" rows="3" placeholder="Enter full delivery address" required></textarea>
                </div>

                <!-- Hidden fields for total order amount and total profit -->
                <input type="hidden" name="total_order_amount" id="total_order_amount">
                <input type="hidden" name="total_profit" id="total_profit">
                <input type="text" hidden name="added_items" id="added_items">
				<input type="hidden" name="discount_amount" id="discount_amount" value="0">
                <input type="hidden" name="discount_type" id="discount_type" value="final_price_discount">
				<input type="hidden" name="selected_city" id="selected_city">
                <input type="hidden" name="selected_area_name" id="selected_area_name">

            </form>
        </div>
    </div>

    <!-- Product Selection Table -->
    <div id="product-selection-section" class="card shadow mb-4" style="display: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Select Products</h6>
    </div>
    <div class="card-body">
            <div class="form-group mb-3">
    			<input type="text" id="product-search" class="form-control" placeholder="Search for a product by name...">
    			<ul id="suggestion-box" class="list-group" style="position: absolute; z-index: 1000; width: 100%; display: none;"></ul>
			</div>
            <div class="table-responsive">
                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Company Fee</th>
                            <th>Delivery Fee</th>
                            @if($products->filter(fn($product) => $product->vat > 0)->isNotEmpty())
                                <th>VAT</th>
                            @endif
							<th>Retail Price</th>
                            <th style="min-width: 130px;">Quantity</th>
                            <th style="min-width: 150px;">Your Price</th>
                            <th style="min-width: 130px;">Your Profit</th>
                            <th>Add to Cart</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr class="product-row">
                            <td>
                                <img src="{{ getStorageImages($product->thumbnail_full_url, 'product') }}" class="avatar border" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
							<td>
    @php
        $fullName = $product->name;
        $words = explode(' ', $fullName);
        $shortName = implode(' ', array_slice($words, 0, 2));
    @endphp
    <a >
        {{ $shortName }}
    </a>
</td>             
                           <td>
    
    <span class="amount-to-convert" data-original-amount="{{ $product->manual_price }}">
       {{ getCurrencySymbol(getCurrencyCode()) }}  {{ number_format($product->manual_price, 2) }}
    </span>
</td>

<td>
  
    <span class="amount-to-convert" data-original-amount="{{ $product->company_fee }}">
       {{ getCurrencySymbol(getCurrencyCode()) }} {{ number_format($product->company_fee, 2) }}
    </span>
</td>

<td>
    
    <span class="amount-to-convert" data-original-amount="{{ $product->delivery_fee }}">
       {{ getCurrencySymbol(getCurrencyCode()) }} {{ number_format($product->delivery_fee, 2) }}
    </span>
</td>
                            @if($product->vat > 0)
                                <td>{{ getCurrencySymbol(getCurrencyCode()) . number_format($product->vat, 2) }}</td>
                            @endif
							
							<!-- Retail Price Calculation -->
          <td>
    @php
        // Final retail price calculation
        $retailPrice = $product->manual_price + $product->company_fee + $product->delivery_fee;
    @endphp
    
    <span class="amount-to-convert" data-original-amount="{{ $retailPrice }}">
       {{ getCurrencySymbol(getCurrencyCode()) }} {{ number_format($retailPrice, 2) }}
    </span>
</td>

                            <td>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary quantity-minus">-</button>
                                    <input type="number" name="quantity[]" class="form-control text-center quantity-input" min="1" value="1" required>
                                    <button type="button" class="btn btn-outline-secondary quantity-plus">+</button>
                                </div>
                            </td>
                            <td>
                                <input type="number" name="your_price[]" class="form-control your-price" placeholder="Your Price" required>
                                <small class="text-danger price-error d-none"></small>
                            </td>
                            <td>
                                <input type="text" class="form-control your-profit" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm add-to-cart" disabled>Add</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cart and Order Summary -->
  <div id="order-summary-section" class="card shadow mb-4" style="display: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
    </div>
    <div class="card-body">
            <div id="cart-summary">
                <h5>Cart Items</h5>
				<div class="table-responsive">
                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="min-width: 130px;">Quantity</th>
                            <th>Your Price</th>
                            <th>Your Profit</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                    </tbody>
                </table>
				</div>


                <!-- Final Amount Field -->
                <div class="mt-3">
                    <label for="final-amount">Final Price:</label>
                    <input type="number" id="final-amount" class="form-control mb-2" placeholder="Enter Final Price">
                    <button id="update-final-amount" class="btn btn-primary">Update</button>
                </div>

                <!-- Discount Information -->
                <div class="mt-3">
                    <h5>Discount: <span  class="amount-to-convert" data-original-amount="0.00" id="discount-amount">{{ getCurrencySymbol(getCurrencyCode()) }} 0.00</span> (<span id="discount-percentage">0%</span>)</h5>
                </div>
<div class="summary">
    <h5>Total Product Price: 
        <span class="amount-to-convert" data-original-amount="0.00" id="total-product-price">{{ getCurrencySymbol(getCurrencyCode()) }} 0.00</span>
    </h5>
    <h5>Total Company Fee: 
        <span class="amount-to-convert" data-original-amount="0.00" id="total-company-fee">{{ getCurrencySymbol(getCurrencyCode()) }} 0.00</span>
    </h5>
    <h5>Total Delivery Fee: 
        <span class="amount-to-convert" data-original-amount="0.00" id="total-delivery-fee">{{ getCurrencySymbol(getCurrencyCode()) }} 0.00</span>
    </h5>
</div>

  
				
				
                <!-- Total Amount and Total Profit in Separate Styled Boxes -->
               <div class="d-flex justify-content-end mt-3">
    <div class="p-3 bg-light border rounded mr-3">
        <h5 id="order-amount-label">Total Order Amount: 
            <span class="amount-to-convert" data-original-amount="0.00" id="total-amount">{{ getCurrencySymbol(getCurrencyCode()) }} 0.00</span>
        </h5>
    </div>
    <div class="p-3 bg-light border rounded">
        <h5>Total Profit: 
            <span class="amount-to-convert" data-original-amount="0.00" id="total-profit">{{ getCurrencySymbol(getCurrencyCode()) }} 0.00</span>
        </h5>
    </div>
</div>
            </div>

            <!-- Submit Button for Order Creation -->
            <div class="text-right mt-3">
                <button id="create-order-btn" class="btn btn-primary" style="display: none;">Create Order</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>

<script>
    let autocomplete;
    let currentExchangeRate = 1; // Default exchange rate is 1 (USD)
    let currencySymbol = '{{ getCurrencySymbol(getCurrencyCode()) }}'; // Default currency symbol

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('delivery_address'), { types: ['geocode'] }
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        let city = '';
        let area_name = '';
        let country = '';
        let lat = place.geometry.location.lat();
        let lng = place.geometry.location.lng();

        for (const component of place.address_components) {
            const componentType = component.types[0];

            if (componentType === 'locality') {
                city = component.long_name;
            }

            if (componentType === 'neighborhood' || componentType === 'sublocality') {
                area_name = component.long_name;
            }

            if (componentType === 'country') {
                country = component.long_name;
            }
        }

        if (!area_name) {
            fetchNearestArea(lat, lng);
        } else {
            document.getElementById('area_name').value = area_name;
        }

        document.getElementById('city').value = city;
        document.getElementById('country').value = country;

        // Update currency based on country
        updateCurrencyByCountry(country);
		checkCountry();
    }

    function fetchNearestArea(lat, lng) {
        const geocoder = new google.maps.Geocoder();
        const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };

        geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === "OK") {
                if (results[0]) {
                    let foundArea = '';

                    for (const component of results[0].address_components) {
                        if (component.types.includes("sublocality") || component.types.includes("neighborhood")) {
                            foundArea = component.long_name;
                            break;
                        }
                    }

                    if (foundArea) {
                        document.getElementById('area_name').value = foundArea;
                    } else {
                        document.getElementById('area_name').value = results[0].address_components.find(component =>
                            component.types.includes("locality") || component.types.includes("administrative_area_level_2")
                        ).long_name || '';
                    }
                }
            } else {
                console.error('Geocoder failed due to: ' + status);
            }
        });
    }

    // Function to update currency based on country name
    function updateCurrencyByCountry(country) {
        country = country.trim().toLowerCase();

        let exchangeRate = 1; // Default is USD

        if (country === 'united arab emirates') {
            currencySymbol = 'AED';
            exchangeRate = 3.68; // USD to AED exchange rate
        } else if (country === 'oman') {
            currencySymbol = 'OMR';
            exchangeRate = 0.38; // USD to OMR exchange rate
        } else {
            currencySymbol = '{{ getCurrencySymbol(getCurrencyCode()) }}'; // Default currency
        }

        // Update the current exchange rate globally
        currentExchangeRate = exchangeRate;

        // Convert currency for all displayed amounts
        convertCurrency(exchangeRate, currencySymbol);
    }

    function convertCurrency(rate, currencySymbol) {
        // Convert all displayed amounts to the specified currency and update currency symbol
        //document.querySelectorAll('.currency-symbol').forEach((symbol) => {
        //    symbol.innerHTML = currencySymbol; // Update currency symbol
        //});

        document.querySelectorAll('.amount-to-convert').forEach((amountElement) => {
            const originalAmount = parseFloat(amountElement.getAttribute('data-original-amount'));
            const convertedAmount = (originalAmount * rate).toFixed(2);
            amountElement.innerHTML = currencySymbol + ' ' + convertedAmount; // Update the value to new currency
        });
    }

	function checkCountry() {
        const countryInput = document.getElementById('country').value.trim().toLowerCase();
        const productSelectionSection = document.getElementById('product-selection-section');

        if (countryInput === 'united arab emirates' || countryInput === 'oman') {
            productSelectionSection.style.display = 'block'; 
        } else {
            productSelectionSection.style.display = 'none'; 
        }
    }
	
    // Event listener for manual country input
    document.getElementById('country').addEventListener('input', function () {
        const country = this.value;
        updateCurrencyByCountry(country); // Update currency based on manual input
		checkCountry();
    });

    google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let cart = [];
	const maxProfitOman = @json($omanMaxProfit);
    const maxProfitUAE = @json($uaeMaxProfit);
    let currentTotalAmount = 0;

	@if(auth('customer')->user() && auth('customer')->user()->is_vip == 1)
	
    document.querySelectorAll('.quantity-minus, .quantity-plus').forEach(button => {
        button.addEventListener('click', function () {
            const row = button.closest('tr');
            const quantityInput = row.querySelector('.quantity-input');
            const yourPriceInput = row.querySelector('input[name="your_price[]"]');
            const yourProfitInput = row.querySelector('.your-profit');
            const manualPrice = parseFloat(row.querySelector('td:nth-child(3)').innerText.replace(/[^\d.]/g, '')); // Manual Price (Product Price)
            const companyFee = parseFloat(row.querySelector('td:nth-child(4)').innerText.replace(/[^\d.]/g, ''));  // Company Fee
            const deliveryFee = parseFloat(row.querySelector('td:nth-child(5)').innerText.replace(/[^\d.]/g, ''));  // Delivery Fee
            const totalBasePrice = manualPrice + companyFee + deliveryFee; // Base Price Calculation

            let quantity = parseInt(quantityInput.value);
            let yourPrice = parseFloat(yourPriceInput.value);

            if (button.classList.contains('quantity-minus')) {
                if (quantity > 1) {
                    quantity--; // Decrease quantity
                }
            } else if (button.classList.contains('quantity-plus')) {
                quantity++; // Increase quantity
            }

            quantityInput.value = quantity; // Update the quantity input field

            // Minimum price validation with exchange rate
            const minRequiredPrice = totalBasePrice * currentExchangeRate;

            if (yourPrice >= minRequiredPrice) {
                // Calculate profit for the new quantity
                const profit = (yourPrice - minRequiredPrice) * quantity;
                yourProfitInput.value = profit.toFixed(2); // Update the profit field
            }
        });
    });



    // Handle Add to Cart logic
    document.querySelectorAll('.add-to-cart').forEach(button => {
        const row = button.closest('tr');
        const manualPrice = parseFloat(row.querySelector('td:nth-child(3)').innerText.replace(/[^\d.]/g, '')); // Manual Price (Product Price)
        const companyFee = parseFloat(row.querySelector('td:nth-child(4)').innerText.replace(/[^\d.]/g, ''));  // Company Fee
        const deliveryFee = parseFloat(row.querySelector('td:nth-child(5)').innerText.replace(/[^\d.]/g, ''));  // Delivery Fee
        const totalBasePrice = manualPrice + companyFee + deliveryFee;

        const yourPriceInput = row.querySelector('input[name="your_price[]"]');
        const yourProfitInput = row.querySelector('.your-profit');
        const priceError = row.querySelector('.price-error');

        // Add event listener to "Your Price" input field
        yourPriceInput.addEventListener('input', function () {
            const yourPrice = parseFloat(this.value);
            const quantity = parseInt(row.querySelector('.quantity-input').value);

            // Minimum price validation with current exchange rate
            const minRequiredPrice = totalBasePrice * currentExchangeRate;

            if (isNaN(yourPrice) || yourPrice < minRequiredPrice) {
                // Show error if Your Price is less than the base total price
                priceError.textContent = `Minimum amount should be ${minRequiredPrice.toFixed(2)}`;
                priceError.classList.remove('d-none');
                yourProfitInput.value = ''; // Clear profit field
                button.disabled = true; // Disable Add to Cart button
            } else {
                // Hide error if valid
                priceError.classList.add('d-none');

                // Calculate profit for the new price and quantity
                const profit = (yourPrice - minRequiredPrice) * quantity;
                yourProfitInput.value = profit.toFixed(2);

                // Enable Add to Cart button
                button.disabled = false;
            }
        });

        // Add to Cart button click logic
        button.addEventListener('click', function () {
            const productName = row.querySelector('td:nth-child(2)').innerText;
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const yourPrice = parseFloat(yourPriceInput.value);
            const profit = parseFloat(yourProfitInput.value);
            const total = quantity * yourPrice;

            const existingProduct = cart.find(item => item.name === productName);

            if (existingProduct) {
                // If it exists, just update the quantity, profit, and total
                existingProduct.quantity += quantity;
                existingProduct.profit = (yourPrice - totalBasePrice) * existingProduct.quantity; // Update profit based on total quantity
                existingProduct.total = existingProduct.quantity * yourPrice;
            } else {
                // Add new product to the cart
                cart.push({
                    name: productName,
                    quantity,
                    yourPrice,
                    profit: profit, // Set profit based on initial quantity
                    manualPrice,
                    companyFee,
                    deliveryFee,
                    total
                });
            }

            updateCart(); // Update the cart UI
            document.getElementById('create-order-btn').style.display = 'block'; // Show "Create Order" button
        });
    });
	@endif
	
	
	// IS PRO LOGIC
	@if(auth('customer')->user() && auth('customer')->user()->is_pro == 1)

document.querySelectorAll('.quantity-minus, .quantity-plus').forEach(button => {
    button.addEventListener('click', function () {
        const row = button.closest('tr');
        const quantityInput = row.querySelector('.quantity-input');
        const yourPriceInput = row.querySelector('input[name="your_price[]"]');
        const yourProfitInput = row.querySelector('.your-profit');
        const manualPrice = parseFloat(row.querySelector('td:nth-child(3)').innerText.replace(/[^\d.]/g, '')); // Manual Price (Product Price)
		const companyFee = parseFloat(row.querySelector('td:nth-child(4)').innerText.replace(/[^\d.]/g, ''));  // Company Fee
		const deliveryFee = parseFloat(row.querySelector('td:nth-child(5)').innerText.replace(/[^\d.]/g, ''));  // Delivery Fee
		const totalBasePrice = manualPrice + companyFee + deliveryFee; // Base Price Calculation

        let quantity = parseInt(quantityInput.value);
        let yourPrice = parseFloat(yourPriceInput.value);

        if (button.classList.contains('quantity-minus')) {
            if (quantity > 1) {
                quantity--; // Decrease quantity
            }
        } else if (button.classList.contains('quantity-plus')) {
            quantity++; // Increase quantity
        }

        quantityInput.value = quantity; // Update the quantity input field
        // Minimum price validation with exchange rate
        const minRequiredPrice = totalBasePrice;

        if (yourPrice >= minRequiredPrice) {
            const countryInput = document.getElementById('country').value.trim(); // Get country input value

            if (!countryInput) {
				toastr.warning('Please enter the country before proceeding.', 'Warning', {
                    CloseButton: true,
                    ProgressBar: true
                });
                return;
            }

            if (countryInput === 'Oman' || countryInput === 'United Arab Emirates') {
                // Send AJAX request to get the rules-based profit amount with the country value
                fetch('{{ route('manual.order.apply-rules') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        retail_price: yourPrice,
                        quantity: quantity,
                        country: countryInput, // Send country in the request
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update profit field with the value returned from the server
                        yourProfitInput.value = data.profit;
                    } else {
                        console.error('Error fetching profit:', data.message);
                        yourProfitInput.value = '0.00'; // Set profit to 0 if there's an error
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    yourProfitInput.value = '0.00'; // Set profit to 0 if an error occurs
                });
            } else {
	toastr.warning('The selected country must be Oman or United Arab Emirates.', 'Warning', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    });
});

document.querySelectorAll('.add-to-cart').forEach(button => {
    const row = button.closest('tr');
    const manualPrice = parseFloat(row.querySelector('td:nth-child(3)').innerText.replace(/[^\d.]/g, '')); // Manual Price (Product Price)
    const companyFee = parseFloat(row.querySelector('td:nth-child(4)').innerText.replace(/[^\d.]/g, ''));  // Company Fee
    const deliveryFee = parseFloat(row.querySelector('td:nth-child(5)').innerText.replace(/[^\d.]/g, ''));  // Delivery Fee
    const totalBasePrice = manualPrice + companyFee + deliveryFee;

    const yourPriceInput = row.querySelector('input[name="your_price[]"]');
    const yourProfitInput = row.querySelector('.your-profit');
    const priceError = row.querySelector('.price-error');

    yourPriceInput.addEventListener('input', function () {
        const yourPrice = parseFloat(this.value);
        const quantity = parseInt(row.querySelector('.quantity-input').value);

        const minRequiredPrice = totalBasePrice * currentExchangeRate;

        if (isNaN(yourPrice) || yourPrice < minRequiredPrice) {
            priceError.textContent = `Minimum amount should be ${minRequiredPrice.toFixed(2)}`;
            priceError.classList.remove('d-none');
            yourProfitInput.value = ''; // Clear profit field
            button.disabled = true; // Disable Add to Cart button
        } else {
            priceError.classList.add('d-none');

            const countryInput = document.getElementById('country').value.trim(); // Get country input value

            if (!countryInput) {
				toastr.warning('Please enter the country before proceeding.!', 'Warning', {
                    CloseButton: true,
                    ProgressBar: true
                });
                button.disabled = true;
                return;
            }

            if (countryInput === 'Oman' || countryInput === 'United Arab Emirates') {
                fetch('{{ route('manual.order.apply-rules') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        retail_price: yourPrice,
                        quantity: quantity,
                        country: countryInput, // Send country in the request
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        yourProfitInput.value = data.profit;
                        button.disabled = false; // Enable Add to Cart button if valid
                    } else {
                        console.error('Error fetching profit:', data.message);
                        yourProfitInput.value = '0.00';
                        button.disabled = true; // Disable Add to Cart button if error
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    yourProfitInput.value = '0.00';
                    button.disabled = true; // Disable Add to Cart button if error
                });
            } else {
	toastr.warning('The selected country must be Oman or United Arab Emirates.', 'Warning', {
                    CloseButton: true,
                    ProgressBar: true
                });
                button.disabled = true;
            }
        }
    });
	button.addEventListener('click', function () {
            const productName = row.querySelector('td:nth-child(2)').innerText;
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const yourPrice = parseFloat(yourPriceInput.value);
            const profit = parseFloat(yourProfitInput.value);
            const total = quantity * yourPrice;

            const existingProduct = cart.find(item => item.name === productName);

            if (existingProduct) {
                // If it exists, just update the quantity, profit, and total
                existingProduct.quantity += quantity;
                existingProduct.profit = (yourPrice - totalBasePrice) * existingProduct.quantity; // Update profit based on total quantity
                existingProduct.total = existingProduct.quantity * yourPrice;
            } else {
                // Add new product to the cart
                cart.push({
                    name: productName,
                    quantity,
                    yourPrice,
                    profit: profit, // Set profit based on initial quantity
                    manualPrice,
                    companyFee,
                    deliveryFee,
                    total
                });
            }

            updateCart(); // Update the cart UI
            document.getElementById('create-order-btn').style.display = 'block'; // Show "Create Order" button
        });
});

@endif


	// END IS PRO LOGIC

    // Update cart display and show summary (Total Product Price, Company Fee, and Delivery Fee)
    function updateCart() {
        const cartItems = document.getElementById('cart-items');
        cartItems.innerHTML = ''; // Clear current cart items

        let totalProfit = 0;
        let totalProductPrice = 0;
        let totalCompanyFee = 0;
        let totalDeliveryFee = 0;

        cart.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary quantity-minus" data-index="${index}">-</button>
                            <input type="number" class="form-control text-center" value="${item.quantity}" readonly>
                            <button type="button" class="btn btn-outline-secondary quantity-plus" data-index="${index}">+</button>
                        </div>
                    </td>
                    <td>${currencySymbol} ${(item.yourPrice).toFixed(2)}</td>
                    <td>${currencySymbol} ${(typeof item.profit === 'number' ? item.profit.toFixed(2) : item.profit)}</td>
                    <td>${currencySymbol} ${(item.total).toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm" data-index="${index}">Remove</button></td>
                </tr>
            `;
            cartItems.insertAdjacentHTML('beforeend', row);

            totalProfit += item.profit;
            totalProductPrice += item.manualPrice * item.quantity;
            
			
		if (cart.length > 0) {
            totalCompanyFee = cart[0].companyFee; // Only add delivery fee once
        }
			
        });

        // Only count one delivery fee, regardless of how many products or quantities are in the cart
        if (cart.length > 0) {
            totalDeliveryFee = cart[0].deliveryFee; // Only add delivery fee once
        }

        currentTotalAmount = calculateCurrentTotalAmount(); // Get the dynamic total amount
		
		@if(auth('customer')->user() && auth('customer')->user()->is_pro == 1)
		const country = document.getElementById('country').value.trim();
        let maxProfit = parseFloat(country === 'Oman' ? maxProfitOman : country === 'United Arab Emirates' ? maxProfitUAE : 0);
        if (maxProfit > 0 && totalProfit > maxProfit) totalProfit = maxProfit;
		@endif

        document.getElementById('total-amount').innerText = currencySymbol + ' ' + (currentTotalAmount).toFixed(2);
        document.getElementById('total-profit').innerText = currencySymbol + ' ' + 
    		(typeof totalProfit === 'number' ? totalProfit.toFixed(2) : totalProfit);

        // Update total product price, company fee, and delivery fee summary
        document.getElementById('total-product-price').innerText = currencySymbol + ' ' + (totalProductPrice * currentExchangeRate).toFixed(2);
        document.getElementById('total-company-fee').innerText = currencySymbol + ' ' + (totalCompanyFee * currentExchangeRate).toFixed(2);
        document.getElementById('total-delivery-fee').innerText = currencySymbol + ' ' + (totalDeliveryFee * currentExchangeRate).toFixed(2);

        document.getElementById('added_items').value = JSON.stringify(cart);

        // Add event listeners for increasing/decreasing quantity and removing items from cart
        document.querySelectorAll('.quantity-minus').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                updateQuantity(index, -1); // Decrease quantity
            });
        });

        document.querySelectorAll('.quantity-plus').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                updateQuantity(index, 1); // Increase quantity
            });
        });

        document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                removeFromCart(index);
            });
        });
    }

    // Update quantity in the cart and update profit as well
    function updateQuantity(index, change) {
        const product = cart[index];
        const basePrice = product.yourPrice - (product.profit / product.quantity); // Base price calculation

        product.quantity += change;

        if (product.quantity < 1) {
            product.quantity = 1; // Ensure quantity does not drop below 1
        }

		let profitValue = (product.yourPrice - basePrice) * product.quantity;
		
		@if(auth('customer')->user() && auth('customer')->user()->is_pro == 1)
		const country = document.getElementById('country').value.trim();
        let maxProfit = parseFloat(country === 'Oman' ? maxProfitOman : country === 'United Arab Emirates' ? maxProfitUAE : 0);
        if (maxProfit > 0 && profitValue > maxProfit) profitValue = maxProfit;
		@endif
		
        // Update profit and total based on new quantity
        product.profit = profitValue;
        product.total = product.quantity * product.yourPrice; // Update total based on new quantity

        updateCart(); // Refresh the cart display
    }

    // Remove item from cart
    function removeFromCart(index) {
        cart.splice(index, 1); // Remove the product from the cart by index
        updateCart(); // Update the cart UI after removal

        // Hide "Create Order" button if cart is empty
        if (cart.length === 0) {
            document.getElementById('create-order-btn').style.display = 'none';
        }
    }

    // Function to calculate the current total amount from the cart
    function calculateCurrentTotalAmount() {
        return cart.reduce((total, item) => total + item.total, 0);
    }

    // Handle Final Amount Update and discount calculation
    document.getElementById('update-final-amount').addEventListener('click', function () {
        const finalAmount = parseFloat(document.getElementById('final-amount').value);

        if (!isNaN(finalAmount)) {
            document.getElementById('total-amount').innerText = currencySymbol + ' ' + (finalAmount).toFixed(2);
            calculateDiscount(finalAmount); // Calculate and update discount
        }
    });

    // Function to calculate the discount and profit based on the final amount
    function calculateDiscount(finalAmount) {
        const cartTotal = calculateCurrentTotalAmount();
        const totalProductPrice = cart.reduce((total, item) => total + (item.manualPrice * item.quantity), 0);
        const totalCompanyFee = cart.length > 0 ? cart[0].companyFee : 0;
        const totalDeliveryFee = cart.length > 0 ? cart[0].deliveryFee : 0; // Count delivery fee once

        const discount = cartTotal - finalAmount;
        const discountPercentage = ((discount / cartTotal) * 100).toFixed(2);

        document.getElementById('discount-amount').textContent = currencySymbol + ' ' + (discount).toFixed(2);
        document.getElementById('discount-percentage').textContent = discountPercentage + '%';

        // Calculate total profit
        const totalExpenses = (totalProductPrice * currentExchangeRate) + (totalCompanyFee * currentExchangeRate) + (totalDeliveryFee * currentExchangeRate);
        let totalProfit = (finalAmount - totalExpenses);
		@if(auth('customer')->user() && auth('customer')->user()->is_pro == 1)
		const country = document.getElementById('country').value.trim();
        let maxProfit = parseFloat(country === 'Oman' ? maxProfitOman : country === 'United Arab Emirates' ? maxProfitUAE : 0);
        if (maxProfit > 0 && totalProfit > maxProfit) totalProfit = maxProfit;
		@endif

        document.getElementById('total-profit').innerText = currencySymbol + ' ' + (typeof totalProfit === 'number' ? totalProfit.toFixed(2) : totalProfit);
    }

    // Allow only numbers and the plus symbol for phone input
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function (e) {
        this.value = this.value.replace(/[^\d+]/g, ''); // Enforce only numbers and the plus symbol

        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16); // Limit phone number length to 16 characters
        }
    });

    // Allow only letters and spaces for customer name, country, and city
    function allowOnlyLetters(input) {
        input.value = input.value.replace(/[^A-Za-z\s]/g, ''); // Remove any character that is not a letter or space
    }


    document.getElementById('country').addEventListener('input', function() {
        allowOnlyLetters(this);
    });

    document.getElementById('city').addEventListener('input', function() {
        allowOnlyLetters(this);
    });

    // Update form values before submission
function updateFormValues() {
    const finalAmount = parseFloat(document.getElementById('final-amount').value);
    const totalOrderAmountInput = document.getElementById('total_order_amount');
    const originalTotalAmount = currentTotalAmount; // Use the original total amount before discount
    let discountAmount = 0;

    // Check if final amount is valid and calculate the discount
    if (!isNaN(finalAmount) && finalAmount > 0 && finalAmount < originalTotalAmount) {
        discountAmount = originalTotalAmount - finalAmount; // Calculate the discount
        totalOrderAmountInput.value = finalAmount.toFixed(2); // Set the discounted final amount
    } else {
        totalOrderAmountInput.value = originalTotalAmount.toFixed(2); // Set non-discounted total if final amount is invalid
    }
    
    // Update discount amount and discount type
    document.getElementById('discount_amount').value = discountAmount.toFixed(2);
    document.getElementById('discount_type').value = 'final_price_discount'; // Set discount type

    // Update the total profit field
    document.getElementById('total_profit').value = document.getElementById('total-profit').textContent.replace(/[^\d.]/g, ''); // Set total profit based on UI
    
}



    // Add event listener for form submission to update hidden fields and submit via AJAX
    document.getElementById('create-order-btn').addEventListener('click', function () {
        updateFormValues();

        // Use AJAX to submit the form
        const formData = new FormData(document.getElementById('manual-order-form'));
        fetch('{{ route('manual.order.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success('Order created successfully!', 'Success', {
                    CloseButton: true,
                    ProgressBar: true
                });
                location.reload(); // Reload the page after successful order creation
            } else {
                toastr.error('An error occurred while processing the order.', 'Error', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        })
        .catch(error => {
            toastr.error('An error occurred: ' + error.message, 'Error', {
                CloseButton: true,
                ProgressBar: true
            });
        });
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#productTable tbody .product-row');
    rows.forEach(row => {
        row.style.display = 'none'; 
    });
});


document.getElementById('product-search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim(); 
    const rows = document.querySelectorAll('#productTable tbody .product-row'); 

    rows.forEach(row => {
        const productName = row.querySelector('td:nth-child(2)').innerText.toLowerCase(); 

        
        if (productName.includes(searchTerm) && searchTerm !== '') {
            row.style.display = ''; 
        } else {
            row.style.display = 'none'; 
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderSummarySection = document.getElementById('order-summary-section');
    let cart = [];

    
    function toggleOrderSummary() {
        if (cart.length > 0) {
            orderSummarySection.style.display = 'block'; 
        } else {
            orderSummarySection.style.display = 'none'; 
        }
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const row = button.closest('tr');
            const productName = row.querySelector('td:nth-child(2)').innerText;
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const yourPrice = parseFloat(row.querySelector('.your-price').value);

            cart.push({ name: productName, quantity: quantity, price: yourPrice });

            toggleOrderSummary();
        });
    });

    function removeFromCart(index) {
        cart.splice(index, 1); 
        toggleOrderSummary(); 
    }

    toggleOrderSummary();

    document.getElementById('cart-items').addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-danger')) {
            const index = parseInt(event.target.getAttribute('data-index'));
            removeFromCart(index); 
        }
    });
});

</script>


@endpush
