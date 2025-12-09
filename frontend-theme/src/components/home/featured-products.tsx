'use client';

import { useState } from 'react';
import Link from 'next/link';
import { motion } from 'framer-motion';
import { ArrowRight } from 'lucide-react';
import { ProductCard } from '@/components/product/product-card';

const tabs = ['All', 'New Arrivals', 'Best Sellers', 'On Sale'];

// Sample products data - replace with API data
const products = [
  {
    id: '1',
    name: 'Premium Cotton T-Shirt',
    slug: 'premium-cotton-t-shirt',
    price: 49.99,
    originalPrice: 69.99,
    image: '/images/products/product1.jpg',
    category: 'Men',
    rating: 4.8,
    reviews: 124,
    isNew: true,
    isSale: true,
  },
  {
    id: '2',
    name: 'Classic Denim Jacket',
    slug: 'classic-denim-jacket',
    price: 129.99,
    image: '/images/products/product2.jpg',
    category: 'Women',
    rating: 4.9,
    reviews: 89,
    isNew: true,
    isSale: false,
  },
  {
    id: '3',
    name: 'Leather Crossbody Bag',
    slug: 'leather-crossbody-bag',
    price: 89.99,
    originalPrice: 119.99,
    image: '/images/products/product3.jpg',
    category: 'Accessories',
    rating: 4.7,
    reviews: 56,
    isNew: false,
    isSale: true,
  },
  {
    id: '4',
    name: 'Running Sneakers Pro',
    slug: 'running-sneakers-pro',
    price: 159.99,
    image: '/images/products/product4.jpg',
    category: 'Shoes',
    rating: 4.9,
    reviews: 203,
    isNew: true,
    isSale: false,
  },
  {
    id: '5',
    name: 'Silk Summer Dress',
    slug: 'silk-summer-dress',
    price: 199.99,
    originalPrice: 249.99,
    image: '/images/products/product5.jpg',
    category: 'Women',
    rating: 4.8,
    reviews: 67,
    isNew: false,
    isSale: true,
  },
  {
    id: '6',
    name: 'Slim Fit Chinos',
    slug: 'slim-fit-chinos',
    price: 79.99,
    image: '/images/products/product6.jpg',
    category: 'Men',
    rating: 4.6,
    reviews: 145,
    isNew: false,
    isSale: false,
  },
  {
    id: '7',
    name: 'Cashmere Sweater',
    slug: 'cashmere-sweater',
    price: 189.99,
    image: '/images/products/product7.jpg',
    category: 'Women',
    rating: 4.9,
    reviews: 78,
    isNew: true,
    isSale: false,
  },
  {
    id: '8',
    name: 'Aviator Sunglasses',
    slug: 'aviator-sunglasses',
    price: 129.99,
    originalPrice: 159.99,
    image: '/images/products/product8.jpg',
    category: 'Accessories',
    rating: 4.7,
    reviews: 92,
    isNew: false,
    isSale: true,
  },
];

export function FeaturedProducts() {
  const [activeTab, setActiveTab] = useState('All');

  const filteredProducts = products.filter((product) => {
    if (activeTab === 'All') return true;
    if (activeTab === 'New Arrivals') return product.isNew;
    if (activeTab === 'Best Sellers') return product.rating >= 4.8;
    if (activeTab === 'On Sale') return product.isSale;
    return true;
  });

  return (
    <section className="section bg-light-bg dark:bg-dark-bg">
      <div className="container-custom">
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
          <div>
            <span className="badge-primary mb-2">Featured</span>
            <h2 className="heading-lg">Trending Products</h2>
          </div>
          
          {/* Tabs */}
          <div className="flex items-center gap-2 overflow-x-auto hide-scrollbar">
            {tabs.map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all ${
                  activeTab === tab
                    ? 'bg-primary-500 text-white'
                    : 'bg-light-card dark:bg-dark-card text-light-muted dark:text-dark-muted hover:text-light-text dark:hover:text-dark-text'
                }`}
              >
                {tab}
              </button>
            ))}
          </div>
        </div>

        <motion.div
          layout
          className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6"
        >
          {filteredProducts.map((product) => (
            <motion.div
              key={product.id}
              layout
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.9 }}
              transition={{ duration: 0.3 }}
            >
              <ProductCard product={product} />
            </motion.div>
          ))}
        </motion.div>

        <div className="flex justify-center mt-10">
          <Link href="/shop" className="btn-primary">
            View All Products <ArrowRight className="w-5 h-5" />
          </Link>
        </div>
      </div>
    </section>
  );
}
