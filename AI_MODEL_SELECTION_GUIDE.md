# AI Model Selection System - Implementation Guide

## Overview
This system allows admins to select and configure different Gemini AI models for product content and image generation.

## Available Models

### 1. **Gemini 2.5 Flash** (Default)
- **Model ID**: `gemini-2.5-flash`
- **Best For**: Fast product analysis and text generation
- **Features**: Balanced speed and quality
- **Use Case**: General product descriptions, category detection

### 2. **Gemini 2.5 Flash Image Preview**
- **Model ID**: `gemini-2.5-flash-image-preview`
- **Best For**: Image analysis with preview capabilities
- **Features**: Enhanced image understanding
- **Use Case**: Product image analysis, visual feature detection

### 3. **Gemini 2.5 Flash Image**
- **Model ID**: `gemini-2.5-flash-image`
- **Best For**: Optimized image processing
- **Features**: Specialized for image generation and manipulation
- **Use Case**: Product image generation, image enhancement

### 4. **Gemini 2.5 Flash Preview (Sep 2025)**
- **Model ID**: `gemini-2.5-flash-preview-09-2025`
- **Best For**: Testing latest features
- **Features**: Preview version with experimental capabilities
- **Use Case**: Testing new AI features before general release

### 5. **Gemini 2.5 Pro**
- **Model ID**: `gemini-2.5-pro`
- **Best For**: Complex analysis and high-quality generation
- **Features**: Most powerful model with advanced reasoning
- **Use Case**: Detailed product descriptions, complex categorization

## Configuration

### Admin Settings Location
**Admin Panel → AI Settings → General Settings**

### Settings Options

#### 1. **Gemini API Key** (Required)
- Your Google AI Studio API key
- Get it from: https://aistudio.google.com/app/apikey
- Can also be set in `.env` as `GOOGLE_GEMINI_API_KEY`

#### 2. **Select AI Model** (Required)
- Choose one model from the dropdown
- This model will be used for all AI operations
- Disabled when "Use All Models" is enabled

#### 3. **Use All Models** (Optional)
- Toggle switch to enable/disable
- When enabled: System will try all available models
- When disabled: Only selected model is used
- Useful for: Better results through model ensemble

#### 4. **Advanced Settings**
- **Analyze Model**: Specific model for product analysis
- **Image Model**: Specific model for image generation
- Falls back to selected model if not specified

## How It Works

### Single Model Mode (Default)
```
User uploads product image
    ↓
Selected model analyzes image
    ↓
Same model generates content
    ↓
Same model generates product images (8x 500x500px)
```

### All Models Mode
```
User uploads product image
    ↓
All 5 models analyze image in parallel
    ↓
Best result is selected
    ↓
All models generate content
    ↓
Combined/best content is used
    ↓
All models generate images
    ↓
Best 8 images selected (500x500px each)
```

## Database Schema

### New Settings
```sql
-- business_settings table
type: 'ai_model_selected'
value: 'gemini-2.5-flash' (default)

type: 'ai_use_all_models'
value: '0' or '1'
```

### AI Generated Images
```sql
-- ai_generated_images table
- id
- filename
- path
- url
- type (product/placeholder)
- angle (front/back/left/right/top/detail/lifestyle/package)
- width (500)
- height (500)
- size (bytes)
- prompt
- product_id (nullable)
- generated_by (admin/seller user ID)
- created_at
- updated_at
```

## Image Specifications

### Aspect Ratio
- **Changed from**: 400x500px (1:1.25)
- **Changed to**: 500x500px (1:1 - Square)
- **Reason**: Better for product displays, social media, and modern e-commerce

### Image Generation
- **Total Images**: 8 per product
- **Thumbnail**: First image (main product view)
- **Additional**: 7 images (different angles/views)
- **Format**: PNG
- **Quality**: 90%
- **Storage**: `public/uploads/products/ai/YYYY/MM/`

### Angles Generated
1. **front** - Main product view
2. **back** - Back view
3. **left** - Left side view
4. **right** - Right side view
5. **top** - Top view
6. **detail** - Close-up details
7. **lifestyle** - Product in use
8. **package** - Packaging view

## API Integration

### GeminiSettingsService Methods

```php
// Get current configuration
$config = $settings->get();
// Returns: ['selected_model', 'use_all_models', 'analyze_model', 'image_model', ...]

// Get available models
$models = $settings->getAvailableModels();
// Returns: ['gemini-2.5-flash' => 'Gemini 2.5 Flash', ...]

// Get model for analysis
$model = $settings->getAnalyzeModel();
// Returns: 'gemini-2.5-flash'

// Get model for images
$model = $settings->getImageModel();
// Returns: 'gemini-2.5-flash-image'

// Get all models to use
$models = $settings->getModelsToUse();
// Returns: ['gemini-2.5-flash'] or all 5 models if use_all_models is enabled
```

### GeminiVisionService
```php
// Automatically uses selected model
$result = $visionService->analyze($images);
```

### GeminiImageService
```php
// Automatically uses selected model
$result = $imageService->generateSet($prompt, $context, 8);
```

## Files Modified

### Backend
1. `app/Services/AI/GeminiSettingsService.php` - Model management
2. `app/Services/AI/GeminiVisionService.php` - Uses selected model
3. `app/Services/AI/GeminiImageService.php` - 1:1 ratio, auto-save to DB
4. `app/Http/Controllers/Admin/Settings/AISettingsController.php` - Model selection
5. `app/Http/Controllers/Admin/AI/AIImagesController.php` - Gallery management
6. `app/Models/AIGeneratedImage.php` - New model for images
7. `routes/admin/routes.php` - New routes for gallery

### Frontend
1. `resources/views/admin-views/business-settings/ai-settings.blade.php` - Model selection UI
2. `resources/views/admin-views/ai/images/index.blade.php` - Gallery view
3. `resources/views/admin-views/product/add\_ai-sidebar.blade.php` - Floating AI button

### Database
1. `database/migrations/2025_10_11_083343_create_ai_generated_images_table.php`
2. `database/migrations/2025_10_11_085524_add_ai_model_settings_to_business_settings_table.php`

## Deployment Steps

1. **Pull latest code**:
   ```bash
   cd /var/www/vhosts/dewdropskin.com/httpdocs
   git pull origin main
   ```

2. **Run migrations**:
   ```bash
   php artisan migrate
   ```

3. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

4. **Set permissions** (if needed):
   ```bash
   chmod -R 755 public/uploads/products/ai
   ```

5. **Configure AI Settings**:
   - Go to Admin Panel → AI Settings
   - Enter Gemini API Key
   - Select preferred model
   - Save settings

## Testing

### Test Model Selection
1. Go to **Admin → AI Settings**
2. Select different models from dropdown
3. Save and verify settings persist
4. Enable "Use All Models" and verify dropdown disables

### Test Image Generation
1. Go to **Admin → Products → Add New Product**
2. Click floating AI button (bottom right)
3. Upload product image
4. Click "Generate"
5. Verify 8 images generated (500x500px)
6. Check thumbnail and additional images filled

### Test Gallery
1. Go to **Admin → AI Settings → AI Images**
2. Verify all generated images appear
3. Test filters (type, search)
4. Test delete single image
5. Test bulk delete
6. Test clear old images

## Troubleshooting

### Images Not Generating
- Check API key is valid
- Verify selected model is available
- Check Laravel logs: `storage/logs/laravel.log`
- Ensure `public/uploads/products/ai/` is writable

### Model Not Working
- Some models may require specific API access
- Try switching to `gemini-2.5-flash` (most stable)
- Check Gemini API quotas and limits

### Gallery Not Showing Images
- Run migration: `php artisan migrate`
- Check database table: `ai_generated_images`
- Verify images exist in `public/uploads/products/ai/`

## Future Enhancements

1. **Model Performance Metrics**: Track which model produces best results
2. **A/B Testing**: Compare results from different models
3. **Cost Tracking**: Monitor API usage per model
4. **Batch Processing**: Generate images for multiple products
5. **Custom Prompts**: Allow custom prompts per model
6. **Image Editing**: Edit generated images before saving
7. **Model Fallback**: Auto-switch if primary model fails

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Review browser console for frontend errors
- Verify API key and model availability
- Test with default model: `gemini-2.5-flash`

---

**Last Updated**: 2025-10-11
**Version**: 1.0.0
