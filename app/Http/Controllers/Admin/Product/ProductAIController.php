<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\BaseController;
use App\Services\AI\GeminiSettingsService;
use App\Services\AI\GeminiVisionService;
use App\Services\AI\GeminiImageService;
use App\Services\AI\AIProductMapper;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ProductAIController extends BaseController
{
    public function __construct(
        private readonly GeminiSettingsService $settings,
        private readonly GeminiVisionService $vision,
        private readonly GeminiImageService $images,
        private readonly AIProductMapper $mapper,
    ) {}

    public function index(?Request $request, string $type = null): View|EloquentCollection|LengthAwarePaginator|callable|RedirectResponse|JsonResponse|null
    {
        // Not used: keep interface compatibility. Redirect to products add page by default.
        return redirect()->route('admin.products.add');
    }

    public function analyze(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'images' => 'required|array|min:1',
                'images.*' => 'file|image|mimes:jpeg,png,webp,jpg|max:8192',
            ]);

            if (!$this->settings->isConfigured()) {
                return response()->json(['status' => 'error', 'message' => translate('AI_settings_missing')], 422);
            }

            $files = $request->file('images', []);
            if (empty($files)) {
                return response()->json(['status' => 'error', 'message' => translate('please_upload_images_first')], 422);
            }

            $analysis = $this->vision->analyze($files);
            $mapped = $this->mapper->map($analysis);
            
            // Store source images temporarily for image generation
            $sourcePaths = [];
            foreach ($files as $file) {
                // Store in temp directory with unique name
                $tempPath = storage_path('app/temp/ai_' . uniqid() . '_' . $file->getClientOriginalName());
                @mkdir(dirname($tempPath), 0755, true);
                copy($file->getRealPath(), $tempPath);
                $sourcePaths[] = $tempPath;
            }
            session(['ai_source_images' => $sourcePaths, 'ai_source_time' => time()]);

            return response()->json([
                'status' => 'success',
                'data' => $mapped,
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'status' => 'error',
                'message' => $ve->getMessage(),
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('AI analyze failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $msg = config('app.debug') ? $e->getMessage() : translate('something_went_wrong');
            return response()->json(['status' => 'error', 'message' => $msg], 500);
        }
    }

    public function generateImages(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'prompt' => 'nullable|string',
                'context' => 'nullable|array',
                'count' => 'nullable|integer|min:1|max:8',
            ]);

            if (!$this->settings->isConfigured()) {
                return response()->json(['status' => 'error', 'message' => translate('AI_settings_missing')], 422);
            }

            $count = (int)($request->input('count', 8));
            $context = (array) $request->input('context', []);
            
            // Get source images from session
            $sourceImages = session('ai_source_images', []);
            $sourceTime = session('ai_source_time', 0);
            
            Log::info('AI Image Generation Request', [
                'count' => $count,
                'has_source_images' => !empty($sourceImages),
                'source_count' => count($sourceImages),
                'source_age' => time() - $sourceTime,
            ]);
            
            // Check if source images are still valid (within 5 minutes)
            if (!empty($sourceImages) && (time() - $sourceTime) < 300) {
                // Verify files still exist
                $validSources = [];
                foreach ($sourceImages as $path) {
                    if (file_exists($path)) {
                        $validSources[] = $path;
                        Log::info("Source image exists: {$path}");
                    } else {
                        Log::warning("Source image missing: {$path}");
                    }
                }
                if (!empty($validSources)) {
                    $context['source_images'] = $validSources;
                    Log::info("Using {count} valid source images", ['count' => count($validSources)]);
                }
            } else {
                Log::info('No valid source images, will generate placeholders');
            }
            
            // Check directory permissions before generation
            $uploadDir = public_path('uploads/products/ai');
            if (!file_exists($uploadDir)) {
                Log::warning("Upload directory does not exist: {$uploadDir}");
            } else {
                $perms = substr(sprintf('%o', fileperms($uploadDir)), -4);
                Log::info("Upload directory permissions: {$perms}");
            }
            
            $result = $this->images->generateSet(
                prompt: (string) $request->input('prompt', ''),
                context: $context,
                count: $count
            );
            
            Log::info('AI Image Generation Result', [
                'thumbnail' => $result['thumbnail'] ?? 'null',
                'additional_count' => count($result['additional'] ?? []),
                'additional_urls' => $result['additional'] ?? [],
            ]);
            
            // Clean up temp files and clear session
            if (!empty($sourceImages)) {
                foreach ($sourceImages as $path) {
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
            session()->forget(['ai_source_images', 'ai_source_time']);

            return response()->json([
                'status' => 'success',
                'data' => $result, // expects ['thumbnail' => url, 'additional' => [urls...]]
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
              ->header('Pragma', 'no-cache')
              ->header('Expires', '0');
        } catch (ValidationException $ve) {
            return response()->json([
                'status' => 'error',
                'message' => $ve->getMessage(),
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('AI image generation failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $msg = config('app.debug') ? $e->getMessage() : translate('something_went_wrong');
            return response()->json(['status' => 'error', 'message' => $msg], 500);
        }
    }
    
    /**
     * Test endpoint to diagnose file creation issues
     */
    public function testFileCreation(): JsonResponse
    {
        $results = [];
        
        // Test 1: Check if public path is correct
        $publicPath = public_path();
        $results['public_path'] = $publicPath;
        $results['public_path_exists'] = file_exists($publicPath);
        
        // Test 2: Check uploads directory
        $uploadsDir = public_path('uploads');
        $results['uploads_dir'] = $uploadsDir;
        $results['uploads_exists'] = file_exists($uploadsDir);
        $results['uploads_writable'] = is_writable($uploadsDir);
        
        // Test 3: Check AI directory
        $aiDir = public_path('uploads/products/ai');
        $results['ai_dir'] = $aiDir;
        $results['ai_exists'] = file_exists($aiDir);
        $results['ai_writable'] = is_writable($aiDir);
        
        // Test 4: Try to create test directory
        $testDir = public_path('uploads/products/ai/test');
        try {
            if (!file_exists($testDir)) {
                $created = mkdir($testDir, 0755, true);
                $results['test_dir_created'] = $created;
                if ($created) {
                    chmod($testDir, 0755);
                    rmdir($testDir);
                }
            } else {
                $results['test_dir_created'] = 'already_exists';
            }
        } catch (\Exception $e) {
            $results['test_dir_error'] = $e->getMessage();
        }
        
        // Test 5: Try to create test file
        $testFile = public_path('uploads/products/ai/test.txt');
        try {
            $written = file_put_contents($testFile, 'test');
            $results['test_file_written'] = $written !== false;
            $results['test_file_bytes'] = $written;
            
            if ($written !== false) {
                chmod($testFile, 0644);
                $results['test_file_exists'] = file_exists($testFile);
                $results['test_file_readable'] = is_readable($testFile);
                $results['test_file_url'] = asset('uploads/products/ai/test.txt');
                @unlink($testFile);
            }
        } catch (\Exception $e) {
            $results['test_file_error'] = $e->getMessage();
        }
        
        // Test 6: Check PHP user
        $results['php_user'] = function_exists('posix_getpwuid') && function_exists('posix_geteuid')
            ? posix_getpwuid(posix_geteuid())['name'] ?? 'unknown'
            : 'unknown';
            
        // Test 7: Check directory permissions
        if (file_exists($aiDir)) {
            $results['ai_dir_perms'] = substr(sprintf('%o', fileperms($aiDir)), -4);
        }
        
        return response()->json([
            'status' => 'success',
            'results' => $results,
        ]);
    }
}
