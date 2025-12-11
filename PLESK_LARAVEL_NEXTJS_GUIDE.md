# Plesk Deployment Guide: Laravel + Next.js Frontend

This guide explains how to deploy and manage both your Laravel backend and Next.js frontend on Plesk.

## 📋 Architecture Options

### Option 1: Subdomain Setup (Recommended)
```
Main Domain:     yourdomain.com        → Laravel (PHP)
Subdomain:       shop.yourdomain.com   → Next.js Frontend
API Endpoint:    yourdomain.com/api    → Laravel API
```

### Option 2: Subfolder Setup
```
Main Domain:     yourdomain.com        → Next.js Frontend
API Path:        yourdomain.com/api    → Laravel API (reverse proxy)
Admin Path:      yourdomain.com/admin  → Laravel Admin
```

### Option 3: Separate Domains
```
Domain 1:        api.yourdomain.com    → Laravel Backend
Domain 2:        yourdomain.com        → Next.js Frontend
```

---

## 🚀 Option 1: Subdomain Setup (Recommended)

### Step 1: Create Subdomain for Next.js

1. **In Plesk → Websites & Domains**
2. Click **"Add Subdomain"**
3. Enter subdomain name: `shop` (or `store`, `www`, etc.)
4. Set document root: `shop.yourdomain.com`
5. Click **OK**

### Step 2: Enable Node.js for Subdomain

1. Go to **shop.yourdomain.com** in Plesk
2. Click **"Node.js"** under Hosting & DNS
3. Enable Node.js
4. Configure:
   - **Node.js Version**: 18.x or 20.x (LTS)
   - **Document Root**: `/shop.yourdomain.com`
   - **Application Mode**: Production
   - **Application URL**: shop.yourdomain.com
   - **Application Root**: `/shop.yourdomain.com`
   - **Application Startup File**: `server.js` (we'll create this)

### Step 3: Upload Next.js Files

#### Method A: Git Deployment (Recommended)

1. In Plesk → **Git** for subdomain
2. Clone your repository
3. Set path to `/frontend-theme` subfolder

#### Method B: Manual Upload

1. Build locally:
   ```bash
   cd frontend-theme
   npm install
   npm run build
   ```
2. Upload these folders via FTP/File Manager:
   - `.next/`
   - `node_modules/`
   - `public/`
   - `package.json`
   - `next.config.js`

### Step 4: Create Server Startup File

Create `server.js` in your Next.js root:

```javascript
// server.js - Plesk Node.js startup file
const { createServer } = require('http');
const { parse } = require('url');
const next = require('next');

const dev = process.env.NODE_ENV !== 'production';
const hostname = '0.0.0.0';
const port = process.env.PORT || 3000;

const app = next({ dev, hostname, port });
const handle = app.getRequestHandler();

app.prepare().then(() => {
  createServer(async (req, res) => {
    try {
      const parsedUrl = parse(req.url, true);
      await handle(req, res, parsedUrl);
    } catch (err) {
      console.error('Error occurred handling', req.url, err);
      res.statusCode = 500;
      res.end('internal server error');
    }
  })
    .once('error', (err) => {
      console.error(err);
      process.exit(1);
    })
    .listen(port, () => {
      console.log(`> Ready on http://${hostname}:${port}`);
    });
});
```

### Step 5: Configure Environment Variables

In Plesk Node.js settings, add:

```
NODE_ENV=production
NEXT_PUBLIC_API_URL=https://yourdomain.com/api
NEXT_PUBLIC_SITE_URL=https://shop.yourdomain.com
```

### Step 6: Start Application

1. In Plesk → Node.js for subdomain
2. Click **"NPM Install"** to install dependencies
3. Click **"Restart App"**

---

## 🔧 Option 2: Same Domain with Reverse Proxy

If you want Next.js as the main site with Laravel API:

### Step 1: Configure Nginx

In Plesk → **Apache & nginx Settings** for your domain:

Add to **Additional nginx directives**:

```nginx
# Next.js Frontend (main site)
location / {
    proxy_pass http://127.0.0.1:3000;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_cache_bypass $http_upgrade;
}

# Laravel API
location /api {
    alias /var/www/vhosts/yourdomain.com/httpdocs/public;
    try_files $uri $uri/ /index.php?$query_string;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/www/vhosts/system/yourdomain.com/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $request_filename;
        include fastcgi_params;
    }
}

# Laravel Admin Panel
location /admin {
    alias /var/www/vhosts/yourdomain.com/httpdocs/public;
    try_files $uri $uri/ /index.php?$query_string;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/www/vhosts/system/yourdomain.com/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $request_filename;
        include fastcgi_params;
    }
}

# Static files from Next.js
location /_next/static {
    proxy_pass http://127.0.0.1:3000;
    proxy_cache_valid 60m;
    add_header Cache-Control "public, immutable, max-age=31536000";
}
```

---

## 📁 Recommended File Structure on Plesk

```
/var/www/vhosts/yourdomain.com/
├── httpdocs/                    # Laravel (main domain)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/                  # Laravel public folder
│   │   └── index.php
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── composer.json
│
├── shop.yourdomain.com/         # Next.js (subdomain)
│   ├── .next/                   # Built Next.js files
│   ├── node_modules/
│   ├── public/
│   ├── src/
│   ├── server.js               # Plesk startup file
│   ├── package.json
│   └── next.config.js
```

---

## 🔄 Process Management with PM2

For production stability, use PM2:

### Install PM2 Globally

SSH into your server:
```bash
npm install -g pm2
```

### Create PM2 Config

Create `ecosystem.config.js` in Next.js root:

```javascript
module.exports = {
  apps: [{
    name: 'nextjs-frontend',
    script: 'node_modules/next/dist/bin/next',
    args: 'start',
    cwd: '/var/www/vhosts/yourdomain.com/shop.yourdomain.com',
    instances: 'max',
    exec_mode: 'cluster',
    env: {
      NODE_ENV: 'production',
      PORT: 3000
    }
  }]
};
```

### Start with PM2

```bash
cd /var/www/vhosts/yourdomain.com/shop.yourdomain.com
pm2 start ecosystem.config.js
pm2 save
pm2 startup
```

---

## 🔗 API Configuration

### Laravel CORS Setup

In `config/cors.php`:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://shop.yourdomain.com',
        'https://yourdomain.com',
        'http://localhost:3000',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### Next.js API Configuration

Create `.env.local` in Next.js:

```env
NEXT_PUBLIC_API_URL=https://yourdomain.com/api
NEXT_PUBLIC_SITE_URL=https://shop.yourdomain.com
```

Create API service `src/lib/api.ts`:

```typescript
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export default api;
```

---

## 📝 Deployment Checklist

### Laravel Setup
- [ ] Upload Laravel files to `httpdocs/`
- [ ] Configure `.env` with production settings
- [ ] Run `composer install --no-dev`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Set permissions: `chmod -R 755 storage bootstrap/cache`

### Next.js Setup
- [ ] Upload Next.js files to subdomain folder
- [ ] Enable Node.js in Plesk
- [ ] Upload `server.js` startup file
- [ ] Set environment variables
- [ ] Run `npm install`
- [ ] Run `npm run build`
- [ ] Start the application

### SSL/HTTPS
- [ ] Enable Let's Encrypt for main domain
- [ ] Enable Let's Encrypt for subdomain
- [ ] Redirect HTTP to HTTPS

---

## 🔄 Git Auto-Deploy Setup

### For Laravel (Main Domain)

1. Plesk → Git for main domain
2. Repository URL: `https://github.com/your/repo.git`
3. Deploy actions:
   ```bash
   cd httpdocs
   composer install --no-dev
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   ```

### For Next.js (Subdomain)

1. Plesk → Git for subdomain
2. Repository URL: `https://github.com/your/repo.git`
3. Repository path: `frontend-theme`
4. Deploy actions:
   ```bash
   npm install
   npm run build
   # Restart via Plesk Node.js or PM2
   pm2 restart nextjs-frontend
   ```

---

## 🛠️ Troubleshooting

### Node.js Not Starting

1. Check Plesk error logs
2. Verify `server.js` exists and is correct
3. Check port is not in use
4. Verify Node.js version compatibility

### CORS Errors

1. Verify Laravel CORS config includes subdomain
2. Check `withCredentials` in API calls
3. Ensure HTTPS on both domains

### 502 Bad Gateway

1. Next.js app not running
2. Wrong port in nginx proxy
3. PM2 process crashed - restart with `pm2 restart all`

### Slow Performance

1. Enable Next.js production mode
2. Use PM2 cluster mode
3. Enable nginx caching for static files

---

## 📊 Recommended Plesk Extensions

1. **Node.js** - Required for Next.js
2. **Git** - For deployment automation
3. **Let's Encrypt** - Free SSL certificates
4. **Nginx Caching** - Performance boost

---

## 💡 Best Practices

1. **Use subdomains** - Easier to manage, better isolation
2. **Use PM2** - Better process management than Plesk Node.js
3. **Enable caching** - Both nginx and Next.js caching
4. **Use CDN** - For static assets (images, CSS, JS)
5. **Monitor logs** - Check both Laravel and Node.js logs
6. **Backup regularly** - Both code and database

---

## 🔐 Security

1. Keep Node.js and npm updated
2. Use environment variables for secrets
3. Enable HTTPS everywhere
4. Configure proper CORS headers
5. Use Laravel Sanctum for API auth

---

**Questions?** Check Plesk documentation or contact your hosting provider.
