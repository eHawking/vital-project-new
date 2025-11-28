# SSO Quick Fix - Not Logging Into Second Script

## 🐛 Issues Found:

1. ❌ JavaScript file NOT included in layout
2. ❌ SSO_SECRET not in main script .env
3. ❌ Session configuration missing in account folder

---

## ✅ Fix #1: Add JavaScript to Layout (DONE ✓)

**File:** `resources/themes/theme_fashion/theme-views/layouts/app.blade.php`

✅ **Already added** - JavaScript is now included

---

## ✅ Fix #2: Add SSO_SECRET to Main Script

**Edit:** `.env` (root directory)

Add this line:

```bash
SSO_SECRET=your-random-secret-key-min-32-characters-long-change-this
```

**Generate a secure key:**

```bash
# Run in main script root
php artisan tinker
>>> Str::random(64)
# Copy output and use as SSO_SECRET
>>> exit
```

---

## ✅ Fix #3: Configure Account Folder Session

**Edit:** `account/core/.env`

Add these lines:

```bash
# SSO Configuration
MAIN_SCRIPT_URL=https://dewdropskin.com

# Session for iframe SSO (CRITICAL!)
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_PARTITIONED_COOKIE=true
```

---

## ✅ Fix #4: Clear All Caches

```bash
# Main Script
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Account Folder
cd account/core
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🧪 Test After Fixes

1. **Clear browser cache and cookies** (Ctrl+Shift+Delete)
2. **Open browser in Incognito mode**
3. **Open Developer Tools** (F12) → Console tab
4. **Login** to main script

### Expected Console Output:

```javascript
SSO URL received: https://dewdropskin.com/account/user/sso-login?token=...
Found SSO login URL in storage
SSO login completed
```

5. **Check main script logs:**

```bash
Get-Content storage/logs/laravel.log -Tail 50 | Select-String "SSO"
```

**Expected:**
```
[INFO] SSO Login Token Generated {"user_id":2,"username":"..."}
```

6. **Check account folder logs:**

```bash
Get-Content account/core/storage/logs/laravel.log -Tail 50 | Select-String "SSO"
```

**Expected:**
```
[INFO] SSO Login Successful {"user_id":456,"username":"...","session_id":"..."}
```

7. **Navigate to `/account`** → Should be logged in! ✅

---

## 📋 Complete Configuration Checklist

### Main Script `.env`:
```bash
APP_URL=https://dewdropskin.com
SSO_SECRET=random-64-character-string-here
```

### Account Folder `account/core/.env`:
```bash
APP_URL=https://dewdropskin.com/account
MAIN_SCRIPT_URL=https://dewdropskin.com
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_PARTITIONED_COOKIE=true
```

### Layout File:
```html
<!-- Already added ✓ -->
<script src="{{ asset('public/assets/back-end/js/sso-handler.js') }}"></script>
```

---

## ⚠️ Important Notes

1. **HTTPS Required:** Your site MUST use HTTPS (not HTTP) for SSO to work
2. **Same Domain:** Both scripts should be on same domain (dewdropskin.com)
3. **Browser Cookies:** Make sure cookies are enabled
4. **Cache:** Always clear cache after .env changes

---

## 🔍 Debug If Still Not Working

### Check 1: JavaScript Loaded?
```javascript
// In browser console
console.log('SSO Handler loaded:', typeof handleSSOLogin);
```

### Check 2: SSO Service Working?
```bash
# Main script
php artisan tinker
>>> $service = app(\App\Services\SSOService::class);
>>> $result = $service->isAccountFolderAccessible();
>>> dump($result); // Should be true
```

### Check 3: API Endpoint Working?
```bash
# Test the API directly
curl -X POST https://dewdropskin.com/api/sso/verify-token \
  -H "Content-Type: application/json" \
  -d '{"token":"test","type":"login"}'
```

**Expected:** JSON response with "Invalid or expired token"

---

## ✅ Summary

1. ✅ JavaScript added to layout
2. ⏳ Add SSO_SECRET to main .env
3. ⏳ Add session config to account .env
4. ⏳ Clear all caches
5. ⏳ Test login flow

**After completing steps 2-4, SSO should work!** 🚀
