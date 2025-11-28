<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SSOController extends Controller
{
    private $mainScriptUrl;
    
    public function __construct()
    {
        // Get main script URL from config
        $this->mainScriptUrl = rtrim(env('MAIN_SCRIPT_URL', 'https://dewdropskin.com'), '/');
    }
    
    /**
     * Handle SSO login from main script
     */
    public function ssoLogin(Request $request)
    {
        $token = $request->input('token');
        
        if (!$token) {
            Log::warning('SSO Login: No token provided');
            return redirect('/user/login')->with('error', 'Invalid SSO request');
        }
        
        try {
            // Verify token with main script
            $tokenData = $this->verifyTokenWithMainScript($token, 'login');
            
            if (!$tokenData) {
                Log::warning('SSO Login: Token verification failed', ['token' => substr($token, 0, 10) . '...']);
                return redirect('/user/login')->with('error', 'SSO token expired or invalid');
            }
            
            // Find or create user in account folder
            $user = User::where('username', $tokenData['username'])
                       ->orWhere('email', $tokenData['email'])
                       ->first();
            
            if (!$user) {
                Log::warning('SSO Login: User not found in account system', [
                    'username' => $tokenData['username'],
                    'email' => $tokenData['email']
                ]);
                return redirect('/user/login')->with('error', 'Account not found. Please register first.');
            }
            
            // Verify password matches
            if (!Hash::check($tokenData['password'], $user->password)) {
                Log::warning('SSO Login: Password mismatch', [
                    'username' => $tokenData['username']
                ]);
                return redirect('/user/login')->with('error', 'Account credentials do not match');
            }
            
            // Log the user in with remember me
            Auth::login($user, true);
            
            // Explicitly save the session
            $request->session()->regenerate();
            $request->session()->save();
            
            Log::info('SSO Login Successful', [
                'user_id' => $user->id,
                'username' => $user->username,
                'session_id' => session()->getId()
            ]);
            
            // Return simple success page (not redirect) for iframe
            return response()->view('sso.success', [
                'message' => 'SSO login successful',
                'user' => $user
            ])->cookie('sso_logged_in', '1', 60 * 24 * 7); // 7 days
            
        } catch (\Exception $e) {
            Log::error('SSO Login Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/user/login')->with('error', 'SSO authentication failed');
        }
    }
    
    /**
     * Handle SSO logout from main script
     */
    public function ssoLogout(Request $request)
    {
        $token = $request->input('token');
        
        if (!$token) {
            return redirect('/');
        }
        
        try {
            // Verify token with main script
            $tokenData = $this->verifyTokenWithMainScript($token, 'logout');
            
            if ($tokenData) {
                // Logout the user
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                Log::info('SSO Logout Successful', [
                    'user_id' => $tokenData['user_id'] ?? null
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('SSO Logout Exception', ['error' => $e->getMessage()]);
        }
        
        // Always redirect to main script after logout
        return redirect($this->mainScriptUrl);
    }
    
    /**
     * Verify reverse SSO token (for main script login)
     */
    public function verifyReverseToken(Request $request)
    {
        $token = $request->input('token');
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token required'
            ], 400);
        }
        
        try {
            $cacheKey = "reverse_sso_token_{$token}";
            $tokenData = \Cache::get($cacheKey);
            
            if (!$tokenData) {
                Log::warning('Reverse SSO: Token not found or expired', [
                    'token' => substr($token, 0, 10) . '...'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token'
                ], 401);
            }
            
            // Delete token after verification (one-time use)
            \Cache::forget($cacheKey);
            
            Log::info('Reverse SSO: Token verified successfully', [
                'user_id' => $tokenData['user_id'],
                'username' => $tokenData['username']
            ]);
            
            return response()->json([
                'success' => true,
                'username' => $tokenData['username'],
                'email' => $tokenData['email'],
                'firstname' => $tokenData['firstname'] ?? '',
                'lastname' => $tokenData['lastname'] ?? ''
            ]);
            
        } catch (\Exception $e) {
            Log::error('Reverse SSO: Token verification exception', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Verification failed'
            ], 500);
        }
    }
    
    /**
     * Verify token with main script via HTTP request
     */
    private function verifyTokenWithMainScript($token, $type = 'login')
    {
        try {
            $endpoint = $this->mainScriptUrl . '/api/sso/verify-token';
            
            $response = Http::timeout(10)
                           ->post($endpoint, [
                               'token' => $token,
                               'type' => $type
                           ]);
            
            if ($response->successful() && $response->json('success')) {
                return $response->json('data');
            }
            
            Log::warning('Token verification failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Token verification exception', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
