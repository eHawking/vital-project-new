# Auto Deployment Setup Guide

## Option 1: GitHub Actions (Recommended)

### Step 1: Generate SSH Key on Your Server
```bash
# SSH into your server
ssh your-username@dewdropskin.com

# Generate SSH key (press Enter for all prompts)
ssh-keygen -t rsa -b 4096 -C "github-deploy"

# View the private key (copy this)
cat ~/.ssh/id_rsa

# Add public key to authorized_keys
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### Step 2: Add Secrets to GitHub

1. Go to your GitHub repository: `https://github.com/eHawking/vital-project`
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add these secrets:

| Secret Name | Value |
|-------------|-------|
| `SERVER_HOST` | `dewdropskin.com` |
| `SERVER_USERNAME` | Your SSH username (e.g., `username` or `root`) |
| `SERVER_SSH_KEY` | Paste the private key from `cat ~/.ssh/id_rsa` |
| `SERVER_PORT` | `22` (or your SSH port) |

### Step 3: Test Auto Deployment

```bash
# On your local machine
git add .
git commit -m "test: auto deployment"
git push origin main
```

GitHub Actions will automatically:
1. ✅ SSH into your server
2. ✅ Pull latest code
3. ✅ Clear caches
4. ✅ Set permissions
5. ✅ Complete deployment

**View deployment status:**
- Go to GitHub → Actions tab
- See real-time deployment logs

---

## Option 2: Plesk Git Integration (Easier)

### Step 1: Enable Git in Plesk

1. Log into **Plesk Control Panel**
2. Go to **Websites & Domains** → **dewdropskin.com**
3. Click **Git** (under "Developer Tools")
4. Click **Enable Git**

### Step 2: Connect to GitHub Repository

1. Click **Add Repository**
2. Enter repository URL: `https://github.com/eHawking/vital-project.git`
3. Select branch: `main`
4. Set deployment path: `/httpdocs`
5. Click **OK**

### Step 3: Enable Auto-Deploy

1. In Git settings, enable **"Deploy automatically"**
2. Add deployment actions:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   chmod -R 755 public/uploads/products/ai
   ```

Now every time you push to GitHub, Plesk will automatically deploy!

---

## Option 3: Webhook (Manual Setup)

### Step 1: Create Deployment Script on Server

```bash
# SSH into server
ssh your-username@dewdropskin.com

# Create deploy script
nano /var/www/vhosts/dewdropskin.com/deploy.php
```

**Paste this code:**
```php
<?php
// Simple deployment webhook
$secret = 'your-secret-key-here'; // Change this!

// Verify secret
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

// Run deployment
$output = shell_exec('cd /var/www/vhosts/dewdropskin.com/httpdocs && git pull origin main 2>&1 && php artisan config:clear && php artisan cache:clear && chmod -R 755 public/uploads/products/ai');

echo "Deployment completed!\n";
echo $output;
```

**Set permissions:**
```bash
chmod 755 /var/www/vhosts/dewdropskin.com/deploy.php
```

### Step 2: Add GitHub Webhook

1. Go to GitHub repository → **Settings** → **Webhooks**
2. Click **Add webhook**
3. Payload URL: `https://dewdropskin.com/deploy.php?secret=your-secret-key-here`
4. Content type: `application/json`
5. Events: Select **Just the push event**
6. Click **Add webhook**

### Step 3: Test

```bash
git add .
git commit -m "test: webhook deployment"
git push origin main
```

GitHub will trigger the webhook and auto-deploy!

---

## Option 4: Simple Cron Job (Periodic Auto-Pull)

### Setup Auto-Pull Every 5 Minutes

```bash
# SSH into server
ssh your-username@dewdropskin.com

# Edit crontab
crontab -e

# Add this line (pulls from GitHub every 5 minutes)
*/5 * * * * cd /var/www/vhosts/dewdropskin.com/httpdocs && git pull origin main && php artisan config:clear && php artisan cache:clear 2>&1 >> /var/log/auto-deploy.log
```

**View deployment logs:**
```bash
tail -f /var/log/auto-deploy.log
```

---

## Comparison

| Method | Difficulty | Speed | Best For |
|--------|-----------|-------|----------|
| **GitHub Actions** | Medium | Instant | Professional setup |
| **Plesk Git** | Easy | Instant | Plesk users (easiest!) |
| **Webhook** | Medium | Instant | Custom control |
| **Cron Job** | Easy | 5 min delay | Simple setup |

---

## Recommended: Plesk Git (Easiest for You!)

Since you're using **Plesk VPS**, the **Plesk Git Integration** is the easiest:

1. ✅ No SSH keys needed
2. ✅ No GitHub secrets needed
3. ✅ Built-in UI
4. ✅ Automatic deployment
5. ✅ Deployment logs in Plesk

**Just enable it in Plesk and you're done!**

---

## Security Tips

### For GitHub Actions:
- ✅ Use SSH keys, not passwords
- ✅ Keep secrets in GitHub Secrets
- ✅ Use read-only deploy keys if possible

### For Webhook:
- ✅ Use strong secret key
- ✅ Verify webhook signatures
- ✅ Limit IP addresses (GitHub IPs only)

### For Cron:
- ✅ Use SSH keys for git pull
- ✅ Run as limited user, not root
- ✅ Monitor logs regularly

---

## Troubleshooting

### GitHub Actions Not Running?
```bash
# Check Actions tab in GitHub
# View logs for errors
# Verify secrets are set correctly
```

### Plesk Git Not Pulling?
```bash
# Check Git logs in Plesk
# Verify repository URL
# Check file permissions
```

### Webhook Not Triggering?
```bash
# Check GitHub webhook delivery logs
# Test webhook URL manually
# Check server error logs
```

---

## After Setup

Every time you push to GitHub:
```bash
git add .
git commit -m "your changes"
git push origin main
```

Your server will automatically:
1. ✅ Pull latest code
2. ✅ Clear caches
3. ✅ Set permissions
4. ✅ Deploy changes

**No manual SSH needed!** 🎉
