'use client';

import Link from 'next/link';
import { motion } from 'framer-motion';
import { ArrowRight } from 'lucide-react';

const categories = [
  { id: 1, name: 'Women', image: '/images/categories/women.jpg', count: 250, href: '/shop?category=women' },
  { id: 2, name: 'Men', image: '/images/categories/men.jpg', count: 180, href: '/shop?category=men' },
  { id: 3, name: 'Kids', image: '/images/categories/kids.jpg', count: 120, href: '/shop?category=kids' },
  { id: 4, name: 'Accessories', image: '/images/categories/accessories.jpg', count: 90, href: '/shop?category=accessories' },
  { id: 5, name: 'Shoes', image: '/images/categories/shoes.jpg', count: 150, href: '/shop?category=shoes' },
  { id: 6, name: 'Sale', image: '/images/categories/sale.jpg', count: 75, href: '/shop?sale=true' },
];

const containerVariants = {
  hidden: { opacity: 0 },
  visible: {
    opacity: 1,
    transition: { staggerChildren: 0.1 }
  }
};

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0 }
};

export function CategoriesSection() {
  return (
    <section className="section">
      <div className="container-custom">
        <div className="flex items-end justify-between mb-10">
          <div>
            <span className="badge-primary mb-2">Categories</span>
            <h2 className="heading-lg">Shop by Category</h2>
          </div>
          <Link href="/categories" className="btn-ghost hidden md:flex items-center gap-2">
            View All <ArrowRight className="w-4 h-4" />
          </Link>
        </div>

        <motion.div
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4"
        >
          {categories.map((category) => (
            <motion.div key={category.id} variants={itemVariants}>
              <Link 
                href={category.href}
                className="group block relative overflow-hidden rounded-2xl aspect-square"
              >
                {/* Placeholder background - replace with actual images */}
                <div className="absolute inset-0 bg-gradient-to-br from-primary-400 to-secondary-500" />
                <div className="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors" />
                
                <div className="absolute inset-0 flex flex-col items-center justify-center text-white p-4">
                  <h3 className="text-lg font-semibold mb-1">{category.name}</h3>
                  <p className="text-sm text-white/70">{category.count} Products</p>
                </div>

                <div className="absolute bottom-4 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all">
                  <span className="px-4 py-2 bg-white text-dark-bg text-sm font-medium rounded-full">
                    Shop Now
                  </span>
                </div>
              </Link>
            </motion.div>
          ))}
        </motion.div>

        <Link href="/categories" className="btn-ghost md:hidden flex items-center justify-center gap-2 mt-6">
          View All Categories <ArrowRight className="w-4 h-4" />
        </Link>
      </div>
    </section>
  );
}
