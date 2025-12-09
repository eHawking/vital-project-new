'use client';

import { motion } from 'framer-motion';

const brands = [
  { id: 1, name: 'Nike', logo: '/images/brands/nike.svg' },
  { id: 2, name: 'Adidas', logo: '/images/brands/adidas.svg' },
  { id: 3, name: 'Puma', logo: '/images/brands/puma.svg' },
  { id: 4, name: 'Gucci', logo: '/images/brands/gucci.svg' },
  { id: 5, name: 'Zara', logo: '/images/brands/zara.svg' },
  { id: 6, name: 'H&M', logo: '/images/brands/hm.svg' },
  { id: 7, name: 'Levis', logo: '/images/brands/levis.svg' },
  { id: 8, name: 'Calvin Klein', logo: '/images/brands/ck.svg' },
];

export function BrandsSection() {
  return (
    <section className="py-12 border-y border-light-border dark:border-dark-border">
      <div className="container-custom">
        <div className="text-center mb-8">
          <h2 className="text-lg font-medium text-light-muted dark:text-dark-muted">
            Trusted by Top Brands
          </h2>
        </div>

        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          className="flex items-center justify-center flex-wrap gap-8 md:gap-16"
        >
          {brands.map((brand) => (
            <motion.div
              key={brand.id}
              whileHover={{ scale: 1.1 }}
              className="opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
            >
              {/* Placeholder - replace with actual brand logos */}
              <div className="w-24 h-12 rounded bg-light-border dark:bg-dark-border flex items-center justify-center">
                <span className="text-sm font-semibold text-light-muted dark:text-dark-muted">
                  {brand.name}
                </span>
              </div>
            </motion.div>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
