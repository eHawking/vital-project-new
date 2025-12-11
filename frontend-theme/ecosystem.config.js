// PM2 Configuration for Next.js on Plesk
// Usage: pm2 start ecosystem.config.js

module.exports = {
  apps: [
    {
      name: 'nextjs-premium-theme',
      script: 'node_modules/next/dist/bin/next',
      args: 'start',
      cwd: './',
      instances: 'max', // Use all CPU cores
      exec_mode: 'cluster',
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'development',
        PORT: 3000,
      },
      env_production: {
        NODE_ENV: 'production',
        PORT: 3000,
      },
      // Logging
      log_file: './logs/combined.log',
      out_file: './logs/out.log',
      error_file: './logs/error.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
      // Performance
      node_args: '--max-old-space-size=2048',
    },
  ],

  // Deployment configuration (optional)
  deploy: {
    production: {
      user: 'your-ssh-user',
      host: 'your-server.com',
      ref: 'origin/main',
      repo: 'git@github.com:your/repo.git',
      path: '/var/www/vhosts/yourdomain.com/shop.yourdomain.com',
      'pre-deploy-local': '',
      'post-deploy': 'npm install && npm run build && pm2 reload ecosystem.config.js --env production',
      'pre-setup': '',
    },
  },
};
