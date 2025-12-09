# Premium Fashion Theme - Next.js Frontend

A blazing-fast, premium e-commerce frontend built with Next.js 14, React 18, and TailwindCSS. Designed for maximum performance with Server Components, Static Site Generation, and modern web standards.

## ⚡ Performance Features

- **Next.js 14** with App Router and React Server Components
- **Static Site Generation (SSG)** for instant page loads
- **Automatic Image Optimization** with next/image
- **Code Splitting** and lazy loading
- **Minimal JavaScript** bundle size
- **Edge-ready** deployment

## 🎨 UI Features

- **Dark/Light Theme** with system preference detection
- **Premium Animations** with Framer Motion
- **Responsive Design** mobile-first approach
- **Glassmorphism** and modern design patterns
- **Custom Scrollbars** and smooth scrolling
- **Skeleton Loading** states

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| Next.js 14 | React framework with SSG/SSR |
| React 18 | UI library with Server Components |
| TypeScript | Type safety |
| TailwindCSS | Utility-first CSS |
| Framer Motion | Animations |
| Zustand | State management |
| Lucide React | Icons |
| Axios | HTTP client |

## 📦 Installation

```bash
# Navigate to the frontend folder
cd frontend-theme

# Install dependencies
npm install

# Run development server
npm run dev

# Build for production
npm run build

# Start production server
npm start
```

## 🚀 Quick Start

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Start development server:**
   ```bash
   npm run dev
   ```

3. **Open browser:**
   Navigate to [http://localhost:3000](http://localhost:3000)

## 📁 Project Structure

```
frontend-theme/
├── src/
│   ├── app/                    # Next.js App Router pages
│   │   ├── layout.tsx          # Root layout
│   │   ├── page.tsx            # Home page
│   │   └── globals.css         # Global styles
│   ├── components/
│   │   ├── home/               # Home page sections
│   │   │   ├── hero-section.tsx
│   │   │   ├── categories-section.tsx
│   │   │   ├── featured-products.tsx
│   │   │   ├── brands-section.tsx
│   │   │   └── newsletter-section.tsx
│   │   ├── layout/             # Layout components
│   │   │   ├── header.tsx
│   │   │   └── footer.tsx
│   │   ├── product/            # Product components
│   │   │   └── product-card.tsx
│   │   └── providers.tsx       # Context providers
│   └── store/
│       └── cart.ts             # Cart state management
├── public/
│   └── images/                 # Static images
├── next.config.js              # Next.js config
├── tailwind.config.ts          # TailwindCSS config
├── tsconfig.json               # TypeScript config
└── package.json                # Dependencies
```

## 🎨 Customization

### Theme Colors

Edit `tailwind.config.ts` to customize colors:

```typescript
colors: {
  primary: {
    500: '#8b5cf6',  // Main brand color
    600: '#7c3aed',
  },
  secondary: {
    500: '#10b981',  // Accent color
  },
}
```

### Dark/Light Mode

The theme uses `next-themes` for automatic dark/light mode:

- System preference detection
- Manual toggle in header
- Smooth transitions

### Fonts

Uses Google Fonts (Inter + Plus Jakarta Sans):

```typescript
const inter = Inter({ subsets: ['latin'] });
const plusJakarta = Plus_Jakarta_Sans({ subsets: ['latin'] });
```

## 🔗 API Integration

### Connect to Laravel Backend

Update `next.config.js` with your API domain:

```javascript
images: {
  domains: ['your-laravel-api.com'],
},
```

Create an API service:

```typescript
// src/lib/api.ts
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
});

export default api;
```

### Environment Variables

Create `.env.local`:

```env
NEXT_PUBLIC_API_URL=https://your-api-domain.com/api
NEXT_PUBLIC_SITE_URL=https://your-site.com
```

## 📱 Pages to Create

Add these pages as needed:

- `/shop` - Product listing with filters
- `/product/[slug]` - Product detail page
- `/cart` - Shopping cart
- `/checkout` - Checkout flow
- `/account` - User dashboard
- `/categories` - All categories
- `/brands` - All brands
- `/about` - About page
- `/contact` - Contact page

## 🚀 Deployment

### Vercel (Recommended)

```bash
npm install -g vercel
vercel
```

### Static Export

```bash
npm run build
npx next export
```

### Docker

```dockerfile
FROM node:18-alpine
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build
EXPOSE 3000
CMD ["npm", "start"]
```

## ⚡ Performance Optimizations

1. **Images**: Use `next/image` for automatic optimization
2. **Fonts**: Google Fonts with `display: swap`
3. **CSS**: Purged TailwindCSS in production
4. **JS**: Automatic code splitting by route
5. **Caching**: 30-day image cache headers

## 📊 Lighthouse Score Target

- Performance: 95+
- Accessibility: 100
- Best Practices: 100
- SEO: 100

## 🤝 Integration with Laravel

This frontend is designed to work with your existing Laravel backend:

1. Set `NEXT_PUBLIC_API_URL` to your Laravel API endpoint
2. Implement API routes in Laravel for products, categories, cart
3. Use JWT or session-based authentication
4. Configure CORS in Laravel for Next.js domain

## 📝 License

MIT License - Feel free to use for commercial projects.

---

Built with ❤️ for Premium Fashion Store
