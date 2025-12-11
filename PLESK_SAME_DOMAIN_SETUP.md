# Plesk Setup: Laravel + Next.js on Same Domain

Run both Laravel backend and Next.js frontend on **one domain**.

## 📋 URL Structure

```
yourdomain.com/              → Next.js Frontend (Shop)
yourdomain.com/api/*         → Laravel API
yourdomain.com/admin/*       → Laravel Admin Panel
yourdomain.com/seller/*      → Laravel Seller Panel
yourdomain.com/storage/*     → Laravel Storage/Uploads
```

---

## 🔧 Step-by-Step Setup

### Step 1: File Structure on Server

```
/var/www/vhosts/yourdomain.com/
├── httpdocs/                    # Laravel application
│   ├── app/
│   ├── public/
│   │   └── index.php
│   ├── routes/
│   ├── storage/
│   └── ...
│
├── frontend/                    # Next.js application (NEW)
│   ├── .next/
│   ├── node_modules/
│   ├── public/
│   ├── src/
│   ├── server.js
│   ├── package.json
│   └── next.config.js
```

### Step 2: Upload Next.js Files

1. Create `frontend` folder in your domain root (same level as httpdocs)
2. Upload all files from `frontend-theme/` to this folder
3. Via SSH or Plesk Terminal:
   ```bash
   cd /var/www/vhosts/yourdomain.com/frontend
   npm install
   npm run build
   ```

### Step 3: Enable Node.js in Plesk

1. Go to **Plesk → Websites & Domains → yourdomain.com**
2. Click **Node.js**
3. Configure:
   - **Enable Node.js**: Yes
   - **Node.js Version**: 18.x or 20.x
   - **Document Root**: Leave as httpdocs
   - **Application Mode**: Production
   - **Application Root**: `/frontend` (change from httpdocs)
   - **Application Startup File**: `server.js`

4. Click **Enable Node.js**

### Step 4: Configure Nginx (Critical Step)

Go to **Plesk → Websites & Domains → yourdomain.com → Apache & nginx Settings**

Scroll to **Additional nginx directives** and add:

```nginx
# ============================================
# NEXT.JS + LARAVEL ON SAME DOMAIN
# ============================================

# Laravel API routes
location /api {
    try_files $uri $uri/ /index.php?$query_string;
}

# Laravel Admin Panel
location /admin {
    try_files $uri $uri/ /index.php?$query_string;
}

# Laravel Seller Panel
location /seller {
    try_files $uri $uri/ /index.php?$query_string;
}

# Laravel Vendor Assets
location /vendor {
    try_files $uri $uri/ =404;
}

# Laravel Storage (uploaded files)
location /storage {
    try_files $uri $uri/ =404;
}

# Laravel Assets (css, js from public folder)
location /assets {
    try_files $uri $uri/ =404;
}

# Laravel Login/Auth Routes (if needed)
location ~ ^/(login|register|logout|password|verify-email) {
    try_files $uri $uri/ /index.php?$query_string;
}

# Laravel Sanctum CSRF
location /sanctum {
    try_files $uri $uri/ /index.php?$query_string;
}

# ============================================
# NEXT.JS FRONTEND (Everything else)
# ============================================

# Next.js Static Files (high priority caching)
location /_next/static {
    proxy_pass http://127.0.0.1:3000;
    proxy_cache_valid 60m;
    add_header Cache-Control "public, immutable, max-age=31536000";
    proxy_http_version 1.1;
    proxy_set_header Host $host;
}

# Next.js Image Optimization
location /_next/image {
    proxy_pass http://127.0.0.1:3000;
    proxy_http_version 1.1;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}

# Next.js API Routes (if any)
location /_next {
    proxy_pass http://127.0.0.1:3000;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}

# Main Site - Proxy to Next.js
# This must be LAST to act as fallback
location / {
    # First check if file exists in Laravel public folder
    # This allows Laravel's public assets to be served
    try_files $uri @nextjs;
}

location @nextjs {
    proxy_pass http://127.0.0.1:3000;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_cache_bypass $http_upgrade;
    proxy_read_timeout 60s;
    proxy_send_timeout 60s;
}
```

Click **OK** to save.

### Step 5: Start Next.js Application

**Option A: Using Plesk Node.js Panel**
1. Go to Node.js settings
2. Click **NPM Install**
3. Click **Run Script** → select `build`
4. Click **Restart App**

**Option B: Using SSH (Recommended)**
```bash
cd /var/www/vhosts/yourdomain.com/frontend
npm install
npm run build

# Start with PM2 for stability
npm install -g pm2
pm2 start ecosystem.config.js --env production
pm2 save
pm2 startup
```

### Step 6: Configure Environment Variables

In Plesk Node.js panel, add these environment variables:

| Variable | Value |
|----------|-------|
| `NODE_ENV` | `production` |
| `PORT` | `3000` |
| `NEXT_PUBLIC_API_URL` | `https://yourdomain.com/api` |
| `NEXT_PUBLIC_SITE_URL` | `https://yourdomain.com` |

Or create `.env.local` in frontend folder:
```env
NODE_ENV=production
PORT=3000
NEXT_PUBLIC_API_URL=https://yourdomain.com/api
NEXT_PUBLIC_SITE_URL=https://yourdomain.com
```

---

## 🔗 Laravel Configuration

### Update CORS (config/cors.php)

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://yourdomain.com',
        'http://localhost:3000', // for local development
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### Update .env (if using Sanctum)

```env
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,localhost:3000
SESSION_DOMAIN=.yourdomain.com
```

---

## 📊 URL Routing Summary

| URL Pattern | Handled By | Purpose |
|-------------|------------|---------|
| `/` | Next.js | Homepage |
| `/shop` | Next.js | Products listing |
| `/product/*` | Next.js | Product details |
| `/cart` | Next.js | Shopping cart |
| `/checkout` | Next.js | Checkout |
| `/api/*` | Laravel | REST API |
| `/admin/*` | Laravel | Admin panel |
| `/seller/*` | Laravel | Seller dashboard |
| `/storage/*` | Laravel | Uploaded files |
| `/login` | Laravel | Authentication |

---

## 🔄 Development Workflow

### Local Development

Run both simultaneously:

**Terminal 1 - Laravel:**
```bash
cd account/core
php artisan serve --port=8000
```

**Terminal 2 - Next.js:**
```bash
cd frontend-theme
npm run dev
```

Update `frontend-theme/.env.local`:
```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

---

## 🛠️ Troubleshooting

### Next.js Not Loading (502 Error)
```bash
# Check if Node.js is running
pm2 status

# Restart
pm2 restart all

# Check logs
pm2 logs
```

### Laravel Routes Not Working
1. Check nginx config syntax
2. Ensure PHP is processing in httpdocs
3. Clear Laravel cache:
   ```bash
   php artisan config:clear
   php artisan route:clear
   ```

### Mixed Content / CORS Errors
1. Ensure both use HTTPS
2. Check Laravel CORS config
3. Verify `NEXT_PUBLIC_API_URL` uses https

### Static Files Not Loading
1. Check `/_next/static` proxy is working
2. Verify build completed: `.next/` folder exists
3. Check nginx error logs

---

## 🚀 Deployment Commands

### Initial Deploy
```bash
# SSH into server
cd /var/www/vhosts/yourdomain.com/frontend

# Install and build
npm install
npm run build

# Start with PM2
pm2 start ecosystem.config.js --env production
pm2 save
```

### Update Deploy
```bash
cd /var/www/vhosts/yourdomain.com/frontend
git pull origin main
npm install
npm run build
pm2 restart all
```

---

## ✅ Checklist

- [ ] Upload Next.js files to `/frontend` folder
- [ ] Install dependencies: `npm install`
- [ ] Build: `npm run build`
- [ ] Enable Node.js in Plesk
- [ ] Configure nginx directives
- [ ] Set environment variables
- [ ] Start application with PM2
- [ ] Test all routes work correctly
- [ ] Enable SSL/HTTPS
- [ ] Configure Laravel CORS
