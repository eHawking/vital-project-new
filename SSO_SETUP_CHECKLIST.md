# SSO Setup Checklist ✅

## 🚀 Quick Setup Guide (5 Minutes)

### **Step 1: Configure Main Script**

Edit `.env` file in root directory:

```bash
# Add these lines at the bottom
SSO_SECRET=change-this-to-a-random-secret-key-in-production-min-32-chars
APP_URL=https://dewdropskin.com
```

**Generate Random Secret:**
```bash
php artisan tinker
>>> Str::random(64)
# Copy the output and use as SSO_SECRET
```

---

### **Step 2: Configure Account Folder**

Edit `account/core/.env` file:

```bash
# Add these lines (CRITICAL for SSO to work!)
MAIN_SCRIPT_URL=https://dewdropskin.com

# Session configuration for iframe SSO support
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_PARTITIONED_COOKIE=true
```

**⚠️ IMPORTANT:** 
- `SESSION_SAME_SITE=none` is **required** for cookies to work in iframes
- `SESSION_SECURE_COOKIE=true` is **required** when same_site=none
- Your site **MUST** use HTTPS for this to work

---

### **Step 3: Include JavaScript**

Edit `resources/themes/theme_fashion/theme-views/layouts/app.blade.php`

Find the closing `</body>` tag and add BEFORE it:

```html
<!-- SSO Handler -->
<script src="{{ asset('public/assets/back-end/js/sso-handler.js') }}"></script>
</body>
```

---

### **Step 4: Clear Cache**

```bash
# Main Script
php artisan cache:clear
php artisan config:clear

# Account Folder
cd account/core
php artisan cache:clear
php artisan config:clear
```

---

### **Step 5: Test Login**

1. Open browser (Incognito/Private mode)
2. Open Developer Tools (F12)
3. Go to Console tab
4. Login to main script

**Expected Console Output:**
```
SSO URL received: https://dewdropskin.com/account/user/sso-login?token=...
SSO login completed
```

5. Navigate to `/account` - Should be already logged in! ✅

---

### **Step 6: Test Logout**

1. Click logout on main script
2. Should redirect through `/account/user/sso-logout`
3. Then redirect back to main script home
4. Try accessing `/account` - Should show login page ✅

---

## ✅ Verification Checklist

- [ ] SSO_SECRET added to main script `.env`
- [ ] MAIN_SCRIPT_URL added to account folder `.env`
- [ ] sso-handler.js included in layout
- [ ] Cache cleared on both scripts
- [ ] Login test passed
- [ ] Logout test passed
- [ ] Console shows SSO messages
- [ ] No errors in Laravel logs

---

## 🐛 Quick Troubleshooting

### **Problem: Logged into main script but logged out in account folder** ⚠️ MOST COMMON

**Symptom:** Login successful on main site, but `/account` pages show as logged out

**Root Cause:** Session cookies blocked in iframe due to `SameSite` policy

**Fix:**
```bash
# Add to account/core/.env
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_PARTITIONED_COOKIE=true

# Then clear cache
php artisan config:clear
php artisan cache:clear
```

**Verify HTTPS:** Your site MUST use HTTPS (not HTTP) for `SameSite=none` to work

---

### **Problem: SSO URL not in response**

```bash
# Check main script log
tail -f storage/logs/laravel.log | grep SSO
```

**Fix:** Make sure SSOService is working and cache is enabled

---

### **Problem: Token expired**

**Symptom:** "SSO token expired or invalid"

**Fix:** Tokens expire in 5 minutes. Login again.

---

### **Problem: Password mismatch**

**Symptom:** "Account credentials do not match"

**Fix:** User password must be identical in both databases

---

### **Problem: User not found**

**Symptom:** "Account not found. Please register first."

**Fix:** User must exist in account folder database with same username or email

---

## 📊 Check Logs

### **Main Script Logs:**
```bash
tail -f storage/logs/laravel.log
```

Look for:
- `SSO Login Token Generated`
- `SSO Token Verified`

### **Account Folder Logs:**
```bash
tail -f account/core/storage/logs/laravel.log
```

Look for:
- `SSO Login Successful`
- `Token verification failed` (if error)

---

## 🎯 What Should Happen

### **On Login:**
```
1. User logs in via modal on main site
   ↓
2. Main script creates SSO token
   ↓
3. JavaScript detects sso_url in AJAX response
   ↓
4. Hidden iFrame loads account folder SSO URL
   ↓
5. Account folder verifies token with main script API
   ↓
6. Account folder auto-logs in user
   ✅ User logged into BOTH systems
```

### **On Logout:**
```
1. User clicks logout on main site
   ↓
2. Main script logs out user
   ↓
3. Redirects to account folder SSO logout
   ↓
4. Account folder logs out user
   ↓
5. Redirects back to main site home
   ✅ User logged out of BOTH systems
```

---

## 🔒 Security Notes

- ✅ Tokens expire in 5 minutes
- ✅ Tokens are one-time use only
- ✅ Tokens have HMAC signatures
- ✅ No passwords in URLs
- ✅ Passwords verified on both sides

---

## 📝 Configuration Summary

| File | Setting | Value |
|------|---------|-------|
| `.env` | SSO_SECRET | Random 64-char string |
| `.env` | APP_URL | https://dewdropskin.com |
| `account/core/.env` | MAIN_SCRIPT_URL | https://dewdropskin.com |
| `app.blade.php` | JavaScript | sso-handler.js included |

---

## ✨ Done!

Your SSO system is now configured. Users can:
- ✅ Login once on main site
- ✅ Access account folder without re-login
- ✅ Logout from anywhere, logged out everywhere
- ✅ Seamless experience like one system

---

**Status:** Ready to Test 🚀  
**Next:** Login and check console logs!
