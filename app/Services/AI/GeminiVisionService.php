<?php

namespace App\Services\AI;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class GeminiVisionService
{
    public function __construct(private readonly GeminiSettingsService $settings)
    {
    }

    /**
     * Analyze one or multiple images using Google Gemini (vision) and return raw model text.
     *
     * @param UploadedFile[] $images
     * @return array Raw response payload for further mapping
     */
    public function analyze(array $images): array
    {
        $cfg = $this->settings->get();
        $apiKey = $cfg['gemini_api_key'] ?? '';
        
        // Use selected model
        $model = $cfg['selected_model'] ?? 'gemini-2.5-flash';

        // Prepare inline image parts (base64) per Gemini v1beta generateContent
        $parts = [];
        foreach ($images as $file) {
            if (!$file instanceof UploadedFile) continue;
            $mime = $file->getMimeType() ?: 'image/jpeg';
            $b64 = base64_encode(file_get_contents($file->getRealPath()));
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mime,
                    'data' => $b64,
                ],
            ];
        }

        // Instruction asking for strict JSON with LONG, SEO-optimized HTML description
        $instruction = [
            'text' => "You are an e-commerce product expert. Analyze the images carefully and output ONLY valid JSON with these keys: name, description, category, sub_category, sub_sub_category, brand, product_type, sku, unit, tags (array of strings), meta_title, meta_description.\n\nCRITICAL REQUIREMENTS:\n\n1. 'name': Create a compelling, SEO-friendly product name (50-80 characters). Include brand if visible.\n\n2. 'description': Generate a COMPREHENSIVE, PROFESSIONAL, SEO-optimized HTML description (minimum 800 words). Structure it with these sections:\n\n<h2>Product Overview</h2>\n<p>Write 2-3 detailed paragraphs describing what the product is, its primary purpose, target audience, and key selling points. Be specific about what makes this product unique.</p>\n\n<h3>Key Features & Benefits</h3>\n<ul>\n<li>List 8-12 specific features with benefits</li>\n<li>Focus on functionality, quality, design, and value</li>\n<li>Use action-oriented language</li>\n</ul>\n\n<h3>Technical Specifications</h3>\n<ul>\n<li>Dimensions and weight (estimate if not visible)</li>\n<li>Materials and construction</li>\n<li>Color and finish options</li>\n<li>Compatibility and requirements</li>\n<li>Performance specifications</li>\n</ul>\n\n<h3>What's Included</h3>\n<ul>\n<li>Main product</li>\n<li>Accessories and components</li>\n<li>Documentation and warranty card</li>\n<li>Packaging details</li>\n</ul>\n\n<h3>How to Use</h3>\n<p>Provide step-by-step usage instructions (3-5 steps). Include setup, operation, and best practices.</p>\n\n<h3>Care & Maintenance</h3>\n<p>Detailed cleaning, storage, and maintenance guidelines to ensure longevity.</p>\n\n<h3>Quality & Warranty</h3>\n<p>Describe build quality, certifications, warranty coverage, and customer support.</p>\n\n<h3>Why Choose This Product</h3>\n<ul>\n<li>3-5 compelling reasons</li>\n<li>Competitive advantages</li>\n<li>Customer satisfaction points</li>\n</ul>\n\n<h3>Frequently Asked Questions</h3>\n<dl>\n<dt>Question 1?</dt><dd>Detailed answer</dd>\n<dt>Question 2?</dt><dd>Detailed answer</dd>\n<dt>Question 3?</dt><dd>Detailed answer</dd>\n</dl>\n\n<p><strong>Order now and experience premium quality!</strong> Fast shipping, secure checkout, and satisfaction guaranteed.</p>\n\n3. 'category', 'sub_category', 'sub_sub_category': Identify the most specific product categories.\n\n4. 'brand': Extract brand name from image or packaging.\n\n5. 'product_type': Set to 'physical' or 'digital'.\n\n6. 'sku': Generate a professional SKU code (e.g., BRAND-CATEGORY-001).\n\n7. 'unit': Specify the unit of measurement (pc, kg, l, m, etc.).\n\n8. 'tags': Create 10-15 relevant search tags including product type, features, use cases, and target audience.\n\n9. 'meta_title': SEO title (50-60 characters) with primary keyword.\n\n10. 'meta_description': Compelling meta description (150-160 characters) with call-to-action.\n\nIMPORTANT:\n- Write naturally and professionally\n- Include relevant keywords but avoid keyword stuffing\n- Be factual based on what you see in the images\n- Use proper HTML tags (h2, h3, p, ul, li, dl, dt, dd, strong)\n- NO inline styles or scripts\n- Output ONLY valid JSON, nothing else"
        ];

        $payload = [
            'contents' => [[
                'role' => 'user',
                'parts' => array_merge([$instruction], $parts),
            ]],
            'generationConfig' => [
                'temperature' => 0.4,
                'responseMimeType' => 'application/json',
            ],
        ];

        // If no API key, return an empty payload to allow graceful fallback
        if (empty($apiKey)) {
            return [
                'status' => 'unconfigured',
                'raw' => '{}',
            ];
        }

        $client = new Client(['timeout' => 60]);
        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $model,
            $apiKey
        );

        try {
            $resp = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);
            $json = json_decode((string) $resp->getBody(), true);
            $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            return [
                'status' => 'ok',
                'raw' => $text,
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'raw' => '{}',
                'error' => $e->getMessage(),
            ];
        }
    }
}
