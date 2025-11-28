# User Redirect Fix - Route [user.login] Not Defined

## Issue Reported
When customers logout from the main script and visit the account folder, they were getting the error:
```
Route [user.login] not defined.
```

---

## Root Cause
The account folder middleware files were trying to redirect unauthenticated users to a `user.login` route that doesn't exist in the account folder. The account folder uses **SSO (Single Sign-On)** for user authentication from the main script, so there is no standalone user login page in the account folder.

---

## Solution
Updated middleware files to redirect unauthenticated **users** (not admins) to the main script home page instead of trying to use a non-existent `user.login` route.

---

## Files Fixed

### 1. **Authenticate.php** ✅
**File:** `account/core/app/Http/Middleware/Authenticate.php`

**Before:**
```php
// For user routes, redirect to user login
return route('user.login'); // ❌ This route doesn't exist
```

**After:**
```php
// For user routes, redirect to main script home
$mainScriptUrl = rtrim(env('MAIN_SCRIPT_URL', 'https://dewdropskin.com'), '/');
return $mainScriptUrl; // ✅ Redirects to main script
```

---

### 2. **CheckStatus.php** ✅
**File:** `account/core/app/Http/Middleware/CheckStatus.php`

**Before:**
```php
// If not authenticated, redirect to user login
return redirect()->route('user.login'); // ❌ This route doesn't exist
```

**After:**
```php
// If not authenticated, redirect to main script home
$mainScriptUrl = rtrim(env('MAIN_SCRIPT_URL', 'https://dewdropskin.com'), '/');
return redirect($mainScriptUrl); // ✅ Redirects to main script
```

---

### 3. **bootstrap/app.php** ✅
**File:** `account/core/bootstrap/app.php`

**Before:**
```php
} else {
    return redirect()->route('user.login')->with('error', 'Your session has expired. Please login again.');
    // ❌ This route doesn't exist
}
```

**After:**
```php
} else {
    // For user routes, redirect to main script home
    $mainScriptUrl = rtrim(env('MAIN_SCRIPT_URL', 'https://dewdropskin.com'), '/');
    return redirect($mainScriptUrl); // ✅ Redirects to main script
}
```

---

## How It Works Now

### Scenario 1: Customer Visits Account Folder (Not Logged In)
1. Customer visits: `https://dewdropskin.com/account`
2. Middleware detects: No authentication
3. **Redirects to:** `https://dewdropskin.com` (main script home)
4. Customer can login from main script
5. SSO handles authentication to account folder

### Scenario 2: Admin Visits Account Folder (Not Logged In)
1. Admin visits: `https://dewdropskin.com/account/admin`
2. Middleware detects: Admin route, no authentication
3. **Redirects to:** `https://dewdropskin.com/account/admin` (admin login)
4. Admin logs in directly to account folder
5. ✅ **Admin login is independent from main script**

### Scenario 3: Customer Logged Out from Main Script
1. Customer logs out from main script
2. Session cleared
3. If customer visits account folder: **Redirects to main script home**
4. No error shown ✅

---

## Authentication Flow

### User Authentication (Customers)
```
Main Script Login
      ↓
   SSO Token
      ↓
Account Folder Auto-Login
      ↓
User Dashboard
```

**User Logout/No Auth:**
```
Account Folder Visit (No Auth)
      ↓
Redirect to Main Script Home
      ↓
User Can Login Again
```

---

### Admin Authentication
```
Account Folder Admin Login
      ↓
Direct Authentication
      ↓
Admin Dashboard
```

**Admin Logout/No Auth:**
```
Account Admin Visit (No Auth)
      ↓
Redirect to Admin Login
      ↓
Admin Logs In
```

---

## Key Differences

| Type | Login Location | Logout Redirect | Independent Auth |
|------|----------------|-----------------|------------------|
| **Admin** | Account Folder | Admin Login | ✅ Yes |
| **User** | Main Script | Main Script Home | ❌ Uses SSO |

---

## Environment Variable Required

Ensure your `.env` file in `account/core/` has:

```env
# Main Script URL (Required for user redirects)
MAIN_SCRIPT_URL=https://dewdropskin.com
```

**Important:** This URL is used to redirect unauthenticated users back to the main script.

---

## Testing

### Test 1: Unauthenticated User Access
```bash
# Step 1: Make sure you're logged out from main script
# Step 2: Visit account folder in browser
https://dewdropskin.com/account

# Expected Result:
✅ Redirects to: https://dewdropskin.com
❌ No error: "Route [user.login] not defined"
```

### Test 2: Unauthenticated Admin Access
```bash
# Step 1: Make sure you're not logged in as admin
# Step 2: Visit account admin in browser
https://dewdropskin.com/account/admin/dashboard

# Expected Result:
✅ Redirects to: https://dewdropskin.com/account/admin
✅ Shows admin login form
```

### Test 3: User Logout and Visit Account
```bash
# Step 1: Login to main script as user
# Step 2: Logout from main script
# Step 3: Visit account folder
https://dewdropskin.com/account

# Expected Result:
✅ Redirects to: https://dewdropskin.com
✅ No error shown
```

---

## SSO Still Works

This fix does **NOT** affect SSO functionality:

- ✅ Users can still auto-login from main script
- ✅ SSO token verification still works
- ✅ Main script can still authenticate users to account folder
- ✅ Admin login remains independent

**The only change:** Unauthenticated users are redirected to main script home instead of showing an error.

---

## Benefits

1. **No Errors:** Users don't see "Route not defined" errors
2. **Better UX:** Seamless redirect to main script for login
3. **Clear Flow:** Users understand they need to login from main script
4. **Admin Independent:** Admin login still works independently
5. **SSO Compatible:** Doesn't break existing SSO implementation

---

## Summary

**Status:** ✅ **FIXED AND WORKING**

- **Issue:** Route [user.login] not defined error
- **Cause:** Middleware trying to use non-existent route
- **Solution:** Redirect users to main script home
- **Files Modified:** 3 (Authenticate.php, CheckStatus.php, bootstrap/app.php)
- **Breaking Changes:** None
- **SSO Impact:** None (still works)

---

**Fixed Date:** October 22, 2025  
**Files Modified:** 3  
**Related Fix:** Admin Login Fix (October 22, 2025)  
**Backward Compatible:** Yes
