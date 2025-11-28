# 🔄 Reverse SSO - Auto-Login to Main Script After Signup

## 🎯 **What Is This?**

When a user signs up in the **account folder**, they are **automatically logged into the main script** too!

---

## ✨ **Complete SSO System**

| Action | Result |
|--------|--------|
| Login to main script | ✅ Auto-login to account folder |
| Logout from main script | ✅ Auto-logout from account folder |
| **Signup in account folder** | ✅ **Auto-login to main script** (NEW!) |

**Result:** Users never need to login twice! 🎉

---

## 🔧 **Setup Instructions**

### **Step 1: Pull Latest Changes**

```bash
cd ~/httpdocs
git pull origin main
```

### **Step 2: Clear All Caches**

```bash
# Main script
php artisan config:clear
php artisan cache:clear

# Account folder
cd account/core
php artisan config:clear
php artisan cache:clear
cd ../..
```

### **Step 3: Include Reverse SSO in Account Folder Layout**

Find your account folder's main layout file (usually `resources/views/layouts/master.blade.php` or similar).

**Add this before `</body>`:**

```blade
@include('partials.reverse_sso')
```

**Example:**
```blade
    <!-- Other scripts -->
    
    @include('partials.reverse_sso')
</body>
</html>
```

### **Step 4: Configure Environment**

Make sure both `.env` files have matching SSO secrets:

**Main Script `.env`:**
```bash
SSO_SECRET=your-64-character-secret
APP_URL=https://dewdropskin.com
```

**Account Folder `account/core/.env`:**
```bash
SSO_SECRET=your-64-character-secret  # Same as main script
MAIN_SCRIPT_URL=https://dewdropskin.com
```

---

## 🧪 **How To Test**

### **Test Reverse SSO (Signup → Main Login)**

1. **Open two browser windows/tabs side by side:**
   - Window 1: Main script `https://dewdropskin.com`
   - Window 2: Account folder `https://dewdropskin.com/account`

2. **In Window 2 (Account Folder):**
   - Go to signup page
   - Fill in registration form
   - Submit signup

3. **Watch What Happens:**
   - ✅ User created in account folder
   - ✅ Logged into account folder
   - ✅ JavaScript triggers (check console - F12)
   - ✅ Hidden iframe created
   - ✅ Main script receives token

4. **In Window 1 (Main Script):**
   - Refresh the page
   - **You should be logged in!** ✅

---

## 📊 **Expected Console Output**

### **In Account Folder (F12 Console):**

```javascript
Reverse SSO Handler Loaded
Reverse SSO URL set from session
Reverse SSO: Found URL in storage: https://dewdropskin.com/sso/reverse-login?token=...
Reverse SSO: Creating iframe for main script login
Reverse SSO: Iframe appended to body
Reverse SSO: Main script login completed
Reverse SSO: Removing iframe
```

---

## 📝 **Expected Log Output**

### **Account Folder Logs:**

```bash
tail -f account/core/storage/logs/laravel.log | grep "Reverse SSO"
```

**Should show:**
```
[INFO] Reverse SSO Initiated After Registration
[INFO] Reverse SSO: Token verified successfully
```

### **Main Script Logs:**

```bash
tail -f storage/logs/laravel.log | grep "Reverse SSO"
```

**Should show:**
```
[INFO] Reverse SSO Login Successful
```

---

## 🔍 **How It Works (Technical)**

```
┌──────────────────────────────────────────┐
│  1. User Submits Signup Form            │
│     (Account Folder)                     │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  2. Create User Account                  │
│     - Save to database                   │
│     - Save to external store (mysql)     │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  3. Generate Reverse SSO Token           │
│     - User ID + Timestamp + Random       │
│     - HMAC signature                     │
│     - Store in cache (5 minutes)         │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  4. Store SSO URL in Flash Session       │
│     reverse_sso_url: https://...         │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  5. Redirect to User Dashboard           │
│     (Account Folder)                     │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  6. Page Loads with JavaScript           │
│     - reverse-sso-handler.js runs        │
│     - Detects reverse_sso_url in session │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  7. JavaScript Creates Hidden iframe     │
│     src: https://dewdropskin.com/sso/... │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  8. Main Script Receives Request         │
│     ReverseSSOController@reverseLogin    │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  9. Verify Token with Account Folder     │
│     POST /api/sso/verify-reverse-token   │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  10. Account Folder Validates Token      │
│      - Check cache                       │
│      - Return user data                  │
│      - Delete token (one-time use)       │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  11. Main Script Finds User              │
│      User::where('username', ...)        │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  12. Auto-Login User                     │
│      auth()->loginUsingId($user->id)     │
└────────────────┬─────────────────────────┘
                 │
                 v
┌──────────────────────────────────────────┐
│  ✅ USER LOGGED INTO BOTH SYSTEMS! ✅    │
└──────────────────────────────────────────┘
```

---

## 🔒 **Security Features**

| Feature | Description |
|---------|-------------|
| **Token Signature** | HMAC-SHA256 with secret key |
| **Time-Limited** | 5-minute expiry |
| **One-Time Use** | Deleted after verification |
| **Cache-Based** | No database storage |
| **User Verification** | Username must exist in main script |
| **Secure Transport** | HTTPS required |

---

## 🐛 **Troubleshooting**

### **Issue: No Auto-Login**

**Check Console (F12):**
```javascript
Reverse SSO Handler Loaded  ← Should appear
```

If you don't see this, the JavaScript isn't included.

**Fix:**
Add to account folder layout:
```blade
@include('partials.reverse_sso')
```

---

### **Issue: Token Verification Failed**

**Check account folder logs:**
```bash
tail -20 account/core/storage/logs/laravel.log | grep "Reverse SSO"
```

**Common causes:**
1. Cache not working
2. SSO_SECRET mismatch
3. Token expired (>5 minutes)

**Fix:**
```bash
# Test cache
php artisan tinker
>>> Cache::put('test', 'value', 60);
>>> Cache::get('test');
# Should return: "value"
```

---

### **Issue: User Not Found in Main Script**

**Error in logs:**
```
Reverse SSO: User not found in main script
```

**Cause:** User exists in account folder but not in main script database.

**This happens because:**
- Registration only creates user in account folder
- User must also exist in main script database

**Fix:** The registration already handles this by inserting into `mysql_store` connection. Verify:
```php
// RegisterController.php line 158-175
DB::connection('mysql_store')
    ->table('users')
    ->insert([...]);
```

---

### **Issue: iframe Not Loading**

**Check logs:**
```bash
tail -f storage/logs/laravel.log | grep "Reverse SSO"
```

**If you see timeout errors:**
- Main script URL not accessible
- HTTPS issue
- Firewall blocking

**Test manually:**
```bash
curl -I https://dewdropskin.com/sso/reverse-login?token=test
```

Should return 302 or 200, not timeout.

---

## 📋 **Quick Reference**

### **Files Created:**

**Account Folder:**
```
account/core/app/Services/SSOService.php
account/core/public/assets/js/reverse-sso-handler.js
account/core/resources/views/partials/reverse_sso.blade.php
```

**Main Script:**
```
app/Http/Controllers/ReverseSSOController.php
```

### **Routes Added:**

**Account Folder:**
```php
POST /api/sso/verify-reverse-token
```

**Main Script:**
```php
GET /sso/reverse-login
```

### **Console Commands:**

```bash
# Pull changes
git pull origin main

# Clear caches
php artisan config:clear && php artisan cache:clear

# Watch logs (account folder)
tail -f account/core/storage/logs/laravel.log | grep "Reverse SSO"

# Watch logs (main script)
tail -f storage/logs/laravel.log | grep "Reverse SSO"

# Test cache
php artisan tinker
>>> Cache::put('test', 'value', 60);
>>> Cache::get('test');
```

---

## ✅ **Success Checklist**

After signup in account folder:

- [ ] Console shows "Reverse SSO Handler Loaded"
- [ ] Console shows "Reverse SSO: Found URL in storage"
- [ ] Console shows "Reverse SSO: iframe appended to body"
- [ ] Console shows "Reverse SSO: Main script login completed"
- [ ] Account logs show "Reverse SSO Initiated"
- [ ] Account logs show "Token verified successfully"
- [ ] Main logs show "Reverse SSO Login Successful"
- [ ] Refresh main script → Logged in! ✅

---

## 🎉 **Complete SSO System**

You now have a **full bidirectional SSO system**:

1. ✅ **Login to main** → Auto-login to account
2. ✅ **Logout from main** → Auto-logout from account
3. ✅ **Signup in account** → Auto-login to main

**One authentication system for both applications!** 🚀

---

**Questions? Check the logs - they tell you everything!** 📝
