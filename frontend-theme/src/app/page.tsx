import { HeroSection } from '@/components/home/hero-section';
import { CategoriesSection } from '@/components/home/categories-section';
import { FeaturedProducts } from '@/components/home/featured-products';
import { BrandsSection } from '@/components/home/brands-section';
import { NewsletterSection } from '@/components/home/newsletter-section';

export default function HomePage() {
  return (
    <>
      <HeroSection />
      <CategoriesSection />
      <FeaturedProducts />
      <BrandsSection />
      <NewsletterSection />
    </>
  );
}
