# Plesk Git Auto-Deployment Setup Guide

## Step-by-Step Instructions

### Step 1: Access Plesk Git Settings

1. **Log into Plesk Control Panel**
   - URL: Usually `https://dewdropskin.com:8443` or your Plesk URL
   - Login with your credentials

2. **Navigate to Your Domain**
   - Click **"Websites & Domains"** in the left sidebar
   - Find **"dewdropskin.com"**

3. **Open Git Settings**
   - Scroll down to **"Developer Tools"** section
   - Click **"Git"**

---

### Step 2: Add Repository

1. **Click "Add Repository"** button

2. **Fill in Repository Details:**
   - **Repository Name:** `vital-project` (or any name you like)
   - **Repository URL:** `https://github.com/eHawking/vital-project.git`
   - **Repository Branch:** `main`
   - **Repository Path:** `/httpdocs` (or leave empty for root)

3. **Authentication (if private repo):**
   - If your repo is private, add GitHub credentials
   - Or use Personal Access Token from GitHub

4. **Click "OK"** to save

---

### Step 3: Configure Deployment Settings

1. **Enable Automatic Deployment**
   - Check the box: ✅ **"Deploy automatically"**
   - This will deploy on every git push

2. **Add Deploy Actions**
   - Click **"Deploy Actions"** or **"Additional Deployment Actions"**
   - Paste these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
mkdir -p public/uploads/products/ai/2025/10
chmod -R 755 public/uploads/products/ai
chmod -R 775 storage bootstrap/cache
echo "✅ Deployment completed at $(date)" >> storage/logs/deployment.log
```

3. **Click "OK"** to save

---

### Step 4: Initial Deployment

1. **Click "Pull Updates"** button to do first deployment
2. Watch the deployment log for any errors
3. Verify deployment was successful

---

### Step 5: Test Auto-Deployment

1. **On your local machine, make a small change:**
   ```bash
   # Edit any file or create a test file
   echo "test" > test.txt
   
   # Commit and push
   git add .
   git commit -m "test: auto deployment"
   git push origin main
   ```

2. **Check Plesk Git Dashboard**
   - Should show "Deploying..." status
   - Then "Deployed successfully"

3. **View Deployment Logs**
   - Click on the repository name
   - View deployment history and logs

---

## Deploy Actions Explained

### Essential Commands (Always Include)

```bash
# Clear all caches - REQUIRED after every deployment
php artisan config:clear    # Clear configuration cache
php artisan cache:clear     # Clear application cache
php artisan view:clear      # Clear compiled views
php artisan route:clear     # Clear route cache
```

### AI Image Fix Commands

```bash
# Create AI images directory
mkdir -p public/uploads/products/ai/2025/10

# Set permissions for AI images
chmod -R 755 public/uploads/products/ai

# Set permissions for Laravel storage
chmod -R 775 storage bootstrap/cache
```

### Optional Commands (Uncomment if needed)

```bash
# Run database migrations
# php artisan migrate --force

# Install/update Composer dependencies
# composer install --no-dev --optimize-autoloader

# Install/update NPM dependencies
# npm install && npm run build

# Optimize for production (cache everything)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Restart queue workers
# php artisan queue:restart
```

---

## Troubleshooting

### Issue 1: "Permission Denied" Errors

**Solution:**
```bash
# SSH into server and run:
cd /var/www/vhosts/dewdropskin.com/httpdocs
chmod -R 755 public/uploads
chmod -R 775 storage bootstrap/cache
```

### Issue 2: "Command Not Found" (php artisan)

**Solution:**
- Make sure you're in the correct directory
- Update deploy actions to include `cd` command:
```bash
cd /var/www/vhosts/dewdropskin.com/httpdocs
php artisan config:clear
# ... rest of commands
```

### Issue 3: Git Pull Fails

**Solution:**
- Check repository URL is correct
- Verify authentication (if private repo)
- Make sure there are no local changes on server:
```bash
# SSH into server
cd /var/www/vhosts/dewdropskin.com/httpdocs
git status
git reset --hard origin/main
```

### Issue 4: Deployment Stuck

**Solution:**
- Check Plesk error logs
- Try manual pull: Click "Pull Updates" button
- Check server disk space: `df -h`

---

## Viewing Deployment Logs

### In Plesk:
1. Go to Git settings
2. Click on repository name
3. View "Deployment History"
4. Click on any deployment to see logs

### Via SSH:
```bash
# View Laravel logs
tail -f /var/www/vhosts/dewdropskin.com/httpdocs/storage/logs/laravel.log

# View deployment log (if you added the echo command)
tail -f /var/www/vhosts/dewdropskin.com/httpdocs/storage/logs/deployment.log

# View Plesk error logs
tail -f /var/www/vhosts/dewdropskin.com/logs/error_log
```

---

## Best Practices

### 1. Always Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 2. Set Correct Permissions
```bash
chmod -R 755 public/uploads
chmod -R 775 storage bootstrap/cache
```

### 3. Test Before Production
- Test changes locally first
- Use staging environment if available
- Check logs after deployment

### 4. Keep Deploy Actions Simple
- Only include necessary commands
- Don't run heavy operations (like migrations) automatically
- Log important actions

### 5. Monitor Deployments
- Check deployment logs regularly
- Set up error notifications if available
- Keep deployment history

---

## Complete Deploy Actions Template

### Minimal (Recommended):
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
chmod -R 755 public/uploads/products/ai
chmod -R 775 storage bootstrap/cache
```

### Standard (With AI Fix):
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
mkdir -p public/uploads/products/ai/2025/10
chmod -R 755 public/uploads/products/ai
chmod -R 775 storage bootstrap/cache
echo "✅ Deployment completed at $(date)" >> storage/logs/deployment.log
```

### Full (With Optimization):
```bash
cd /var/www/vhosts/dewdropskin.com/httpdocs
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
mkdir -p public/uploads/products/ai/2025/10
chmod -R 755 public/uploads/products/ai
chmod -R 775 storage bootstrap/cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Deployment completed at $(date)" >> storage/logs/deployment.log
```

---

## After Setup

### Every time you push code:
```bash
git add .
git commit -m "your changes"
git push origin main
```

### Plesk will automatically:
1. ✅ Pull latest code from GitHub
2. ✅ Run all deploy actions
3. ✅ Clear caches
4. ✅ Set permissions
5. ✅ Log deployment

### You can monitor:
- Plesk Git dashboard for status
- Deployment logs for details
- Laravel logs for errors

---

## Success Indicators

After deployment, verify:
- ✅ Website loads correctly
- ✅ No errors in browser console
- ✅ AI image generation works
- ✅ All features functional
- ✅ Deployment log shows success

---

## Need Help?

If deployment fails:
1. Check Plesk deployment logs
2. Check Laravel error logs
3. SSH into server and run commands manually
4. Verify file permissions
5. Check disk space

**You're all set for automatic deployments!** 🚀
