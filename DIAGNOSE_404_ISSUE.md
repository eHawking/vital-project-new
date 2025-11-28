# Diagnose AI Image 404 Issue

## The Problem
Images are still getting 404 errors after deployment. This means files are **not being created** on the server.

## Step 1: Run Diagnostic Test

After deploying the latest code, visit this URL in your browser:

```
https://dewdropskin.com/admin/products/ai/test-file-creation
```

This will test:
- ✅ Public path exists
- ✅ Uploads directory exists and is writable
- ✅ AI directory exists and is writable
- ✅ Can create test directory
- ✅ Can create test file
- ✅ PHP user running the process
- ✅ Directory permissions

**Copy the entire JSON response and share it.**

---

## Step 2: Check Laravel Logs

After trying AI image generation, check the logs:

### Via SSH:
```bash
# SSH into server
ssh your-username@dewdropskin.com

# View latest logs
tail -100 /var/www/vhosts/dewdropskin.com/httpdocs/storage/logs/laravel.log
```

### Via Plesk:
1. Go to **Websites & Domains** → **dewdropskin.com**
2. Click **Logs**
3. View **Error Log**

**Look for these log entries:**
- `AI Image Generation Request`
- `Source image exists` or `Source image missing`
- `Upload directory does not exist`
- `Upload directory permissions`
- `Successfully created image` or `Failed to create image`

---

## Step 3: Check Directory Permissions

```bash
# SSH into server
cd /var/www/vhosts/dewdropskin.com/httpdocs

# Check if directory exists
ls -la public/uploads/products/ai/

# If it doesn't exist, create it
mkdir -p public/uploads/products/ai/2025/10

# Set permissions
chmod -R 755 public/uploads/products/ai

# Check ownership
ls -la public/uploads/

# Should show something like:
# drwxr-xr-x 3 username psacln 4096 Oct 11 07:00 ai
```

---

## Step 4: Manual File Creation Test

```bash
# SSH into server
cd /var/www/vhosts/dewdropskin.com/httpdocs

# Try to create a test file
echo "test" > public/uploads/products/ai/test.txt

# Check if it was created
ls -l public/uploads/products/ai/test.txt

# Try to access it via browser
# https://dewdropskin.com/uploads/products/ai/test.txt

# Clean up
rm public/uploads/products/ai/test.txt
```

---

## Common Issues and Solutions

### Issue 1: Directory Doesn't Exist
**Error in logs:** `Upload directory does not exist`

**Solution:**
```bash
mkdir -p public/uploads/products/ai/2025/10
chmod -R 755 public/uploads/products/ai
```

### Issue 2: Permission Denied
**Error in logs:** `Failed to create directory` or `Failed to write image file`

**Solution:**
```bash
# Find web server user
ps aux | grep -E 'apache|httpd|nginx|php-fpm' | head -1

# Set ownership (replace 'www-data' with your web server user)
chown -R www-data:www-data public/uploads

# Set permissions
chmod -R 755 public/uploads/products/ai
chmod -R 775 storage bootstrap/cache
```

### Issue 3: SELinux Blocking (CentOS/RHEL)
**Solution:**
```bash
# Check if SELinux is enabled
getenforce

# If enabled, set proper context
chcon -R -t httpd_sys_rw_content_t public/uploads
```

### Issue 4: Disk Space Full
**Solution:**
```bash
# Check disk space
df -h

# If full, clean up old files
find public/uploads/products/ai -type f -mtime +30 -delete
```

### Issue 5: Wrong Public Path
**Check diagnostic test output for `public_path`**

Should be: `/var/www/vhosts/dewdropskin.com/httpdocs/public`

If different, there's a configuration issue.

---

## What the New Logging Shows

After the latest deployment, when you try AI image generation, check logs for:

### 1. Request Info
```
AI Image Generation Request
- count: 8
- has_source_images: true/false
- source_count: X
- source_age: X seconds
```

### 2. Source Images
```
Source image exists: /path/to/image.jpg
OR
Source image missing: /path/to/image.jpg
```

### 3. Directory Check
```
Upload directory does not exist: /path/to/dir
OR
Upload directory permissions: 0755
```

### 4. Image Creation
```
Successfully created image: uploads/products/ai/2025/10/uuid.png, size: 12345
OR
Failed to create directory: /path/to/dir
OR
Failed to write image file: /path/to/file.png
```

### 5. Result
```
AI Image Generation Result
- thumbnail: https://dewdropskin.com/uploads/...
- additional_count: 7
```

---

## Next Steps

1. **Deploy latest code** (already done via Plesk Git)
2. **Run diagnostic test**: Visit `/admin/products/ai/test-file-creation`
3. **Try AI generation** again
4. **Check Laravel logs** for detailed error messages
5. **Share results** with me

---

## Quick Fix Commands

Run these on your server:

```bash
cd /var/www/vhosts/dewdropskin.com/httpdocs

# Create directories
mkdir -p public/uploads/products/ai/2025/10

# Set permissions
chmod -R 755 public/uploads/products/ai
chmod -R 775 storage bootstrap/cache

# Set ownership (adjust based on your server)
# For www-data:
chown -R www-data:www-data public/uploads

# For Plesk user (replace 'username'):
chown -R username:psacln public/uploads

# Clear caches
php artisan config:clear
php artisan cache:clear
```

---

## Expected Diagnostic Test Output

### ✅ Success (Everything Working):
```json
{
  "status": "success",
  "results": {
    "public_path": "/var/www/vhosts/dewdropskin.com/httpdocs/public",
    "public_path_exists": true,
    "uploads_dir": "/var/www/vhosts/dewdropskin.com/httpdocs/public/uploads",
    "uploads_exists": true,
    "uploads_writable": true,
    "ai_dir": "/var/www/vhosts/dewdropskin.com/httpdocs/public/uploads/products/ai",
    "ai_exists": true,
    "ai_writable": true,
    "test_dir_created": true,
    "test_file_written": true,
    "test_file_bytes": 4,
    "test_file_exists": true,
    "test_file_readable": true,
    "test_file_url": "https://dewdropskin.com/uploads/products/ai/test.txt",
    "php_user": "www-data",
    "ai_dir_perms": "0755"
  }
}
```

### ❌ Failure (Permission Issues):
```json
{
  "status": "success",
  "results": {
    "public_path": "/var/www/vhosts/dewdropskin.com/httpdocs/public",
    "public_path_exists": true,
    "uploads_dir": "/var/www/vhosts/dewdropskin.com/httpdocs/public/uploads",
    "uploads_exists": true,
    "uploads_writable": false,  // ❌ Problem here
    "ai_dir": "/var/www/vhosts/dewdropskin.com/httpdocs/public/uploads/products/ai",
    "ai_exists": false,  // ❌ Directory doesn't exist
    "test_dir_error": "Permission denied",  // ❌ Can't create directory
    "php_user": "www-data"
  }
}
```

---

## After Fixing

Once you've fixed the permissions:

1. Try AI image generation again
2. Images should load without 404 errors
3. Check AI Images Gallery to see generated images
4. Verify files exist in `public/uploads/products/ai/`

**The diagnostic test and detailed logging will pinpoint the exact issue!** 🔍
