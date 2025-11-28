-- Create ai_generated_images table
-- Run this SQL directly in your database if you cannot run migrations

CREATE TABLE IF NOT EXISTS `ai_generated_images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product' COMMENT 'product, placeholder, etc.',
  `angle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'front, back, left, right, etc.',
  `width` int(11) NOT NULL DEFAULT 500,
  `height` int(11) NOT NULL DEFAULT 500,
  `size` int(11) DEFAULT NULL COMMENT 'file size in bytes',
  `prompt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `generated_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Admin/Seller user ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_generated_images_type_index` (`type`),
  KEY `ai_generated_images_created_at_index` (`created_at`),
  KEY `ai_generated_images_product_id_foreign` (`product_id`),
  CONSTRAINT `ai_generated_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add to migrations table to mark as run
INSERT INTO `migrations` (`migration`, `batch`) 
VALUES ('2025_10_11_083343_create_ai_generated_images_table', 
        (SELECT COALESCE(MAX(batch), 0) + 1 FROM (SELECT batch FROM migrations) AS temp))
ON DUPLICATE KEY UPDATE migration = migration;
