import type { Metadata, Viewport } from 'next';
import { Inter, Plus_Jakarta_Sans } from 'next/font/google';
import './globals.css';
import { Providers } from '@/components/providers';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { Toaster } from 'react-hot-toast';

const inter = Inter({ 
  subsets: ['latin'],
  variable: '--font-inter',
  display: 'swap',
});

const plusJakarta = Plus_Jakarta_Sans({ 
  subsets: ['latin'],
  variable: '--font-display',
  display: 'swap',
});

export const metadata: Metadata = {
  title: {
    default: 'Premium Fashion Store',
    template: '%s | Premium Fashion Store',
  },
  description: 'Discover the latest fashion trends with our premium collection. Shop now for exclusive deals and free shipping.',
  keywords: ['fashion', 'clothing', 'premium', 'store', 'shop', 'style'],
  authors: [{ name: 'Premium Fashion' }],
  creator: 'Premium Fashion',
  openGraph: {
    type: 'website',
    locale: 'en_US',
    siteName: 'Premium Fashion Store',
  },
  twitter: {
    card: 'summary_large_image',
  },
  robots: {
    index: true,
    follow: true,
  },
};

export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  themeColor: [
    { media: '(prefers-color-scheme: light)', color: '#ffffff' },
    { media: '(prefers-color-scheme: dark)', color: '#0a0a0f' },
  ],
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body className={`${inter.variable} ${plusJakarta.variable} font-sans antialiased`}>
        <Providers>
          <div className="min-h-screen flex flex-col bg-light-bg dark:bg-dark-bg transition-colors duration-300">
            <Header />
            <main className="flex-1">{children}</main>
            <Footer />
          </div>
          <Toaster 
            position="top-right"
            toastOptions={{
              className: 'bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text border border-light-border dark:border-dark-border',
              duration: 4000,
            }}
          />
        </Providers>
      </body>
    </html>
  );
}
