# 🔧 SSO Auto-Fix - One Command Solution

## 🚀 For Plesk Terminal (Linux Server)

Upload `sso-auto-fix.sh` to your server and run:

```bash
bash sso-auto-fix.sh
```

**That's it!** The script will automatically:

- ✅ Generate secure SSO_SECRET
- ✅ Configure all .env settings
- ✅ Set up session for iframe
- ✅ Include JavaScript in layout
- ✅ Clear all caches
- ✅ Fix permissions
- ✅ Verify everything

---

## 💻 For Windows (Local Development)

In PowerShell, navigate to your project and run:

```powershell
powershell -ExecutionPolicy Bypass -File sso-auto-fix.ps1
```

---

## 📋 What Gets Fixed

| Issue | Auto-Fix |
|-------|----------|
| ❌ No SSO_SECRET | ✅ Generates secure 64-char key |
| ❌ No MAIN_SCRIPT_URL | ✅ Adds from APP_URL |
| ❌ Wrong SESSION_SAME_SITE | ✅ Sets to 'none' |
| ❌ Missing session config | ✅ Adds all required settings |
| ❌ JavaScript not included | ✅ Adds to layout |
| ❌ Old caches | ✅ Clears everything |
| ❌ Wrong permissions | ✅ Fixes storage (Linux) |

---

## 📊 Expected Output

```
========================================
  SSO Auto-Fix Script
========================================

========================================
Analyzing SSO Configuration
========================================

Checking SSO_SECRET...
⚠ ISSUE: SSO_SECRET not configured
  Generating secure SSO_SECRET...
✓ FIX: Generated and configured SSO_SECRET

Checking MAIN_SCRIPT_URL...
✓ FIX: Added MAIN_SCRIPT_URL to account/.env

Checking session configuration...
✓ FIX: Updated session configuration for iframe support

========================================
Clearing Caches
========================================

✓ FIX: Main script caches cleared
✓ FIX: Account folder caches cleared

========================================
Auto-Fix Complete
========================================

✓ Fixed Issues: 5

Current Configuration:
----------------------
✓ SSO_SECRET: Configured (length: 64)
✓ APP_URL: https://dewdropskin.com
✓ MAIN_SCRIPT_URL: https://dewdropskin.com
✓ SESSION_SAME_SITE: none

==========================================

NEXT STEPS:

1. Configuration has been automatically fixed ✓
2. Caches have been cleared ✓
3. Ready to test login!

To test:
  1. Open browser (incognito mode)
  2. Press F12 → Console tab
  3. Login to your website
  4. Watch console for SSO messages

SSO system should now be working!
```

---

## 🧪 After Auto-Fix - Test Login

### Step 1: Open Browser
- Use **Incognito/Private mode**
- Press **F12** to open console

### Step 2: Login
Watch the console, you should see:

```javascript
SSO Handler Loaded
AJAX Success: /customer/auth/login
Login Response: {status: 'success', sso_url: '...'}
SSO URL received: ...
SSO: Creating iframe for URL: ...
SSO: Iframe loaded successfully
```

### Step 3: Check You're Logged Into Both
1. Stay on main site → Should be logged in ✅
2. Navigate to `/account` → Should be logged in ✅

---

## 🔍 Monitor Logs (Optional)

### On Plesk Terminal:

```bash
# Watch main script logs
tail -f storage/logs/laravel.log | grep --color=auto SSO

# Watch account folder logs (in another terminal)
tail -f account/core/storage/logs/laravel.log | grep --color=auto SSO
```

---

## ❓ If Still Not Working

### 1. Re-run the auto-fix:
```bash
bash sso-auto-fix.sh
```

### 2. Check the logs:
```bash
tail -50 storage/logs/laravel.log | grep SSO
```

### 3. Test API endpoint:
```bash
curl -X POST https://dewdropskin.com/api/sso/verify-token \
  -H "Content-Type: application/json" \
  -d '{"token":"test","type":"login"}'
```

Expected: `{"success":false,"message":"Invalid or expired token"}`

### 4. Verify HTTPS:
SSO requires HTTPS. Check:
```bash
curl -I https://dewdropskin.com
```

Should return `200` or `302`, not error.

---

## 🎯 Quick Reference

| Task | Command |
|------|---------|
| **Auto-fix everything** | `bash sso-auto-fix.sh` |
| **Check config** | `php check-sso-config.php` |
| **Clear caches** | `bash sso-quick-setup.sh` |
| **Monitor logs** | `tail -f storage/logs/laravel.log \| grep SSO` |

---

## ✅ Success Indicators

After auto-fix and login, you should have:

- ✅ Console shows "SSO Handler Loaded"
- ✅ Console shows "SSO URL received"
- ✅ Console shows "Iframe loaded successfully"
- ✅ Main logs show "SSO Sync Successful"
- ✅ Account logs show "SSO Login Successful"
- ✅ Can access `/account` without re-login
- ✅ Logout from main site = logout from account too

---

## 📞 Still Need Help?

If auto-fix doesn't resolve the issue:

1. Check you're running on HTTPS (not HTTP)
2. Verify both Laravel applications are accessible
3. Check PHP version (>= 8.0)
4. Ensure cache driver is working (file/redis)
5. Look for errors in Laravel logs

---

**Created for effortless SSO setup** 🚀

No manual configuration needed - just run the auto-fix script!
