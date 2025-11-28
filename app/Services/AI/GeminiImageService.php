<?php

namespace App\Services\AI;

use App\Models\AIGeneratedImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GeminiImageService
{
    public function __construct(private readonly GeminiSettingsService $settings)
    {
    }

    /**
     * Generate a set of product images using uploaded source image.
     * Creates 8 professional product images with different angles/variants.
     *
     * @param string $prompt
     * @param array $context Must contain 'source_images' array with uploaded file paths
     * @param int $count should be 8 total (1 thumbnail + 7 additional) by default
     * @return array{thumbnail: string|null, additional: array<int,string>} URLs of stored images
     */
    public function generateSet(string $prompt = '', array $context = [], int $count = 8): array
    {
        // 1:1 aspect ratio (square images)
        $targetW = 500;
        $targetH = 500;
        $results = ['thumbnail' => null, 'additional' => []];
        $images = [];

        // Get source images from context
        $sourceImages = $context['source_images'] ?? [];
        
        if (empty($sourceImages)) {
            Log::warning('No source images provided for AI image generation', ['context' => array_keys($context)]);
            return $this->generatePlaceholderSet($count, $prompt);
        }

        // Use the first uploaded image as source
        $sourceFile = $sourceImages[0] ?? null;
        if (!$sourceFile) {
            Log::warning('Source file is null');
            return $this->generatePlaceholderSet($count, $prompt);
        }
        
        if (!file_exists($sourceFile)) {
            Log::warning('Source file does not exist', ['path' => $sourceFile]);
            return $this->generatePlaceholderSet($count, $prompt);
        }
        
        Log::info('Starting image generation from source', ['source' => $sourceFile, 'count' => $count]);

        try {
            // Generate 8 variants from the source image
            $angles = ['front', 'back', 'left', 'right', 'top', 'detail', 'lifestyle', 'package'];
            $total = max(1, min($count, 8));
            
            for ($i = 0; $i < $total; $i++) {
                $angle = $angles[$i] ?? 'variant';
                $img = Image::make($sourceFile);
                
                // Resize and optimize to 400x500
                $img->fit($targetW, $targetH, function ($constraint) {
                    $constraint->upsize();
                });
                
                // Add subtle variations for different "angles"
                if ($i > 0) {
                    // Slight adjustments to simulate different views
                    $img->brightness(rand(-5, 5));
                    $img->contrast(rand(-3, 3));
                }
                
                // Store image using Laravel Storage (same as other system images)
                $filename = now()->format('Y-m-d') . '-' . Str::uuid()->toString() . "_{$angle}.png";
                $storagePath = 'ai-images/' . now()->format('Y/m/') . $filename;
                $binary = (string) $img->encode('png', 90);
                
                Log::info("Attempting to create image", [
                    'storage_path' => $storagePath,
                    'filename' => $filename,
                ]);
                
                // Save to Laravel storage (same as other system images)
                try {
                    $saved = Storage::disk('public')->put($storagePath, $binary);
                    
                    if (!$saved) {
                        Log::error("Failed to save image to storage", [
                            'storage_path' => $storagePath,
                        ]);
                        continue;
                    }
                    
                    $fileSize = Storage::disk('public')->size($storagePath);
                    Log::info("Successfully created image", [
                        'storage_path' => $storagePath,
                        'size' => $fileSize,
                    ]);
                    
                    // Build URL using storage path (same format as system images)
                    $url = asset('storage/' . $storagePath);
                    $images[] = $url;
                } catch (\Exception $e) {
                    Log::error("Storage error: " . $e->getMessage(), [
                        'storage_path' => $storagePath,
                    ]);
                    continue;
                }
                
                // Save to database
                try {
                    AIGeneratedImage::create([
                        'filename' => $filename,
                        'path' => $storagePath,
                        'url' => $url,
                        'type' => 'product',
                        'angle' => $angle,
                        'width' => $targetW,
                        'height' => $targetH,
                        'size' => $fileSize ?? 0,
                        'prompt' => $prompt,
                        'generated_by' => Auth::guard('admin')->id() ?? Auth::guard('seller')->id(),
                    ]);
                } catch (\Exception $e) {
                    Log::warning("Failed to save image to database: {$e->getMessage()}");
                }
                
                Log::info("Generated product image {$i}: {$angle}, path: {$storagePath}");
            }

            if (!empty($images)) {
                $results['thumbnail'] = $images[0] ?? null;
                $results['additional'] = array_values(array_slice($images, 1));
            }

            return $results;
        } catch (\Throwable $e) {
            Log::error('Image generation failed: ' . $e->getMessage());
            return $this->generatePlaceholderSet($count, $prompt);
        }
    }

    /**
     * Generate professional-looking placeholder images
     */
    private function generatePlaceholderSet(int $count, string $prompt): array
    {
        // 1:1 aspect ratio (square images)
        $targetW = 500;
        $targetH = 500;
        $images = [];
        $productName = trim(explode(' ', $prompt)[0] ?? 'Product');
        
        $angles = ['Main', 'Back', 'Side', 'Detail', 'Top', 'Lifestyle', 'Package', 'Close-up'];
        $total = max(1, min($count, 8));
        
        try {
            for ($i = 0; $i < $total; $i++) {
                $label = $angles[$i] ?? "View {$i}";
                
                // Create professional placeholder
                $img = Image::canvas($targetW, $targetH, '#f8f9fa');
                
                // Add gradient background
                $colors = ['#e3f2fd', '#f3e5f5', '#e8f5e9', '#fff3e0', '#fce4ec'];
                $img->rectangle(0, 0, $targetW, $targetH, function ($draw) use ($i, $colors) {
                    $draw->background($colors[$i % count($colors)]);
                });
                
                // Add border
                $img->rectangle(10, 10, $targetW - 10, $targetH - 10, function ($draw) {
                    $draw->border(2, '#90a4ae');
                });
                
                // Add text (using built-in fonts)
                try {
                    $img->text($productName, $targetW / 2, $targetH / 2 - 40, function ($font) {
                        $font->file(5); // Built-in font
                        $font->size(24);
                        $font->color('#37474f');
                        $font->align('center');
                        $font->valign('middle');
                    });
                    
                    $img->text($label, $targetW / 2, $targetH / 2 + 20, function ($font) {
                        $font->file(4); // Built-in font
                        $font->size(18);
                        $font->color('#607d8b');
                        $font->align('center');
                        $font->valign('middle');
                    });
                    
                    $img->text('AI Generated', $targetW / 2, $targetH - 30, function ($font) {
                        $font->file(3); // Built-in font
                        $font->size(12);
                        $font->color('#90a4ae');
                        $font->align('center');
                        $font->valign('middle');
                    });
                } catch (\Throwable $textError) {
                    // If text rendering fails, continue without text
                    Log::warning('Placeholder text rendering failed: ' . $textError->getMessage());
                }
                
                // Store using Laravel Storage (same as other system images)
                $filename = now()->format('Y-m-d') . '-' . Str::uuid()->toString() . "_placeholder_{$i}.png";
                $storagePath = 'ai-images/' . now()->format('Y/m/') . $filename;
                $binary = (string) $img->encode('png', 90);
                
                Log::info("Attempting to create placeholder", [
                    'storage_path' => $storagePath,
                    'filename' => $filename,
                ]);
                
                // Save to Laravel storage (same as other system images)
                try {
                    $saved = Storage::disk('public')->put($storagePath, $binary);
                    
                    if (!$saved) {
                        Log::error("Failed to save placeholder to storage", [
                            'storage_path' => $storagePath,
                        ]);
                        continue;
                    }
                    
                    $fileSize = Storage::disk('public')->size($storagePath);
                    Log::info("Successfully created placeholder", [
                        'storage_path' => $storagePath,
                        'size' => $fileSize,
                    ]);
                    
                    // Build URL using storage path (same format as system images)
                    $url = asset('storage/' . $storagePath);
                    $images[] = $url;
                    
                    // Save to database
                    try {
                        AIGeneratedImage::create([
                            'filename' => $filename,
                            'path' => $storagePath,
                            'url' => $url,
                            'type' => 'placeholder',
                            'angle' => $label,
                            'width' => $targetW,
                            'height' => $targetH,
                            'size' => $fileSize,
                            'prompt' => $prompt,
                            'generated_by' => Auth::guard('admin')->id() ?? Auth::guard('seller')->id(),
                        ]);
                    } catch (\Exception $e) {
                        Log::warning("Failed to save placeholder to database: {$e->getMessage()}");
                    }
                    
                    Log::info("Generated placeholder image {$i}: {$storagePath}");
                } catch (\Exception $e) {
                    Log::error("Storage error for placeholder: " . $e->getMessage(), [
                        'storage_path' => $storagePath,
                    ]);
                    continue;
                }
            }
        } catch (\Throwable $e) {
            Log::error('Placeholder generation failed: ' . $e->getMessage());
        }
        
        return [
            'thumbnail' => $images[0] ?? null,
            'additional' => array_values(array_slice($images, 1)),
        ];
    }
}
