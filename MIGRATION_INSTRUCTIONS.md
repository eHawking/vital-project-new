# Migration Instructions - Admin Referral Settings

## Issue
The `admin_refer_settings` table doesn't exist on the production server yet.

## Solution
You need to run the migration on your production server.

---

## Option 1: Run Migration via SSH (Recommended)

### Step 1: Connect to your server via SSH
```bash
ssh username@dewdropskin.com
```

### Step 2: Navigate to your project directory
```bash
cd /var/www/vhosts/dewdropskin.com/httpdocs
```

### Step 3: Run the migration
```bash
php artisan migrate
```

### Step 4: Clear cache (optional but recommended)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Option 2: Run Migration via Plesk/cPanel

### If you have Terminal access in Plesk:
1. Login to **Plesk Control Panel**
2. Go to **Websites & Domains**
3. Click on **PHP Settings** or **Terminal**
4. Open Terminal
5. Navigate to project: `cd httpdocs`
6. Run: `php artisan migrate`

### If you don't have Terminal access:
You'll need to manually create the table using phpMyAdmin.

---

## Option 3: Manual Database Creation (If no SSH access)

### Step 1: Login to phpMyAdmin
- Go to your hosting control panel
- Open phpMyAdmin
- Select database: `dds_marketplace_`

### Step 2: Run this SQL query:

```sql
-- Create admin_refer_settings table
CREATE TABLE `admin_refer_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_refer_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default values
INSERT INTO `admin_refer_settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('default_referral_username', NULL, NOW(), NOW()),
('default_referral_position', NULL, NOW(), NOW()),
('enable_default_referral', '0', NOW(), NOW());
```

### Step 3: Verify the table was created
```sql
SELECT * FROM admin_refer_settings;
```

You should see 3 rows.

---

## Verification

After running the migration, test by:

1. **Go to product details page** - Should load without error
2. **Click "Buy Now"** (while logged out) - Modal should appear
3. **Go to Admin Panel** → Business Settings → Referral Settings - Should load the settings page

---

## Notes

- ✅ The code has been updated to gracefully handle the missing table (won't crash if table doesn't exist)
- ✅ The feature will be disabled until you run the migration
- ✅ Once migration is run, you can configure the default referral in admin panel

---

## Need Help?

If you encounter issues:
1. Check Laravel logs: `/storage/logs/laravel.log`
2. Check PHP version: `php -v` (should be 8.1+)
3. Check database connection in `.env` file
4. Ensure database user has CREATE TABLE permissions

---

## Current Status

**Before Migration:**
- ❌ Table doesn't exist
- ✅ Code won't crash (graceful fallback)
- ⚠️ Default referral feature disabled

**After Migration:**
- ✅ Table exists
- ✅ Admin can configure default referral
- ✅ Full feature enabled
