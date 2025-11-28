# Product Variation-Based Ordering System

## Overview
This implementation adds complete support for variation-based product ordering for vendors (shops and franchises). Vendors can now select specific product variations when placing orders, and the admin can approve orders with proper stock management for each variation.

## Features Implemented

### 1. **Variation Selection in Buy Products Page**
- Vendors can see a dropdown of available variations for each product
- Each variation displays:
  - Variation type (e.g., size, color)
  - Price for that specific variation
  - Available stock quantity
- Vendors must select a variation before adding products with variations to cart
- Stock validation happens before adding to cart

### 2. **Order Summary with Variations**
- Order summary table shows:
  - Product name
  - Selected variation
  - Price (variation-specific or base price)
  - Quantity
  - Total amount
- Each product+variation combination is treated as a unique line item
- Same product with different variations appears as separate rows

### 3. **Order Creation with Variations**
- Order details include:
  - `product_id` - The main product ID
  - `quantity` - Quantity ordered
  - `variation` - Selected variation type
  - `variation_sku` - SKU of the variation (if available)
  - `unit_price` - Variation-specific price
- Validates variation stock before order placement
- Calculates total amount based on variation prices

### 4. **Admin Order Approval with Variation Stock Management**
When admin approves an order:

#### For Products with Variations:
1. **Deduct from Admin Stock:**
   - Finds the specific variation in admin's product
   - Deducts quantity from that variation's stock
   - Updates the admin product's variation JSON

2. **Add to Vendor Stock:**
   - **If vendor already has the product:**
     - Checks if vendor has that specific variation
     - If yes: Increments the variation quantity
     - If no: Adds the new variation to vendor's product
   
   - **If vendor doesn't have the product:**
     - Creates a new product copy for the vendor
     - Sets `admin_id` to link it to the main product
     - Adds only the purchased variation to the new product

#### For Products without Variations:
- Works the same as before
- Deducts from admin's `current_stock`
- Adds to vendor's `current_stock`

### 5. **Stock Updates and Incrementing**
- **Key Feature:** If vendor buys the same product and same variation multiple times:
  - First purchase: Creates vendor product with variation
  - Subsequent purchases: Simply increments the existing variation's quantity
  - No duplicate entries created

## Files Modified

### 1. **ProductStock Model** (`app/Models/ProductStock.php`)
- Added fillable fields: `product_id`, `variant`, `sku`, `price`, `qty`
- Added casts for proper data types
- Added relationship to Product model

### 2. **BuyProductsController** (`app/Http/Controllers/Vendor/BuyProductsController.php`)
- Added validation for `variation` array input
- Extracts variation data when processing orders
- Checks variation stock before order creation
- Stores variation info in order details:
  - `variation` - variation type
  - `variation_sku` - SKU if available

### 3. **ShopAndFranchiseOrdersController** (`app/Http/Controllers/Admin/ShopAndFranchiseOrdersController.php`)
- Complete rewrite of `processOrderApproval()` method
- Handles both variation-based and non-variation products
- Proper variation stock deduction from admin
- Proper variation stock addition to vendor
- Increments existing variations instead of creating duplicates

### 4. **Buy Products View** (`resources/views/vendor-views/buy-products/partials/product-list.blade.php`)
- Added variation dropdown for products with variations
- Shows variation type, price, and stock
- Styled for mobile responsiveness

### 5. **Buy Products Index** (`resources/views/vendor-views/buy-products/index.blade.php`)
- Added "Variation" column to order summary table
- Updated JavaScript to handle variations:
  - `updateHiddenInputs()` - Now tracks variations
  - `attachProductActionEvents()` - Validates variation selection
  - Variation change listener - Updates price dynamically
  - Unique keys for product+variation combinations
  - Stock validation before adding to cart

## Data Flow

### Vendor Places Order:
```
1. Vendor selects product → 2. Chooses variation → 3. Sets quantity → 
4. Adds to cart → 5. Reviews order summary → 6. Places order → 
7. Payment deducted from wallet → 8. Order stored as "pending"
```

### Admin Approves Order:
```
1. Admin views order → 2. Clicks "Approve" → 
3. System validates stock → 4. Deducts from admin variation → 
5. Adds to vendor variation (or creates new) → 
6. Updates order status to "approved" → 7. Sends notification
```

## Database Schema

### Order Details JSON Structure:
```json
{
  "product_id": 123,
  "product_name": "Example Product",
  "quantity": 10,
  "unit_price": 1950.00,
  "original_price": 2000.00,
  "discount": 50,
  "discount_type": "amount",
  "subtotal": 19500.00,
  "variation": "30",
  "variation_sku": "CM&M-DEI&WS-30"
}
```

### Product Variations JSON Structure:
```json
[
  {
    "type": "30",
    "price": 1950,
    "sku": "CM&M-DEI&WS-30",
    "qty": 1000
  },
  {
    "type": "50",
    "price": 2100,
    "sku": "CM&M-DEI&WS-50",
    "qty": 500
  }
]
```

## Testing Checklist

### Vendor Side:
- [ ] Product with variations shows dropdown
- [ ] Product without variations shows no dropdown
- [ ] Cannot add to cart without selecting variation
- [ ] Stock validation works (cannot exceed available stock)
- [ ] Order summary shows correct variation and price
- [ ] Can add same product with different variations
- [ ] Order placement deducts wallet balance
- [ ] Order appears in order history

### Admin Side:
- [ ] Orders appear in Shop Orders / Franchise Orders
- [ ] Order details show selected variations
- [ ] Approve button works
- [ ] Admin variation stock decreases correctly
- [ ] Vendor variation stock increases correctly
- [ ] If vendor has product+variation, quantity increments
- [ ] If vendor doesn't have variation, new one is added
- [ ] If vendor doesn't have product, new product created
- [ ] Cannot approve if insufficient stock
- [ ] Order status changes to "approved"

## Edge Cases Handled

1. **Missing Variation in Order:** Returns error, rolls back transaction
2. **Insufficient Stock:** Returns error before processing
3. **Vendor Already Has Variation:** Increments quantity instead of duplicate
4. **Vendor Has Product but Not Variation:** Adds new variation to existing product
5. **Product Without Variation:** Falls back to regular stock management
6. **Variation Not Found:** Returns error with helpful message

## Benefits

✅ **Accurate Stock Management:** Each variation tracked separately
✅ **No Duplicates:** Smart increment logic prevents duplicate entries
✅ **User-Friendly:** Clear variation selection UI
✅ **Flexible:** Works with both variation and non-variation products
✅ **Scalable:** Can handle unlimited variations per product
✅ **Safe:** Transaction rollback on any error

## Future Enhancements

- Add variation images
- Bulk variation stock updates
- Variation-specific discounts
- Stock alerts for low variation quantities
- Variation history tracking
- Export/import variations via CSV

---

**Implementation Date:** October 13, 2025
**Status:** ✅ Complete and Ready for Testing
