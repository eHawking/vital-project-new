# Vendor POS - Registered Customers Only & Stock Filtering

## Issues Implemented
The vendor POS system has been updated with three critical business rule changes:

1. **Hide Out-of-Stock Products** - Don't show products with no stock in product section
2. **Filter Search Results** - Don't show out-of-stock products in product search
3. **Registered Customers Only** - Remove walking customer logic, only allow orders from registered/searched customers

---

## Business Rules

### ⛔ **No More Walking Customers**
- **Old System:** Allowed anonymous "walking customer" orders
- **New System:** **Only registered customers** can place orders
- **Reason:** Platform requires customer tracking for bonus distribution system

### 📦 **Stock Availability**
- **Old System:** Showed all products regardless of stock
- **New System:** **Only in-stock products** are visible
- **Reason:** Prevent orders for unavailable products

---

## Changes Implemented

### 1. Product Display Filtering ✅

**File:** `app/Http/Controllers/Vendor/POS/POSController.php`

**Line 97:** Added `'in_stock' => true` filter

```php
$products = $this->productRepo->getListWhere(
    orderBy: ['id' => 'desc'],
    searchValue: $searchValue,
    filters: [
        'added_by' => 'seller',
        'seller_id' => $vendorId,
        'category_id' => $categoryId,
        'code' => $searchValue,
        'status' => 1,
        'in_stock' => true,  // ✅ Only show in-stock products
    ],
    // ...
);
```

**Impact:** Products without stock are hidden from the POS product grid.

---

### 2. Product Search Filtering ✅

**File:** `app/Http/Controllers/Vendor/POS/POSController.php`

**Line 538:** Added `'in_stock' => true` filter

```php
$products = $this->productRepo->getListWhere(
    searchValue: $searchTerm,
    filters: [
        'added_by' => 'seller',
        'seller_id' => auth('seller')->id(),
        'status' => 1,
        'name' => $searchTerm,
        'code' => $searchTerm,
        'in_stock' => true,  // ✅ Only show in-stock products in search
    ],
    // ...
);
```

**Impact:** Search results only return products that are in stock.

---

### 3. Remove Walking Customer on Load ✅

**File:** `app/Http/Controllers/Vendor/POS/POSController.php`

**Lines 138-144:**

**Before:**
```php
$cartId = 'walking-customer-' . rand(10, 1000);
$this->cartService->getNewCartSession(cartId: $cartId);
```

**After:**
```php
// No longer auto-create walking customer - require customer selection
$cartId = session(SessionKey::CURRENT_USER);
if (!$cartId) {
    // Start with empty cart, customer must be selected first
    $cartId = 'temp-cart-' . rand(10, 1000);
    session([SessionKey::CURRENT_USER => $cartId]);
}
```

**Impact:** POS starts with temporary cart, requires customer selection before orders can be placed.

---

### 4. Reject Walking Customer Selection ✅

**File:** `app/Http/Controllers/Vendor/POS/POSController.php`

**Lines 176-182:**

```php
public function changeCustomer(Request $request): JsonResponse
{
    // Only allow registered customers, no walking customer
    if ($request['user_id'] == 0) {
        return response()->json([
            'error' => true,
            'message' => translate('Please select a registered customer to continue')
        ], 400);
    }
    
    $cartId = 'saved-customer-' . $request['user_id'];
    // ...
}
```

**Impact:** Attempting to use walking customer (user_id = 0) returns error.

---

### 5. Block Order Placement Without Customer ✅

**File:** `app/Http/Controllers/Vendor/POS/POSOrderController.php`

**Lines 117-124:**

```php
$userId = $this->cartService->getUserId();

// Only allow orders from registered customers - no walking customer
if ($userId == 0) {
    ToastMagic::error(translate('Please select a registered customer before placing order'));
    return response()->json([
        'error' => true,
        'message' => translate('Please select a registered customer before placing order')
    ], 400);
}
```

**Impact:** Orders cannot be placed without a registered customer selected.

---

### 6. Clean Up Customer Username Logic ✅

**File:** `app/Http/Controllers/Vendor/POS/POSOrderController.php`

**Lines 204-206, 218:**

**Before:**
```php
if ($userId != 0) {
    $user = User::find($userId);
    $customerUsername = $user ? $user->username : 'unknown-customer';
} else {
    $customerUsername = 'walking-customer';  // ❌ No longer possible
}
```

**After:**
```php
// Get customer username - userId is guaranteed to be non-zero at this point
$user = User::find($userId);
$customerUsername = $user ? $user->username : 'unknown-customer';
```

**Impact:** Simplified logic since walking customer is no longer possible.

---

### 7. Update Customer Data Helper ✅

**File:** `app/Http/Controllers/Vendor/POS/POSController.php`

**Lines 394-421:**

```php
protected function getCustomerDataFromSessionForPOS(): array
{
    $cartId = session(SessionKey::CURRENT_USER);
    
    // Check if customer is selected (no more walking customer)
    if (!$cartId || Str::contains($cartId, 'temp-cart')) {
        return [
            'currentCustomer' => null,
            'currentCustomerData' => null
        ];
    }
    
    if (Str::contains($cartId, 'saved-customer')) {
        $userId = explode('-', $cartId)[2];
        $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => $userId]);
        $currentCustomerInfo = $this->cartService->getCustomerInfo(
            currentCustomerData: $currentCustomerData, 
            customerId: $userId
        );
        
        return [
            'currentCustomer' => $currentCustomerInfo['customerName'],
            'currentCustomerData' => $currentCustomerData
        ];
    }
    
    return [
        'currentCustomer' => null,
        'currentCustomerData' => null
    ];
}
```

**Impact:** Returns null for customer when not selected, instead of "Walking Customer".

---

### 8. Update Cart Customer Data ✅

**File:** `app/Http/Controllers/Vendor/POS/POSController.php`

**Lines 427-452:**

```php
protected function getCustomerCartData(string $cartName): array
{
    $customerCartData = [];
    
    // No more walking customer - must be a saved customer
    if (Str::contains($cartName, 'saved-customer')) {
        $customerId = explode('-', $cartName)[2];
        $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => $customerId]);
        $currentCustomerInfo = $this->cartService->getCustomerInfo(
            currentCustomerData: $currentCustomerData, 
            customerId: $customerId
        );
        
        $customerCartData[$cartName] = [
            'customerName' => $currentCustomerInfo['customerName'],
            'customerPhone' => $currentCustomerInfo['customerPhone'],
            'customerId' => $customerId,
        ];
    } else {
        // Temp cart - no customer selected
        $customerCartData[$cartName] = [
            'customerName' => null,
            'customerPhone' => null,
            'customerId' => 0,
        ];
    }
    
    return $customerCartData;
}
```

**Impact:** Cart data returns null for temp carts instead of walking customer data.

---

### 9. Disable Clear Customer Button ✅

**File:** `public/assets/back-end/js/vendor/pos-script.js`

**Lines 1358-1362:**

**Before:**
```javascript
$("#clear-customer").on("click", function() {
    // Reset to walking customer
    $.post({
        url: changeCustomerUrl,
        data: { user_id: 0 },  // ❌ Walking customer
        // ...
    });
});
```

**After:**
```javascript
// Clear customer selection - NO LONGER ALLOWED (registered customers only)
$("#clear-customer").on("click", function() {
    // Show warning that customer cannot be cleared
    toastr.warning("Customer cannot be cleared. Only registered customers can place orders. Please complete this order or start a new order.");
});
```

**Impact:** Clear customer button shows warning instead of reverting to walking customer.

---

### 10. Update Cart Summary View ✅

**File:** `resources/views/vendor-views/pos/partials/_cart-summary.blade.php`

**Lines 4-53:**

**Before:**
```blade
@if ($summaryData['currentCustomer'] != 'Walking Customer')
    {{-- Show customer info --}}
@endif
```

**After:**
```blade
@if ($summaryData['currentCustomer'] !== null && $currentCustomerData !== null)
    {{-- Show customer info --}}
@else
    <div class="alert alert-warning mb-4">
        <div class="d-flex align-items-center">
            <i class="tio-info-outined mr-2"></i>
            <div>
                <strong>{{ translate('Customer Required') }}</strong><br>
                <small>{{ translate('Please search and select a registered customer above to place an order. Only registered customers can place orders.') }}</small>
            </div>
        </div>
    </div>
@endif
```

**Impact:** Shows prominent warning when no customer is selected.

---

## User Experience Flow

### ✅ **Correct Flow (Registered Customer)**

1. **Vendor opens POS**
   - Temp cart created
   - Warning shown: "Customer Required"
   - Cart is empty

2. **Vendor searches for customer**
   - Enter username or phone
   - Search results appear
   - Only in-stock products visible

3. **Vendor selects customer**
   - Customer info displays
   - Warning disappears
   - Cart becomes active for this customer

4. **Vendor adds products**
   - Only in-stock products shown
   - Search only returns in-stock items
   - Products added to cart

5. **Vendor places order**
   - Order created ✅
   - Customer receives bonuses ✅
   - BV/PV calculated ✅

---

### ❌ **Blocked Flow (Walking Customer)**

1. **Vendor tries to place order without customer**
   - ❌ **BLOCKED:** "Please select a registered customer before placing order"

2. **Vendor tries to clear customer**
   - ❌ **BLOCKED:** "Customer cannot be cleared"

3. **Vendor tries to select walking customer (user_id = 0)**
   - ❌ **BLOCKED:** "Please select a registered customer to continue"

---

## Stock Filtering Logic

### Product Repository Filter

The `'in_stock' => true` filter is interpreted by the repository as:

```php
// For physical products
WHERE (product_type = 'physical' AND current_stock > 0)
   OR (product_type = 'digital')

// Digital products are always "in stock"
// Physical products require current_stock > 0
```

**Impact:**
- Physical products with `current_stock = 0` → **Hidden**
- Physical products with `current_stock > 0` → **Shown**
- Digital products → **Always shown** (no stock concept)

---

## Error Messages

### 1. No Customer Selected (Order Attempt)
```
Please select a registered customer before placing order
```

### 2. Clear Customer Attempt
```
Customer cannot be cleared. Only registered customers can place orders. 
Please complete this order or start a new order.
```

### 3. Walking Customer Selection Attempt
```
Please select a registered customer to continue
```

---

## Files Modified

| File | Lines Changed | Purpose |
|------|---------------|---------|
| `POSController.php` | 97, 138-144, 176-182, 394-421, 427-452, 538 | Product filtering, customer selection logic |
| `POSOrderController.php` | 117-124, 204-206, 218 | Order placement validation |
| `pos-script.js` | 1358-1362 | Clear customer button |
| `_cart-summary.blade.php` | 4-53 | Customer requirement warning |

**Total:** 4 files modified

---

## Testing

### Test 1: Out-of-Stock Products Hidden
1. Set a product's `current_stock` to 0
2. Go to vendor POS
3. ✅ **Verify:** Product not visible in grid
4. Search for the product by name
5. ✅ **Verify:** Product not in search results

### Test 2: Cannot Place Order Without Customer
1. Open POS (no customer selected)
2. Add products to cart
3. Try to place order
4. ✅ **Verify:** Error shown "Please select a registered customer"

### Test 3: Cannot Clear Customer
1. Select a registered customer
2. Add products to cart
3. Click clear customer (X button)
4. ✅ **Verify:** Warning shown, customer NOT cleared

### Test 4: Customer Required Warning
1. Open POS
2. ✅ **Verify:** Yellow warning box shows "Customer Required"
3. Select a customer
4. ✅ **Verify:** Warning disappears, customer info shows

### Test 5: Order with Registered Customer
1. Select customer
2. Add in-stock products
3. Place order
4. ✅ **Verify:**
   - Order created with customer username
   - Bonuses calculated correctly
   - No "walking-customer" in database

---

## Database Impact

### Orders Table

**Before:**
```
username: "walking-customer"  ❌
customer_id: NULL
```

**After:**
```
username: "john_doe"  ✅
customer_id: 123
```

All orders now have real customer usernames and IDs.

---

## Benefits

1. ✅ **Complete Bonus Tracking**
   - All orders tied to registered customers
   - No lost bonuses
   - Complete referral chain

2. ✅ **Better Inventory Management**
   - Only show available products
   - Prevent overselling
   - Accurate stock display

3. ✅ **Customer Accountability**
   - Every order has a real customer
   - Better customer analytics
   - Proper purchase history

4. ✅ **Business Intelligence**
   - No anonymous orders
   - Accurate sales attribution
   - Complete customer journey tracking

---

## Migration Notes

### For Existing POS Users:

**⚠️ Important Change:**
- Walking customers are **no longer allowed**
- Must search and select registered customer
- Cannot clear customer once selected

**Workflow Update:**
1. Always search for customer first
2. If new customer, use "Add New Customer" button
3. Complete order or use "New Order" to switch customers

---

## Summary

**Status:** ✅ **IMPLEMENTED AND WORKING**

- **Issue 1:** Out-of-stock products hidden ✅
- **Issue 2:** Search filtered for in-stock only ✅
- **Issue 3:** Walking customers disabled ✅
- **Files Modified:** 4
- **Breaking Changes:** Walking customer no longer supported
- **Bonus System:** Fully protected

---

**Implementation Date:** October 22, 2025  
**Affected:** Vendor POS only  
**Backward Compatible:** No (walking customer removed by design)  
**Database Changes:** None (logic only)
