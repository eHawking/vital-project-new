<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SSOService
{
    private $accountBaseUrl;
    private $ssoSecret;
    
    public function __construct()
    {
        $this->accountBaseUrl = rtrim(config('app.url'), '/') . '/account';
        $this->ssoSecret = config('app.sso_secret', env('SSO_SECRET', 'change-this-secret-key'));
    }
    
    /**
     * Authenticate user in account folder after main script login
     */
    public function syncLogin($user, $password)
    {
        try {
            // Generate secure SSO token
            $token = $this->generateSSOToken($user->id);
            
            // Store token temporarily (5 minutes)
            $cacheKey = "sso_token_{$token}";
            Cache::put($cacheKey, [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'password' => $password, // Plain text password for verification
                'timestamp' => now()->timestamp
            ], now()->addMinutes(5));
            
            // Verify cache was stored
            if (!Cache::has($cacheKey)) {
                Log::error('SSO Login Failed - Cache storage failed', [
                    'user_id' => $user->id,
                    'cache_driver' => config('cache.default')
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Failed to store SSO token in cache'
                ];
            }
            
            Log::info('SSO Login Token Generated and Cached', [
                'user_id' => $user->id,
                'username' => $user->username,
                'token' => substr($token, 0, 10) . '...',
                'cache_key' => $cacheKey
            ]);
            
            $ssoUrl = $this->accountBaseUrl . '/user/sso-login?token=' . $token;
            
            Log::info('SSO URL Created', [
                'url' => $ssoUrl,
                'user_id' => $user->id
            ]);
            
            return [
                'success' => true,
                'token' => $token,
                'url' => $ssoUrl
            ];
            
        } catch (\Exception $e) {
            Log::error('SSO Login Exception', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'SSO authentication exception: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Logout user from account folder when main script logout
     */
    public function syncLogout($userId)
    {
        try {
            // Generate logout token
            $token = $this->generateSSOToken($userId);
            
            // Store logout token temporarily (2 minutes)
            Cache::put("sso_logout_{$token}", [
                'user_id' => $userId,
                'timestamp' => now()->timestamp
            ], now()->addMinutes(2));
            
            Log::info('SSO Logout Token Generated', [
                'user_id' => $userId,
                'token' => substr($token, 0, 10) . '...'
            ]);
            
            return [
                'success' => true,
                'token' => $token,
                'url' => $this->accountBaseUrl . '/user/sso-logout?token=' . $token
            ];
            
        } catch (\Exception $e) {
            Log::error('SSO Logout Failed', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'SSO logout failed'
            ];
        }
    }
    
    /**
     * Verify SSO token from account folder
     */
    public function verifyLoginToken($token)
    {
        $cacheKey = "sso_token_{$token}";
        $data = Cache::get($cacheKey);
        
        if (!$data) {
            Log::warning('SSO Token Not Found or Expired', ['token' => substr($token, 0, 10) . '...']);
            return null;
        }
        
        // Verify token age (max 5 minutes)
        if ((now()->timestamp - $data['timestamp']) > 300) {
            Cache::forget($cacheKey);
            Log::warning('SSO Token Expired', ['token' => substr($token, 0, 10) . '...']);
            return null;
        }
        
        // Delete token after verification (one-time use)
        Cache::forget($cacheKey);
        
        Log::info('SSO Token Verified', [
            'user_id' => $data['user_id'],
            'username' => $data['username']
        ]);
        
        return $data;
    }
    
    /**
     * Verify SSO logout token
     */
    public function verifyLogoutToken($token)
    {
        $cacheKey = "sso_logout_{$token}";
        $data = Cache::get($cacheKey);
        
        if (!$data) {
            return null;
        }
        
        // Delete token after verification
        Cache::forget($cacheKey);
        
        return $data;
    }
    
    /**
     * Generate secure SSO token
     */
    private function generateSSOToken($userId)
    {
        $random = Str::random(40);
        $timestamp = now()->timestamp;
        $signature = hash_hmac('sha256', $userId . $timestamp . $random, $this->ssoSecret);
        
        return base64_encode($userId . '|' . $timestamp . '|' . $random . '|' . $signature);
    }
    
    /**
     * Check if account folder is accessible
     */
    public function isAccountFolderAccessible()
    {
        try {
            $response = Http::timeout(3)->get($this->accountBaseUrl);
            return $response->successful() || $response->status() === 302;
        } catch (\Exception $e) {
            Log::warning('Account Folder Not Accessible', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
