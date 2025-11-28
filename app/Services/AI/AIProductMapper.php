<?php

namespace App\Services\AI;

class AIProductMapper
{
    /**
     * Map raw analysis output into structured product fields expected by the frontend.
     *
     * @param array $analysis Expecting ['status'=>'ok','raw'=>json-string]
     * @return array
     */
    public function map(array $analysis): array
    {
        $defaults = [
            'name' => '',
            'description' => '',
            'category' => '',
            'sub_category' => '',
            'sub_sub_category' => '',
            'brand' => '',
            'product_type' => 'physical',
            'sku' => '',
            'unit' => '',
            'tags' => [],
        ];

        $raw = $analysis['raw'] ?? '{}';
        $json = json_decode($raw, true);
        if (!is_array($json)) $json = [];

        $data = array_merge($defaults, [
            'name' => (string)($json['name'] ?? ''),
            'description' => (string)($json['description'] ?? ''),
            'category' => (string)($json['category'] ?? ''),
            'sub_category' => (string)($json['sub_category'] ?? ''),
            'sub_sub_category' => (string)($json['sub_sub_category'] ?? ''),
            'brand' => (string)($json['brand'] ?? ''),
            'product_type' => in_array(($json['product_type'] ?? 'physical'), ['physical','digital']) ? $json['product_type'] : 'physical',
            'sku' => (string)($json['sku'] ?? ''),
            'unit' => (string)($json['unit'] ?? ''),
            'tags' => array_values(array_filter(array_map('strval', $json['tags'] ?? []))),
        ]);

        // SEO alias (optional)
        $data['seo'] = [
            'title' => (string)($json['meta_title'] ?? $data['name']),
            'description' => (string)($json['meta_description'] ?? $data['description']),
            'keywords' => implode(', ', $data['tags']),
        ];

        return $data;
    }
}
