# SSO (Single Sign-On) Implementation Guide

## 🎯 Overview

This SSO system creates a seamless authentication experience between the main e-commerce script and the account folder (wallet system). When a user logs in to the main script, they are automatically logged into the account folder, and vice versa for logout.

---

## 🏗️ Architecture

```
Main Script (Store)                Account Folder (Wallet)
       |                                   |
   Login Modal                        Login Page
       |                                   |
       v                                   |
  LoginController                          |
       |                                   |
       |---> SSOService                    |
       |       |                           |
       |       |-- Generate Token          |
       |       |-- Store in Cache (5 min)  |
       |       |                           |
       |       v                           |
       |   Return SSO URL + Token          |
       |                                   |
       |---[SSO URL in AJAX Response]---->|
       |                                   |
    JavaScript                             |
       |                                   |
       |--[Hidden iFrame]----------------->|
                                           |
                                      SSOController
                                           |
                                           |-- Verify Token via API
                                           |-- Check User Exists
                                           |-- Verify Password
                                           |-- Auto Login
                                           v
                                      User Dashboard
```

---

## 📁 Files Created/Modified

### **Main Script Files:**

1. **`app/Services/SSOService.php`** ✨ NEW
   - Generates secure SSO tokens
   - Manages token cache (5 min expiry)
   - Handles login/logout sync

2. **`app/Http/Controllers/Api/SSOController.php`** ✨ NEW
   - Verifies tokens from account folder
   - Returns user data for authentication

3. **`app/Http/Controllers/Customer/Auth/LoginController.php`** ✏️ MODIFIED
   - Triggers SSO after successful login
   - Redirects to account folder on logout

4. **`routes/web/routes.php`** ✏️ MODIFIED
   - Added API route: `/api/sso/verify-token`

5. **`public/assets/back-end/js/sso-handler.js`** ✨ NEW
   - JavaScript to handle SSO redirect via iFrame

### **Account Folder Files:**

6. **`account/core/app/Http/Controllers/User/Auth/SSOController.php`** ✨ NEW
   - Handles SSO login requests
   - Verifies tokens with main script
   - Authenticates users automatically

7. **`account/core/routes/user.php`** ✏️ MODIFIED
   - Added routes: `/user/sso-login` and `/user/sso-logout`

---

## 🔐 Security Features

### **1. Secure Token Generation**
```php
$signature = hash_hmac('sha256', $userId . $timestamp . $random, $secret);
$token = base64_encode($userId . '|' . $timestamp . '|' . $random . '|' . $signature);
```

### **2. Token Expiry**
- Login tokens: 5 minutes
- Logout tokens: 2 minutes
- One-time use only

### **3. Password Verification**
- Account folder verifies password matches
- No credentials in URLs
- Tokens deleted after use

### **4. HMAC Signature**
- Prevents token tampering
- Secret key required
- Signature verification

---

## ⚙️ Configuration

### **Step 1: Add to Main Script `.env`**

```env
# SSO Configuration
SSO_SECRET=your-super-secret-key-change-this-in-production
APP_URL=https://dewdropskin.com
```

### **Step 2: Add to Account Folder `.env`**

```env
# Main Script URL for SSO
MAIN_SCRIPT_URL=https://dewdropskin.com
```

### **Step 3: Include JavaScript in Layout**

Add to `resources/themes/theme_fashion/theme-views/layouts/app.blade.php` before closing `</body>` tag:

```html
<!-- SSO Handler -->
<script src="{{ asset('public/assets/back-end/js/sso-handler.js') }}"></script>
```

---

## 🔄 How It Works

### **Login Flow:**

1. **User logs in via modal** on main script
2. **LoginController** authenticates user
3. **SSOService** generates secure token
4. Token stored in cache with user data (including plain password)
5. **AJAX response** includes `sso_url` with token
6. **JavaScript** detects `sso_url` in response
7. Creates hidden iFrame pointing to account folder SSO endpoint
8. **Account Folder** receives token
9. Makes API call to main script to verify token
10. **Main Script API** validates token, returns user data
11. **Account Folder** finds user by username/email
12. Verifies password matches
13. **Auto-logs** user into account folder
14. User is now logged into BOTH systems ✅

### **Logout Flow:**

1. **User clicks logout** on main script
2. **LoginController** logs out from main script
3. **SSOService** generates logout token
4. **Redirect** to account folder SSO logout endpoint
5. **Account Folder** verifies token
6. Logs out user from account folder
7. **Redirects back** to main script home
8. User is logged out of BOTH systems ✅

---

## 🧪 Testing

### **Test Login:**

```bash
# 1. Open browser dev tools (F12)
# 2. Go to Network tab
# 3. Login to main script
# 4. Check console for:
```

**Expected Console Output:**
```
SSO URL received: https://dewdropskin.com/account/user/sso-login?token=...
Found SSO login URL in storage
SSO login completed
```

**Expected Network Requests:**
```
1. POST /customer/auth/login (main script)
   Response: { status: 'success', sso_url: '...' }

2. GET /account/user/sso-login?token=... (iFrame)
   Status: 302 (redirect to dashboard)

3. POST /api/sso/verify-token (from account folder)
   Response: { success: true, data: {...} }
```

### **Test Logout:**

```bash
# 1. Click logout on main script
# 2. Should redirect to: /account/user/sso-logout?token=...
# 3. Should then redirect back to main script home
```

---

## 🐛 Troubleshooting

### **Issue: SSO URL not appearing in response**

**Check:**
```php
// In LoginController.php
Log::info('SSO Result:', $ssoResult);
```

**Solution:** Ensure SSOService is properly injected

---

### **Issue: Token verification fails**

**Check Laravel logs:**
```bash
# Main Script
tail -f storage/logs/laravel.log | grep SSO

# Account Folder  
tail -f account/core/storage/logs/laravel.log | grep SSO
```

**Common causes:**
- Token expired (>5 minutes)
- Wrong SSO_SECRET in config
- Cache not working

---

### **Issue: User not found in account folder**

**Error:** "Account not found. Please register first."

**Solution:** User must exist in BOTH databases with matching:
- Username OR Email
- Password (same hash)

---

### **Issue: Password mismatch**

**Error:** "Account credentials do not match"

**Cause:** Password hash different between databases

**Solution:** Ensure password is synced between systems

---

## 🔒 Production Checklist

- [ ] Change `SSO_SECRET` to strong random key
- [ ] Enable HTTPS on both domains
- [ ] Configure CORS if needed
- [ ] Set proper cache driver (Redis recommended)
- [ ] Test token expiry
- [ ] Monitor SSO logs
- [ ] Set up error alerting
- [ ] Test with real users

---

## 📊 Monitoring

### **Key Metrics to Track:**

1. **SSO Success Rate**
```php
Log::info('SSO Login Successful', ['user_id' => $user->id]);
```

2. **Token Expiry Rate**
```php
Log::warning('SSO Token Expired', ['token' => $token]);
```

3. **Authentication Failures**
```php
Log::warning('SSO Login: Password mismatch', ['username' => $username]);
```

---

## 🎨 User Experience

### **What Users See:**

1. ✅ Login once on main site
2. ✅ Navigate to wallet (/account) - Already logged in!
3. ✅ No second login required
4. ✅ Logout once - Logs out everywhere
5. ✅ Seamless experience like one system

### **What Users DON'T See:**

- ❌ Token URLs (hidden in iFrame)
- ❌ Second login screen
- ❌ Authentication delays
- ❌ Password prompts

---

## 🚀 Benefits

| Feature | Benefit |
|---------|---------|
| **Single Login** | Users only authenticate once |
| **Secure Tokens** | HMAC-signed, one-time use |
| **Short Expiry** | Tokens expire in 5 minutes |
| **No URL Passwords** | Credentials never in URLs |
| **Automatic Sync** | Login/logout syncs instantly |
| **Independent Systems** | Each Laravel app works standalone |
| **Fallback Safe** | If SSO fails, manual login still works |

---

## 📝 API Reference

### **POST /api/sso/verify-token**

**Request:**
```json
{
    "token": "base64_encoded_token",
    "type": "login" // or "logout"
}
```

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "user_id": 123,
        "username": "john_doe",
        "email": "john@example.com",
        "password": "plain_text_password",
        "timestamp": 1634567890
    }
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Invalid or expired token"
}
```

---

## 🎯 Next Steps

1. ✅ Add SSO configuration to `.env` files
2. ✅ Include sso-handler.js in layout
3. ✅ Test login flow in browser
4. ✅ Test logout flow
5. ✅ Monitor logs for errors
6. ✅ Verify user experience
7. ✅ Deploy to production

---

## 📞 Support

If you encounter issues:

1. Check Laravel logs (both scripts)
2. Verify `.env` configuration
3. Test API endpoint directly
4. Clear cache: `php artisan cache:clear`
5. Check token expiry times

---

**Status:** ✅ Implementation Complete  
**Version:** 1.0  
**Last Updated:** 2025-10-15
