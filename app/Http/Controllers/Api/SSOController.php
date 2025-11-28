<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SSOService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SSOController extends Controller
{
    protected $ssoService;
    
    public function __construct(SSOService $ssoService)
    {
        $this->ssoService = $ssoService;
    }
    
    /**
     * Verify SSO token (called from account folder)
     */
    public function verifyToken(Request $request): JsonResponse
    {
        $token = $request->input('token');
        $type = $request->input('type', 'login');
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token is required'
            ], 400);
        }
        
        if ($type === 'login') {
            $data = $this->ssoService->verifyLoginToken($token);
        } else {
            $data = $this->ssoService->verifyLogoutToken($token);
        }
        
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
