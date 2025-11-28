<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SSOService
{
    protected $mainScriptUrl;
    protected $ssoSecret;
    
    public function __construct()
    {
        $this->mainScriptUrl = rtrim(config('app.main_script_url', env('MAIN_SCRIPT_URL', 'https://dewdropskin.com')), '/');
        $this->ssoSecret = config('app.sso_secret', env('SSO_SECRET', 'change-this-secret-key'));
    }
    
    /**
     * Generate SSO token for reverse login (account -> main script)
     */
    public function generateReverseLoginToken($user)
    {
        try {
            // Generate secure token
            $token = $this->generateToken($user->id);
            
            // Store token in cache (5 minutes)
            $cacheKey = "reverse_sso_token_{$token}";
            Cache::put($cacheKey, [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'timestamp' => now()->timestamp
            ], now()->addMinutes(5));
            
            Log::info('Reverse SSO Token Generated', [
                'user_id' => $user->id,
                'username' => $user->username,
                'token' => substr($token, 0, 10) . '...'
            ]);
            
            return [
                'success' => true,
                'token' => $token,
                'url' => $this->mainScriptUrl . '/sso/reverse-login?token=' . $token
            ];
            
        } catch (\Exception $e) {
            Log::error('Reverse SSO Token Generation Failed', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to generate SSO token'
            ];
        }
    }
    
    /**
     * Generate secure SSO token
     */
    protected function generateToken($userId)
    {
        $timestamp = now()->timestamp;
        $random = Str::random(32);
        $data = "{$userId}|{$timestamp}|{$random}";
        $signature = hash_hmac('sha256', $data, $this->ssoSecret);
        
        return base64_encode($data . '|' . $signature);
    }
}
