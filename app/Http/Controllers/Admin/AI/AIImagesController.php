<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AIGeneratedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AIImagesController extends Controller
{
    /**
     * Display gallery of AI generated images
     */
    public function index(Request $request)
    {
        $query = AIGeneratedImage::query()
            ->latest()
            ->with('product');

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Search by filename or prompt
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('filename', 'like', '%' . $request->search . '%')
                  ->orWhere('prompt', 'like', '%' . $request->search . '%');
            });
        }

        $images = $query->paginate(24);

        // Statistics
        $stats = [
            'total' => AIGeneratedImage::count(),
            'product' => AIGeneratedImage::where('type', 'product')->count(),
            'placeholder' => AIGeneratedImage::where('type', 'placeholder')->count(),
            'total_size' => AIGeneratedImage::sum('size'),
        ];

        return view('admin-views.ai.images.index', compact('images', 'stats'));
    }

    /**
     * Delete an AI generated image
     */
    public function destroy($id)
    {
        $image = AIGeneratedImage::findOrFail($id);
        
        // Delete physical file
        $fullPath = public_path($image->path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
        
        // Delete database record
        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => translate('Image deleted successfully'),
        ]);
    }

    /**
     * Bulk delete images
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        $images = AIGeneratedImage::whereIn('id', $ids)->get();
        
        foreach ($images as $image) {
            // Delete physical file
            $fullPath = public_path($image->path);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            
            $image->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => translate('Images deleted successfully'),
            'count' => count($ids),
        ]);
    }

    /**
     * Clear old images (older than X days)
     */
    public function clearOld(Request $request)
    {
        $days = $request->input('days', 30);
        
        $images = AIGeneratedImage::where('created_at', '<', now()->subDays($days))->get();
        
        $count = 0;
        foreach ($images as $image) {
            $fullPath = public_path($image->path);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            $image->delete();
            $count++;
        }

        return response()->json([
            'status' => 'success',
            'message' => translate('Old images cleared successfully'),
            'count' => $count,
        ]);
    }
}
