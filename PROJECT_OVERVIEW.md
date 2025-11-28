# Project Overview - Vital E-Commerce Platform

## 📋 Project Information

**Project Name:** 6valley Multi-Vendor E-Commerce Platform  
**Framework:** Laravel 10.x (PHP 8.1+)  
**Database:** MySQL (6valley)  
**Timezone:** Asia/Dhaka  
**Location:** c:\Users\dds\Desktop\vital-project-new

---

## 🎯 Project Type

This is a **comprehensive multi-vendor e-commerce platform** with advanced features including:
- Multi-vendor marketplace capabilities
- AI-powered product generation
- SSO (Single Sign-On) authentication
- Wallet/Account system integration
- Product variation management
- Shop and franchise order systems
- MLM/Bonus distribution system

---

## 🏗️ Architecture Overview

### **Technology Stack**

#### Backend
- **Framework:** Laravel 10.10
- **PHP Version:** 8.1+
- **Authentication:** Laravel Passport (OAuth2), Laravel Sanctum
- **Database ORM:** Eloquent
- **Queue:** Sync (can be configured for Redis/Database)
- **Cache:** File (can be configured for Redis)
- **Storage:** Local/AWS S3 support

#### Frontend
- **Theme System:** Multi-theme support (theme_fashion active)
- **JavaScript:** jQuery, Vue.js 2.x
- **CSS Framework:** Bootstrap 4
- **Build Tool:** Laravel Mix (Webpack)
- **Rich Text Editor:** Quill
- **Image Upload:** Spartan Multi Image Picker

#### Key PHP Extensions Required
- GD or Imagick (for image processing)
- cURL, JSON, DOM, FileInfo
- OpenSSL, Zip, MySQLi, Intl

---

## 📁 Project Structure

```
vital-project-new/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Contracts/        # Interfaces (90 files)
│   ├── Enums/           # Enumerations (93 files)
│   ├── Events/          # Event classes (20 files)
│   ├── Exports/         # Excel export classes (30 files)
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/   # Admin controllers (104 items)
│   │       ├── Api/     # API controllers
│   │       ├── Customer/# Customer controllers (8 items)
│   │       ├── Vendor/  # Vendor controllers (34 items)
│   │       └── Web/     # Web controllers (18 items)
│   ├── Models/          # Eloquent models (121 files)
│   ├── Repositories/    # Repository pattern (87 files)
│   ├── Services/        # Business logic services (74 files)
│   │   └── AI/         # AI services (4 files)
│   ├── Traits/          # Reusable traits (28 files)
│   └── Utils/           # Helper utilities (22 files)
├── Modules/
│   └── Blog/            # Blog module (61 items)
├── config/              # Configuration files (29 files)
├── database/
│   ├── migrations/      # Database migrations (230 files)
│   └── seeds/           # Database seeders (5 items)
├── public/              # Public assets
│   ├── assets/         # CSS, JS, images (2783 items)
│   └── uploads/        # User-uploaded files
├── resources/
│   ├── lang/           # Language files (30 languages)
│   ├── themes/         # Theme files (742 items)
│   └── views/          # Blade templates (652 files)
├── routes/
│   ├── admin/          # Admin routes
│   ├── web/            # Web routes
│   └── rest_api/       # REST API routes (3 items)
├── storage/            # Storage directory
│   └── logs/          # Application logs
└── vendor/             # Composer dependencies
```

---

## 🚀 Key Features Implemented

### 1. **AI Product Generation System** ✅
- **Image Analysis:** Uses Google Gemini Vision API
- **Product Data Extraction:** Automatically extracts name, category, brand, specs
- **Description Generation:** Creates 800+ word SEO-optimized descriptions
- **Image Generation:** Creates 8 professional product images (400x500px)
- **Auto-Fill:** Populates all product form fields automatically
- **Models Supported:** 
  - gemini-2.5-flash
  - gemini-2.5-flash-image-preview
  - gemini-2.5-flash-image
  - gemini-2.5-pro

**Key Files:**
- `app/Services/AI/GeminiVisionService.php` - Vision analysis
- `app/Services/AI/GeminiImageService.php` - Image generation
- `app/Services/AI/AIProductMapper.php` - Data mapping
- `app/Http/Controllers/Admin/Product/ProductAIController.php` - API endpoints

### 2. **SSO (Single Sign-On) System** ✅
- **Seamless Authentication:** Between main store and account/wallet system
- **Secure Token System:** HMAC-signed, one-time use, 5-minute expiry
- **Bidirectional Sync:** Login/logout syncs across both systems
- **Hidden iFrame:** Token exchange happens transparently

**Key Files:**
- `app/Services/SSOService.php` - Token generation and management
- `app/Http/Controllers/Api/SSOController.php` - Token verification API
- `public/assets/back-end/js/sso-handler.js` - Frontend integration

### 3. **Product Variation Management** ✅
- **Variation-Based Ordering:** Vendors can select specific variations
- **Stock Management:** Per-variation stock tracking
- **Price Variations:** Different prices per variation
- **Smart Increment:** Prevents duplicate variation entries

**Key Files:**
- `app/Models/ProductStock.php` - Variation stock model
- `app/Http/Controllers/Vendor/BuyProductsController.php` - Vendor purchase
- `app/Http/Controllers/Admin/ShopAndFranchiseOrdersController.php` - Order approval

### 4. **Multi-Vendor System**
- **Vendor Types:** Shops, Franchises, Regular Vendors
- **Order Management:** Shop orders, Franchise orders
- **Wallet System:** Integrated payment wallet
- **Product Purchase:** Vendors buy from admin inventory

### 5. **Bonus/MLM System**
- Multiple bonus types: DDS ref bonus, shop bonus, franchise bonus, etc.
- Leadership bonus, vendor bonus, product partner bonus
- Royalty bonus system
- Wallet transaction tracking

---

## 🗄️ Database Structure

### **Key Tables** (230+ migrations)

#### Product Tables
- `products` - Main product data
- `product_stocks` - Variation stock management
- `product_seo` - SEO metadata
- `product_compares` - Product comparisons
- `product_tags` - Tagging system

#### Order Tables
- `orders` - Main orders
- `order_details` - Order line items
- `order_transactions` - Payment transactions
- `shop_orders` - Shop purchase orders
- `franchise_orders` - Franchise purchase orders

#### User Tables
- `users` - Customer accounts
- `sellers` - Vendor accounts
- `admins` - Admin accounts
- `delivery_men` - Delivery personnel

#### E-commerce Tables
- `categories`, `brands`, `attributes`
- `coupons`, `flash_deals`, `banners`
- `wishlists`, `carts`, `reviews`
- `refund_requests`, `support_tickets`

#### Wallet & Bonus Tables
- `customer_wallets`, `seller_wallets`, `admin_wallets`
- `wallet_transactions`
- `loyalty_point_transactions`
- `bonus_management`, `bonus_wallets`

#### AI Tables
- `ai_generated_images` - Stores AI-generated product images

---

## 🔧 Configuration

### **Environment Variables** (.env)

```env
# App
APP_NAME=6valy
APP_ENV=local/production
APP_DEBUG=true/false
APP_KEY=base64:...
APP_URL=https://dewdropskin.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=6valley
DB_USERNAME=root
DB_PASSWORD=

# AI Configuration
GOOGLE_GEMINI_API_KEY=your_api_key
SSO_SECRET=your-super-secret-key

# Storage
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

# Payment Gateways
# Multiple payment methods configured (Stripe, Razorpay, PayPal, etc.)
```

### **Key Services Configured**
- **Payment Methods:** 14+ payment gateways supported
- **SMS Gateways:** Twilio, Nexmo support
- **Storage:** Local + AWS S3
- **Email:** SMTP configuration
- **Social Login:** Google, Facebook, Apple support

---

## 📦 Key Dependencies

### Backend (composer.json)
```json
{
  "laravel/framework": "^10.10",
  "laravel/passport": "^12.3",
  "laravel/sanctum": "^3.3",
  "intervention/image": "^2.7",
  "guzzlehttp/guzzle": "^7.2",
  "nwidart/laravel-modules": "^10.0",
  "maatwebsite/excel": "*",
  "barryvdh/laravel-dompdf": "^2.0",
  "kreait/firebase-php": "^7.0",
  "stripe/stripe-php": "^13.9",
  "razorpay/razorpay": "^2.9",
  "paypal/rest-api-sdk-php": "^1.6"
}
```

### Frontend (package.json)
```json
{
  "bootstrap": "^4.0.0",
  "jquery": "^3.2",
  "vue": "^2.5.17",
  "laravel-mix": "^5.0.1",
  "axios": "^0.19"
}
```

---

## 🔐 Security Features

1. **Authentication:**
   - OAuth2 via Laravel Passport
   - API tokens via Sanctum
   - SSO with HMAC-signed tokens

2. **Authorization:**
   - Role-based access control
   - Admin/Seller/Customer permissions
   - API middleware protection

3. **Data Protection:**
   - CSRF protection
   - XSS prevention
   - SQL injection protection (Eloquent ORM)
   - Input validation

4. **Session Management:**
   - Secure session handling
   - Token expiry (5 minutes for SSO)
   - Cache-based token storage

---

## 🌐 Routes

### **Admin Routes** (`routes/admin/routes.php`)
- Dashboard, orders, products, customers
- Vendor management, reports, settings
- AI product generation endpoints
- Shop/franchise order management

### **Web Routes** (`routes/web/routes.php`)
- Customer authentication
- Product browsing, cart, checkout
- User dashboard, orders, wallet
- SSO endpoints

### **API Routes** (`routes/rest_api/`)
- Mobile app endpoints
- Third-party integrations
- SSO verification API

---

## 📱 User Roles

1. **Admin** - Full system access, manages vendors and products
2. **Seller/Vendor** - Manages their shop, products, orders
3. **Shop Owner** - Purchases from admin, sells to customers
4. **Franchise Owner** - Similar to shops with different bonus structure
5. **Customer** - Browses, purchases products
6. **Delivery Man** - Handles order deliveries

---

## 🎨 Theme System

- **Active Theme:** theme_fashion
- **Theme Location:** `resources/themes/`
- **View Override:** Theme-specific views override default views
- **Assets:** Separate CSS/JS per theme
- **Responsive:** Mobile-friendly design

---

## 🔄 Recent Major Updates

1. **AI Implementation** (Complete)
   - Full AI product generation workflow
   - 8 image generation with variations
   - 800+ word description generation
   - Auto-fill all product fields

2. **SSO Implementation** (Complete)
   - Seamless login/logout across systems
   - Secure token-based authentication
   - iFrame-based silent authentication

3. **Variation System** (Complete)
   - Product variation ordering
   - Per-variation stock management
   - Variation-specific pricing

4. **Admin Theme Update** (Complete)
   - Responsive admin interface
   - Mobile-optimized views

---

## 🚀 Deployment

### **Production Server:** Plesk VPS
- **URL:** https://dewdropskin.com
- **Git Deployment:** Configured
- **SSL:** Enabled
- **Web Server:** Apache/Nginx (Plesk managed)

### **Deployment Steps:**
1. Pull latest code: `git pull origin main`
2. Update dependencies: `composer install --no-dev`
3. Clear caches: `php artisan cache:clear`
4. Migrate database: `php artisan migrate`
5. Set permissions: `chmod 755 storage bootstrap/cache`

---

## 📝 Documentation Files

- `README.md` - Basic Laravel info
- `AI_IMPLEMENTATION_COMPLETE.md` - AI feature details
- `SSO_IMPLEMENTATION_GUIDE.md` - SSO setup guide
- `VENDOR_VARIATION_PURCHASE.md` - Variation ordering guide
- `PRODUCTION_DEPLOYMENT_GUIDE.md` - Deployment instructions
- `AI_MODEL_SELECTION_GUIDE.md` - AI model configuration
- `RESPONSIVE_ADMIN_THEME_UPDATE.md` - Admin UI updates
- `VARIATION_ORDERING_IMPLEMENTATION.md` - Variation system details

---

## 🐛 Known Issues / Notes

1. **AI Image 404 on Production:** Requires proper directory permissions
   - Solution documented in `PRODUCTION_DEPLOYMENT_GUIDE.md`

2. **Storage Symlink:** Must run `php artisan storage:link`

3. **Session Storage:** Consider Redis for production

4. **Cache Driver:** File cache suitable for single-server, use Redis for scaling

---

## 📊 Statistics

- **Controllers:** 200+ controller files
- **Models:** 121 Eloquent models
- **Migrations:** 230+ database migrations
- **Routes:** 100+ defined routes
- **Languages:** 30 language files
- **Themes:** Multiple theme support
- **Views:** 652+ Blade templates
- **Services:** 74 service classes
- **Repositories:** 87 repository classes

---

## 🔗 Integration Points

1. **Account Folder:** Separate Laravel app for wallet system
   - Location: `/account/`
   - SSO integration with main store

2. **Payment Gateways:** 14+ payment methods
   - Stripe, Razorpay, PayPal, Flutterwave, etc.

3. **SMS Services:** Twilio, Nexmo

4. **Cloud Storage:** AWS S3 support

5. **Firebase:** Push notification support

6. **Google Gemini:** AI product generation

---

## 🎯 Business Logic

### **Product Flow:**
1. Admin adds products with variations
2. Vendors (shops/franchises) purchase from admin
3. Stock transferred from admin to vendor
4. Vendors sell to customers
5. Bonus distribution based on sales

### **Order Flow:**
1. Customer places order
2. Payment processed (wallet/gateway)
3. Order assigned to vendor/admin
4. Delivery man assigned
5. Order delivered
6. Bonuses distributed

### **Variation Flow:**
1. Vendor selects product with variation
2. Chooses specific variation (color, size, etc.)
3. Quantity validated against variation stock
4. Order placed with variation details
5. Admin approves order
6. Variation stock decremented from admin
7. Variation stock incremented to vendor

---

## 💡 Best Practices Implemented

1. **Repository Pattern:** Data access abstraction
2. **Service Layer:** Business logic separation
3. **Dependency Injection:** Constructor injection throughout
4. **Traits:** Reusable functionality (Storage, Cache)
5. **Events & Listeners:** Decoupled event handling
6. **Observer Pattern:** Model lifecycle hooks
7. **API Resources:** Structured API responses
8. **Form Requests:** Validation separation
9. **Middleware:** Request filtering and modification

---

## 🔮 Future Enhancement Possibilities

1. **AI Improvements:**
   - Multi-model comparison
   - Image style transfer
   - Automatic tagging improvements

2. **Performance:**
   - Redis caching
   - Queue workers for heavy tasks
   - CDN integration

3. **Features:**
   - Live chat support
   - Product recommendations
   - Advanced analytics dashboard
   - Mobile app (API ready)

---

## 📞 Support & Maintenance

- **Logs Location:** `storage/logs/laravel.log`
- **Cache Clear:** `php artisan cache:clear`
- **Config Cache:** `php artisan config:cache`
- **Route Cache:** `php artisan route:cache`
- **View Cache:** `php artisan view:clear`

---

**Last Updated:** October 22, 2025  
**Version:** Production-ready  
**Status:** ✅ Fully Functional
