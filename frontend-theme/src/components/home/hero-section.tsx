'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { motion, AnimatePresence } from 'framer-motion';
import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-react';

const slides = [
  {
    id: 1,
    title: 'Summer Collection 2024',
    subtitle: 'Discover the latest trends',
    description: 'Explore our exclusive summer collection featuring breathable fabrics and vibrant colors.',
    image: '/images/hero/slide1.jpg',
    cta: 'Shop Now',
    link: '/shop?collection=summer',
  },
  {
    id: 2,
    title: 'Premium Quality',
    subtitle: 'Crafted with excellence',
    description: 'Experience the finest materials and impeccable craftsmanship in every piece.',
    image: '/images/hero/slide2.jpg',
    cta: 'Explore',
    link: '/shop?quality=premium',
  },
  {
    id: 3,
    title: 'New Arrivals',
    subtitle: 'Fresh styles weekly',
    description: 'Stay ahead of fashion with our continuously updated collection of new arrivals.',
    image: '/images/hero/slide3.jpg',
    cta: 'View New',
    link: '/shop?sort=newest',
  },
];

export function HeroSection() {
  const [currentSlide, setCurrentSlide] = useState(0);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 6000);
    return () => clearInterval(timer);
  }, []);

  const nextSlide = () => setCurrentSlide((prev) => (prev + 1) % slides.length);
  const prevSlide = () => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);

  return (
    <section className="relative h-[80vh] min-h-[600px] w-full overflow-hidden bg-dark-bg">
      {/* Background Slides */}
      <AnimatePresence mode="wait">
        <motion.div
          key={currentSlide}
          initial={{ opacity: 0, scale: 1.1 }}
          animate={{ opacity: 1, scale: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.7 }}
          className="absolute inset-0"
        >
          {/* Placeholder gradient background - replace with actual images */}
          <div 
            className="absolute inset-0 bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900"
          />
          <div className="absolute inset-0 bg-black/40" />
        </motion.div>
      </AnimatePresence>

      {/* Content */}
      <div className="relative z-10 h-full container-custom flex items-center">
        <div className="max-w-2xl">
          <AnimatePresence mode="wait">
            <motion.div
              key={currentSlide}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -30 }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <span className="inline-block px-4 py-2 bg-primary-500/20 text-primary-300 rounded-full text-sm font-medium mb-4">
                {slides[currentSlide].subtitle}
              </span>
              <h1 className="heading-xl text-white mb-4">
                {slides[currentSlide].title}
              </h1>
              <p className="text-lg text-gray-300 mb-8 max-w-lg">
                {slides[currentSlide].description}
              </p>
              <div className="flex items-center gap-4">
                <Link href={slides[currentSlide].link} className="btn-primary">
                  {slides[currentSlide].cta}
                  <ArrowRight className="w-5 h-5" />
                </Link>
                <Link href="/categories" className="btn-secondary text-white border-white/30 hover:border-white">
                  Browse Categories
                </Link>
              </div>
            </motion.div>
          </AnimatePresence>
        </div>
      </div>

      {/* Navigation Arrows */}
      <div className="absolute z-20 bottom-8 right-8 flex items-center gap-3">
        <button
          onClick={prevSlide}
          className="w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-colors"
        >
          <ChevronLeft className="w-6 h-6" />
        </button>
        <button
          onClick={nextSlide}
          className="w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-colors"
        >
          <ChevronRight className="w-6 h-6" />
        </button>
      </div>

      {/* Slide Indicators */}
      <div className="absolute z-20 bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2">
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrentSlide(index)}
            className={`h-2 rounded-full transition-all duration-300 ${
              index === currentSlide
                ? 'w-8 bg-primary-500'
                : 'w-2 bg-white/50 hover:bg-white/80'
            }`}
          />
        ))}
      </div>

      {/* Decorative Elements */}
      <div className="absolute top-1/4 right-1/4 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl animate-pulse-slow" />
      <div className="absolute bottom-1/4 left-1/4 w-48 h-48 bg-secondary-500/20 rounded-full blur-3xl animate-pulse-slow" />
    </section>
  );
}
