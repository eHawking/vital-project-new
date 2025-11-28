<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ReverseSSOController extends Controller
{
    protected $ssoSecret;
    protected $accountBaseUrl;
    
    public function __construct()
    {
        $this->ssoSecret = config('app.sso_secret', env('SSO_SECRET', 'change-this-secret-key'));
        $this->accountBaseUrl = rtrim(config('app.url'), '/') . '/account';
    }
    
    /**
     * Handle reverse SSO login from account folder
     */
    public function reverseLogin(Request $request)
    {
        $token = $request->get('token');
        
        if (!$token) {
            Log::warning('Reverse SSO: No token provided');
            return redirect('/')->with('error', 'Invalid SSO request');
        }
        
        try {
            // Verify token with account folder
            $response = Http::timeout(5)->post($this->accountBaseUrl . '/api/sso/verify-reverse-token', [
                'token' => $token
            ]);
            
            if (!$response->successful()) {
                Log::error('Reverse SSO: Token verification failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return redirect('/')->with('error', 'SSO verification failed');
            }
            
            $data = $response->json();
            
            if (!$data['success']) {
                Log::warning('Reverse SSO: Invalid token', [
                    'message' => $data['message'] ?? 'Unknown error'
                ]);
                
                return redirect('/')->with('error', 'Invalid or expired SSO token');
            }
            
            // Find or create user in main script
            $user = User::where('username', $data['username'])->first();
            
            if (!$user) {
                Log::error('Reverse SSO: User not found in main script', [
                    'username' => $data['username']
                ]);
                
                return redirect('/')->with('error', 'User not found. Please contact support.');
            }
            
            // Login the user
            auth()->guard('customer')->loginUsingId($user->id, true);
            
            Log::info('Reverse SSO Login Successful', [
                'user_id' => $user->id,
                'username' => $user->username,
                'from' => 'account_folder'
            ]);
            
            // Redirect to home
            return redirect('/')->with('success', 'Welcome! You have been logged in successfully.');
            
        } catch (\Exception $e) {
            Log::error('Reverse SSO Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/')->with('error', 'An error occurred during login. Please try again.');
        }
    }
}
