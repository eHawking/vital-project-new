/** @type {import('next').NextConfig} */
const nextConfig = {
  // Enable React strict mode for better development
  reactStrictMode: true,
  
  // Optimize images from external domains
  images: {
    domains: ['localhost', 'your-api-domain.com'],
    formats: ['image/avif', 'image/webp'],
    minimumCacheTTL: 60 * 60 * 24 * 30, // 30 days
  },
  
  // Enable experimental features for maximum performance
  experimental: {
    optimizeCss: true,
  },
  
  // Compress responses
  compress: true,
  
  // Power by header (remove for security)
  poweredByHeader: false,
  
  // Generate ETags for caching
  generateEtags: true,
  
  // Enable SWC minifier (faster than Terser)
  swcMinify: true,
  
  // Optimize fonts
  optimizeFonts: true,
  
  // Production source maps
  productionBrowserSourceMaps: false,
  
  // Compiler options
  compiler: {
    // Remove console.log in production
    removeConsole: process.env.NODE_ENV === 'production',
  },
  
  // Headers for security and caching
  async headers() {
    return [
      {
        source: '/:all*(svg|jpg|png|webp|avif)',
        headers: [
          {
            key: 'Cache-Control',
            value: 'public, max-age=31536000, immutable',
          },
        ],
      },
      {
        source: '/:path*',
        headers: [
          {
            key: 'X-DNS-Prefetch-Control',
            value: 'on',
          },
          {
            key: 'X-Frame-Options',
            value: 'SAMEORIGIN',
          },
          {
            key: 'X-Content-Type-Options',
            value: 'nosniff',
          },
        ],
      },
    ];
  },
};

module.exports = nextConfig;
