# Git Ignore - Image Folders Update

## Overview
Updated `.gitignore` files to exclude all image and upload directories from version control. This prevents large image files from being committed to the repository and keeps the repository size manageable.

---

## Files Updated

### 1. **Main Script `.gitignore`**
**File:** `.gitignore` (root directory)

### 2. **Account Folder `.gitignore`**
**File:** `account/core/.gitignore`

---

## Main Script - Ignored Directories

### Storage Directories
```
/storage/app/banner/          # Banner images
/storage/app/brand/           # Brand logos
/storage/app/category/        # Category images
/storage/app/company/         # Company images
/storage/app/deal/            # Deal/promotion images
/storage/app/icon/            # Icons
/storage/app/logo/            # Logo files
/storage/app/png/             # PNG files
/storage/app/product/         # Product images
/storage/app/profile/         # User profile images
```

### Public Directories
```
/public/uploads/              # All uploaded files
/public/assets/*/img/*        # Asset images (all themes)
/public/assets/*/images/*     # Asset images (all themes)
/public/images/               # General images
```

---

## Account Folder - Ignored Directories

### Account Assets (in main .gitignore)
```
/account/assets/images/extensions/*       # Extension images
/account/assets/images/firebase/*         # Firebase-related images
/account/assets/images/frontend/*         # Frontend images
/account/assets/images/gateway/*          # Payment gateway images
/account/assets/images/language/*         # Language flags/icons
/account/assets/images/logoIcon/*         # Logo and icon files
/account/assets/images/maintenance/*      # Maintenance mode images
/account/assets/images/seo/*              # SEO images
/account/assets/images/user/*             # User-uploaded images
/account/assets/images/withdraw_method/*  # Withdrawal method images
/account/assets/admin/images/*            # Admin panel images
```

### Account Core Storage (in account/core/.gitignore)
```
/storage/app/public/*                     # Public storage files
!/storage/app/public/.gitignore           # Keep .gitignore file
```

---

## Benefits

### 1. **Repository Size**
- ✅ Significantly reduces repository size
- ✅ Faster clone and pull operations
- ✅ Less storage usage on GitHub

### 2. **Performance**
- ✅ Faster git operations (commit, push, pull)
- ✅ Improved diff and merge performance
- ✅ Better CI/CD pipeline performance

### 3. **Best Practices**
- ✅ Follows Laravel conventions
- ✅ Separates code from content
- ✅ Easier backup and deployment strategies

### 4. **Security**
- ✅ Prevents accidental commit of sensitive images
- ✅ User data not stored in version control
- ✅ Better data privacy compliance

---

## What's Still Tracked

### Default/Template Images
Static images that are part of the theme or design:
- `/account/assets/images/checklist.png`
- `/account/assets/images/default-user.png`
- `/account/assets/images/default.png`
- `/account/assets/images/empty_list.png`
- `/storage/app/def.png`

### Asset Files
Design and UI assets:
- `/public/assets/` (excluding img/ and images/ subdirectories)
- `/account/assets/` (excluding dynamic upload folders)

---

## Deployment Considerations

### Production Setup
When deploying to production, ensure these directories exist:

**Main Script:**
```bash
mkdir -p storage/app/{banner,brand,category,company,deal,icon,logo,png,product,profile}
mkdir -p public/uploads
chmod -R 775 storage/app
chmod -R 775 public/uploads
```

**Account Folder:**
```bash
mkdir -p account/core/storage/app/public
mkdir -p account/assets/images/{extensions,firebase,frontend,gateway,language,logoIcon,maintenance,seo,user,withdraw_method}
mkdir -p account/assets/admin/images
chmod -R 775 account/core/storage/app/public
chmod -R 775 account/assets/images
```

### File Permissions
Ensure web server has write permissions:
```bash
# For Apache/Nginx user (www-data, nginx, etc.)
chown -R www-data:www-data storage/app
chown -R www-data:www-data public/uploads
chown -R www-data:www-data account/core/storage/app/public
chown -R www-data:www-data account/assets/images
```

---

## Backup Strategy

Since images are not in version control, implement a backup strategy:

### Option 1: Separate Image Backup
```bash
# Daily backup of image directories
tar -czf images-backup-$(date +%Y%m%d).tar.gz \
  storage/app/{banner,brand,category,company,deal,icon,logo,png,product,profile} \
  public/uploads \
  account/assets/images \
  account/core/storage/app/public
```

### Option 2: Cloud Storage
- Use AWS S3, Google Cloud Storage, or similar
- Sync images to cloud storage automatically
- Configure Laravel to use cloud storage driver

### Option 3: Dedicated Storage Server
- Mount network storage for image directories
- Separate image storage from application server
- Easier to scale and backup

---

## Migration Instructions

### For Existing Repositories

If you already have image files committed:

1. **Remove cached files:**
   ```bash
   git rm -r --cached storage/app/banner/
   git rm -r --cached storage/app/brand/
   git rm -r --cached storage/app/category/
   git rm -r --cached storage/app/company/
   git rm -r --cached storage/app/deal/
   git rm -r --cached storage/app/icon/
   git rm -r --cached storage/app/logo/
   git rm -r --cached storage/app/png/
   git rm -r --cached storage/app/product/
   git rm -r --cached storage/app/profile/
   git rm -r --cached public/uploads/
   git rm -r --cached account/assets/images/
   git rm -r --cached account/core/storage/app/public/
   ```

2. **Commit the changes:**
   ```bash
   git commit -m "Remove image directories from version control"
   ```

3. **Push to remote:**
   ```bash
   git push origin main
   ```

4. **Optional: Clean repository history**
   ```bash
   # Use git filter-branch or BFG Repo-Cleaner to remove from history
   # WARNING: This rewrites history - coordinate with team first
   ```

---

## Testing

### Verify .gitignore is working:
```bash
# Add a test file to an ignored directory
touch storage/app/product/test-image.jpg

# Check git status - file should not appear
git status

# Clean up test file
rm storage/app/product/test-image.jpg
```

---

## Summary

**Updated:** October 22, 2025  
**Files Modified:** 2 (.gitignore files)  
**Directories Ignored:** 25+ image and upload directories  
**Repository Size Impact:** Significant reduction  
**Breaking Changes:** None

All image and upload directories are now properly excluded from version control while maintaining the necessary structure for the application to function.

---

## Notes

- Default/placeholder images are still tracked in git
- Empty directory structure is preserved via .gitignore files
- This change does not affect existing clones until they pull updates
- Team members should ensure they have proper backups before updating
