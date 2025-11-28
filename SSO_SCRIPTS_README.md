# SSO Setup Scripts for Plesk Terminal

Quick setup and testing scripts for SSO (Single Sign-On) system.

---

## 📁 Available Scripts

### 1. **sso-quick-setup.sh** (RECOMMENDED)
✅ **Best for Plesk terminal** - Runs everything automatically

**What it does:**
- Checks configuration
- Clears all caches (main + account folder)
- Verifies file permissions
- Tests account folder accessibility
- Shows recent SSO logs
- Provides next steps

**Usage:**
```bash
bash sso-quick-setup.sh
```

**Time:** ~10 seconds

---

### 2. **sso-setup-and-test.sh**
Interactive version with manual confirmations

**Usage:**
```bash
bash sso-setup-and-test.sh
```

---

### 3. **check-sso-config.php**
Configuration checker only (used by above scripts)

**Usage:**
```bash
php check-sso-config.php
```

---

## 🚀 Quick Start in Plesk

### **Step 1: Upload Files**

Upload these files to your domain root:
- `sso-quick-setup.sh`
- `check-sso-config.php`

### **Step 2: Make Executable**

In Plesk Terminal:
```bash
chmod +x sso-quick-setup.sh
```

### **Step 3: Run**

```bash
bash sso-quick-setup.sh
```

That's it! ✅

---

## 📋 What the Script Checks

### ✅ Configuration:
- `SSO_SECRET` in main `.env`
- `APP_URL` in main `.env`
- `MAIN_SCRIPT_URL` in account `.env`
- `SESSION_SAME_SITE=none` in account `.env`
- `SESSION_SECURE_COOKIE=true` in account `.env`

### ✅ Files:
- `app/Services/SSOService.php`
- `app/Http/Controllers/Api/SSOController.php`
- `public/assets/back-end/js/sso-handler.js`
- `account/core/app/Http/Controllers/User/Auth/SSOController.php`

### ✅ Layout:
- JavaScript included in `app.blade.php`

### ✅ System:
- Cache driver configured
- Storage directories writable
- Account folder accessible via URL

---

## 🐛 Common Issues & Fixes

### Issue 1: Permission Denied

**Error:**
```
bash: ./sso-quick-setup.sh: Permission denied
```

**Fix:**
```bash
chmod +x sso-quick-setup.sh
bash sso-quick-setup.sh
```

---

### Issue 2: Not in Laravel Directory

**Error:**
```
ERROR: Not in Laravel root directory!
```

**Fix:**
```bash
cd /path/to/your/laravel/root
bash sso-quick-setup.sh
```

---

### Issue 3: Account Folder Not Accessible

**Error:**
```
✗ Account folder not accessible (HTTP 404)
```

**Fix:**
Check your web server configuration. Account folder should be accessible at:
```
https://yourdomain.com/account
```

---

### Issue 4: Storage Not Writable

**Error:**
```
✗ Main script storage/logs NOT writable
```

**Fix:**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 775 account/core/storage/
chmod -R 775 account/core/bootstrap/cache/
```

---

## 📊 Expected Output

### ✅ Successful Setup:

```
========================================
  SSO Quick Setup - Auto Mode
========================================

========================================
Step 1: Configuration Check
========================================

✓ Checking Main Script Configuration...
  ✅ SSO_SECRET configured (length: 64)
  ✅ APP_URL configured: https://dewdropskin.com

✓ Checking Account Folder Configuration...
  ✅ MAIN_SCRIPT_URL configured: https://dewdropskin.com
  ✅ SESSION_SAME_SITE=none (correct for iframe)
  ✅ SESSION_SECURE_COOKIE configured

✓ Checking Required Files...
  ✅ SSO Service exists
  ✅ API Controller exists
  ✅ JavaScript Handler exists
  ✅ Account SSO Controller exists

✓ Checking Layout Configuration...
  ✅ SSO JavaScript included in layout

========================================
Step 2: Clearing Main Script Caches
========================================

✓ Config cache cleared
✓ Application cache cleared
✓ View cache cleared
✓ Route cache cleared

========================================
Step 3: Clearing Account Folder Caches
========================================

✓ Account config cache cleared
✓ Account application cache cleared
✓ Account view cache cleared

========================================
Step 4: Checking Permissions
========================================

✓ Main script storage/logs writable
✓ Account folder storage/logs writable

========================================
Step 5: Testing Account Folder Access
========================================

Testing: https://dewdropskin.com/account
✓ Account folder accessible (HTTP 302)

========================================
Step 6: Recent SSO Activity
========================================

Main Script SSO Logs:
  No SSO logs yet (expected before first login)

Account Folder SSO Logs:
  No SSO logs yet (expected before first login)

========================================
Setup Complete!
========================================

✓ All caches cleared
✓ Configuration checked

NEXT STEPS:

1. Open browser in Incognito/Private mode
2. Press F12 to open Developer Console
3. Go to Console tab
4. Login to your website
```

---

## 🧪 Testing After Setup

### 1. **Open Browser Console**
- Press `F12`
- Go to **Console** tab

### 2. **Login**

### 3. **Check Console Output**

**Expected:**
```javascript
SSO Handler Loaded
AJAX Success: /customer/auth/login
Login Response: {status: 'success', sso_url: '...'}
SSO URL received: https://dewdropskin.com/account/user/sso-login?token=...
SSO: Creating iframe for URL: ...
SSO: Iframe appended to body
SSO: Iframe loaded successfully
```

### 4. **Check Logs**

```bash
# Watch main script logs
tail -f storage/logs/laravel.log | grep SSO

# Watch account folder logs (in another terminal)
tail -f account/core/storage/logs/laravel.log | grep SSO
```

**Expected in main script:**
```
[INFO] SSO Login Token Generated and Cached
[INFO] SSO URL Created
[INFO] SSO Sync Successful - Login Complete
```

**Expected in account folder:**
```
[INFO] SSO Login Successful
```

### 5. **Navigate to /account**

Should be **already logged in**! ✅

---

## 🔍 Manual Testing Commands

### Test API Endpoint:
```bash
curl -X POST https://dewdropskin.com/api/sso/verify-token \
  -H "Content-Type: application/json" \
  -d '{"token":"test","type":"login"}'
```

**Expected:** `{"success":false,"message":"Invalid or expired token"}`

### Test Account Folder:
```bash
curl -I https://dewdropskin.com/account
```

**Expected:** HTTP 200 or 302

### Check Cache:
```bash
php artisan tinker
>>> Cache::get('test-key', 'not-found')
>>> exit
```

---

## 📝 After Running Script

### If Everything is ✅:
1. Open browser (incognito)
2. Open console (F12)
3. Login
4. Check console for SSO messages
5. Navigate to /account → Should be logged in

### If You See ❌:
1. Fix the errors shown
2. Run script again
3. Clear browser cache
4. Try login again

---

## 🎯 One-Line Setup

```bash
chmod +x sso-quick-setup.sh && bash sso-quick-setup.sh
```

---

## 💡 Tips

1. **Always run in Laravel root directory** (where `artisan` file is)
2. **Always clear browser cache** before testing
3. **Use incognito mode** to avoid cached sessions
4. **Check both console and logs** for complete picture
5. **HTTPS is required** for SSO to work

---

## 📞 Support

If SSO still not working after running script:

1. Check all ❌ errors in script output
2. Verify HTTPS is enabled
3. Check both Laravel log files
4. Check browser console for errors
5. Ensure cache driver is working

---

**Created for easy SSO setup in Plesk environment** 🚀
