# Production Deployment Guide - AI Image Generation Fix

## Issue on Production Server
Images are being generated but returning 404 errors. This is likely due to:
1. ❌ Directory permissions
2. ❌ Web server can't access the files
3. ❌ Files not actually being created

## Solution - Step by Step

### Step 1: SSH into Your Plesk VPS Server
```bash
ssh your-username@dewdropskin.com
# Or use Plesk SSH terminal
```

### Step 2: Navigate to Project Directory
```bash
cd /var/www/vhosts/dewdropskin.com/httpdocs
```

### Step 3: Pull Latest Code
```bash
git pull origin main
```

### Step 4: Create AI Images Directory
```bash
mkdir -p public/uploads/products/ai
mkdir -p public/uploads/products/ai/2025/10
```

### Step 5: Set Proper Permissions

#### Find Your Web Server User
```bash
# Check what user runs PHP/web server
ps aux | grep -E 'apache|httpd|nginx|php-fpm' | head -1

# Common users:
# - www-data (Ubuntu/Debian)
# - apache (CentOS/RHEL)
# - nginx (Nginx)
# - Your Plesk username
```

#### Set Ownership (Choose ONE based on your server)
```bash
# Option A: If using www-data (most common on Ubuntu/Debian)
sudo chown -R www-data:www-data public/uploads

# Option B: If using apache (CentOS/RHEL)
sudo chown -R apache:apache public/uploads

# Option C: If using nginx
sudo chown -R nginx:nginx public/uploads

# Option D: If using Plesk user (replace 'username' with your actual username)
sudo chown -R username:psacln public/uploads
```

#### Set Directory and File Permissions
```bash
# Directories: 755 (rwxr-xr-x)
chmod -R 755 public/uploads/products/ai

# Files: 644 (rw-r--r--)
find public/uploads/products/ai -type f -exec chmod 644 {} \;

# Make sure storage is writable
chmod -R 775 storage bootstrap/cache
```

### Step 6: Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Step 7: Create Database Table (If Not Already Done)
```bash
# Check if table exists
mysql -u your_db_user -p dds_marketplace -e "SHOW TABLES LIKE 'ai_generated_images';"

# If it doesn't exist, create it
mysql -u your_db_user -p dds_marketplace < database/ai_generated_images_table.sql
```

### Step 8: Test AI Image Generation
1. Go to Admin Panel → Products → Add Product
2. Click "Use AI" button
3. Upload product images
4. Click "Generate with AI"
5. Watch browser console for errors
6. Check if images load

### Step 9: Check Logs If Issues Persist
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Apache error log (if using Apache)
tail -f /var/log/apache2/error.log
# Or on CentOS/RHEL:
tail -f /var/log/httpd/error_log

# Nginx error log (if using Nginx)
tail -f /var/log/nginx/error.log

# Plesk error logs
tail -f /var/www/vhosts/dewdropskin.com/logs/error_log
```

## Quick Deployment Script

I've created a deployment script. Upload it and run:

```bash
# Upload deploy-ai-fix.sh to your server, then:
chmod +x deploy-ai-fix.sh
./deploy-ai-fix.sh
```

## Verification Commands

### Check if directory exists and has correct permissions
```bash
ls -la public/uploads/products/ai/
```

Expected output:
```
drwxr-xr-x 3 www-data www-data 4096 Oct 11 06:00 .
drwxr-xr-x 5 www-data www-data 4096 Oct 11 06:00 ..
drwxr-xr-x 2 www-data www-data 4096 Oct 11 06:00 2025
```

### Check if images are being created
```bash
# After testing AI generation
ls -lh public/uploads/products/ai/2025/10/
```

### Check Laravel logs for errors
```bash
tail -50 storage/logs/laravel.log
```

### Test file creation manually
```bash
# Try creating a test file
touch public/uploads/products/ai/test.txt
ls -l public/uploads/products/ai/test.txt

# Try accessing it via browser
# https://dewdropskin.com/uploads/products/ai/test.txt

# Clean up
rm public/uploads/products/ai/test.txt
```

## Common Issues and Solutions

### Issue 1: Permission Denied
**Error:** `Failed to create directory` or `Failed to write image file`

**Solution:**
```bash
# Make sure web server user owns the directory
sudo chown -R www-data:www-data public/uploads
chmod -R 755 public/uploads
```

### Issue 2: 404 Not Found
**Possible causes:**
1. Files not actually created
2. Web server can't access the files
3. .htaccess blocking access

**Solution:**
```bash
# Check if .htaccess exists in uploads
cat public/uploads/.htaccess

# It should allow access, not deny
# If it denies, remove or update it
```

### Issue 3: SELinux Blocking (CentOS/RHEL)
**Solution:**
```bash
# Check if SELinux is enabled
getenforce

# If enabled, set proper context
sudo chcon -R -t httpd_sys_rw_content_t public/uploads
```

### Issue 4: Disk Space Full
**Solution:**
```bash
# Check disk space
df -h

# Clean up old files if needed
find public/uploads/products/ai -type f -mtime +30 -delete
```

## What Changed in Code

### GeminiImageService.php
- ✅ Added better error handling
- ✅ Added explicit permission setting (chmod 755 for dirs, 644 for files)
- ✅ Added detailed logging for debugging
- ✅ Verifies directory creation success
- ✅ Verifies file write success

### Benefits
- Better error messages in logs
- Automatic permission setting
- Easier to debug issues
- Works on any Linux server configuration

## After Deployment

### Success Indicators
- ✅ No errors in browser console
- ✅ Images load in preview
- ✅ Images visible in AI Images Gallery
- ✅ Files exist in `public/uploads/products/ai/`
- ✅ No errors in Laravel logs

### If Still Not Working
1. Share the output of:
   ```bash
   ls -la public/uploads/products/ai/
   tail -50 storage/logs/laravel.log
   ps aux | grep -E 'apache|httpd|nginx|php-fpm' | head -1
   ```

2. Check browser console errors
3. Check web server error logs

## Support
If issues persist after following this guide, provide:
- Laravel log output
- Web server error log output
- Directory permissions output
- Browser console errors
