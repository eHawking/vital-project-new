<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\LoginSetupRepositoryInterface;
use App\Contracts\Repositories\PhoneOrEmailVerificationRepositoryInterface;
use App\Events\EmailVerificationEvent;
use App\Services\FirebaseService;
use App\Services\Web\CustomerAuthService;
use App\Utils\CustomerManager;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Utils\CartManager;
use App\Utils\SMSModule;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use App\Models\Seller;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function __construct(
        private readonly CustomerRepositoryInterface                 $customerRepo,
        private readonly PhoneOrEmailVerificationRepositoryInterface $phoneOrEmailVerificationRepo,
        private readonly LoginSetupRepositoryInterface               $loginSetupRepo,
        private readonly CustomerAuthService                         $customerAuthService,
        private readonly FirebaseService                             $firebaseService,
    )
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function loginView(): View|RedirectResponse
    {
        session()->put('keep_return_url', url()->previous());
        return theme_root_path() == 'default' ? view('web-views.customer-views.auth.login') : redirect()->route('home');
    }

    public function loginSubmit(Request $request): JsonResponse|RedirectResponse
    {
        $firebaseOTPVerification = getWebConfig(name: 'firebase_otp_verification') ?? [];

        if ($firebaseOTPVerification && $firebaseOTPVerification['status'] && empty($request['g-recaptcha-response'])) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('ReCAPTCHA_Failed')
                ]);
            }
            Toastr::error(translate('ReCAPTCHA_Failed'));
            return redirect()->back();
        }
        $loginOptions = json_decode($this->loginSetupRepo->getFirstWhere(params: ['key' => 'login_options'])?->value ?? [], true);
        session()->forget('tempCustomerInfo');
        if (isset($loginOptions['otp_login']) && $loginOptions['otp_login'] && $request['login_type'] == 'otp-login') {
            return $this->loginByOTP(request: $request);
        } elseif (isset($loginOptions['manual_login']) && $loginOptions['manual_login'] && $request['login_type'] == 'manual-login') {
            return $this->loginByEmailOrPhone(request: $request);
        } else {
            if (theme_root_path() == 'default' && session()->has('keep_return_url')) {
                return redirect(session('keep_return_url'));
            }
            return redirect()->route('home');
        }
    }

    public function loginByOTP(Request $request): JsonResponse|RedirectResponse
    {
		
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:6|max:20'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
            }
            $errors = Helpers::validationErrorProcessor($validator);
            foreach ($errors as $value) {
                Toastr::error(translate($value['message']));
            }
            return back();
        }

        $fullPhoneNumber = $request['phone']; 

        
        $normalizedInputPhone = preg_replace('/[^0-9]/', '', $request['phone']);

        
        $lastTenDigits = substr($normalizedInputPhone, -10);

        
        $customer = $this->customerRepo->getByIdentity(filters: ['phone_last_digits' => $lastTenDigits]);

        if (!$customer) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('User_not_found_with_this_phone_number')
                ]);
            }
            Toastr::error(translate('User_not_found_with_this_phone_number'));
            return redirect()->back();
        }
        $phoneNumber = $customer['phone']; 

        
       
        $phoneNumberForFirebase = $fullPhoneNumber;
        if (!str_starts_with($phoneNumberForFirebase, '+')) {
            
            $countryCode = getWebConfig(name: 'country_code') ?? '92'; 
            if (str_starts_with($phoneNumber, $countryCode)) { 
                $phoneNumberForFirebase = '+' . $phoneNumber;
            } else {
               
                $phoneNumberForFirebase = '+' . $countryCode . $phoneNumber;
            }
        }
		
        $OTPIntervalTime = getWebConfig(name: 'otp_resend_time') ?? 60;
        $OTPVerificationData = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => $phoneNumber]);

        if (isset($OTPVerificationData) && Carbon::parse($OTPVerificationData['created_at'])->DiffInSeconds() < $OTPIntervalTime) {
            $time = $OTPIntervalTime - Carbon::parse($OTPVerificationData['created_at'])->DiffInSeconds();
            if (request()->ajax()) {
                return response()->json([
                    'status' => 0,
                    'message' => translate('please_try_again_after_') . $time . ' ' . translate('seconds')
                ]);
            }
            Toastr::error(translate('please_try_again_after_') . $time . ' ' . translate('seconds'));
            return redirect()->back();
        }

        $token = $this->customerAuthService->getCustomerVerificationToken();
        $firebaseOTPVerification = getWebConfig(name: 'firebase_otp_verification') ?? [];
        $errorMsg = translate('OTP_sending_failed');

        if ($firebaseOTPVerification && $firebaseOTPVerification['status']) {
            $firebaseResponse = $this->firebaseService->sendOtp($phoneNumberForFirebase);
            $response = 'error';
            if ($firebaseResponse['status'] == 'success') {
                $token = $firebaseResponse['sessionInfo'];
                $response = $firebaseResponse['status'];
            } else {
                $errorMsg = translate(ucfirst(strtolower($firebaseResponse['errors'])));
            }
        } else {
            $response = $this->customerAuthService->sendCustomerPhoneVerificationToken($phoneNumber, $token);
            $response = $response['response'] ?? 'error';
            if (env('APP_MODE') == 'dev') {
                $response = 'success';
            }
        }

        if ($response == 'success') {
            $this->phoneOrEmailVerificationRepo->updateOrCreate(params: ['phone_or_email' => $phoneNumber], value: [
                'phone_or_email' => $phoneNumber,
                'token' => $token,
            ]);
        }

        if (request()->ajax()) {
            if ($response == 'success') {
                return response()->json([
                    'status' => 'success',
                    'redirect_url' => route('customer.auth.login.verify-account', ['identity' => base64_encode($phoneNumber), 'type' => base64_encode('phone_verification')]),
                    'message' => translate('Please_Verify_that_its_you')
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => $errorMsg
            ]);
        } else {
            if ($response == 'success') {
                return redirect()->route('customer.auth.login.verify-account', ['identity' => base64_encode($phoneNumber), 'type' => base64_encode('phone_verification')]);
            }
            Toastr::error($errorMsg);
            return redirect()->back();
        }
    }

    public function loginByEmailOrPhone(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'user_identity' => 'required',
            'password' => 'required'
        ]);

        $firebaseOTPVerification = getWebConfig(name: 'firebase_otp_verification') ?? [];
        $recaptcha = getWebConfig(name: 'recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1 && (empty($firebaseOTPVerification) || !$firebaseOTPVerification['status'])) {
            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = getWebConfig(name: 'recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(translate('ReCAPTCHA_Failed'));
                            }
                        },
                    ],
                ]);
            } catch (\Exception $exception) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => translate($exception->getMessage()),
                        'redirect_url' => ''
                    ]);
                } else {
                    Toastr::error(translate($exception->getMessage()));
                    return back();
                }
            }
        }

        if (isset($recaptcha) && $recaptcha['status'] == 0 && $firebaseOTPVerification && !$firebaseOTPVerification['status']) {
            if (strtolower($request['default_recaptcha_id_customer_login']) != strtolower(Session('default_recaptcha_id_customer_login'))) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => translate('Captcha_Failed.'),
                        'redirect_url' => ''
                    ]);
                } else {
                    Session::forget('default_recaptcha_id_customer_login');
                    Toastr::error(translate('captcha_failed'));
                    return back();
                }
            }
        }

		
        $user = null;
        $identityInput = $request['user_identity'];

        
        $normalizedInput = preg_replace('/[^0-9]/', '', $identityInput);

        
        if (strlen($normalizedInput) >= 9) {
            $lastTenDigits = substr($normalizedInput, -10);
            
            $user = $this->customerRepo->getByIdentity(filters: ['phone_last_digits' => $lastTenDigits]);
        }

        
        if (!$user) {
            $user = $this->customerRepo->getByIdentity(filters: ['identity' => $identityInput]);
        }
        

       
        if (!$user || (!Hash::check($request->input('password'), $user['password']))) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('credentials_doesnt_match'),
                    'redirect_url' => ''
                ]);
            }
            Toastr::error(translate('credentials_doesnt_match'));
            return back()->withInput();
        }

        $remember = (bool)$request['remember'];

        $maxLoginHit = getWebConfig(name: 'maximum_login_hit') ?? 5;
        $tempBlockTime = getWebConfig(name: 'temporary_login_block_time') ?? 5; //seconds
        if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $tempBlockTime) {
            $time = $tempBlockTime - Carbon::parse($user->temp_block_time)->DiffInSeconds();
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url' => ''
                ]);
            }
            Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
            return back()->withInput();
        }

        $phoneVerification = getLoginConfig(key: 'phone_verification');
        $emailVerification = getLoginConfig(key: 'email_verification');
        $emailVerification = !$phoneVerification ? $emailVerification : 0;

        if ($phoneVerification && $user['phone'] && !$user['is_phone_verified'] || $emailVerification && $user['email'] && !$user['is_email_verified']) {
            $this->getCustomerVerificationCheck($request, $user, $phoneVerification, $emailVerification);
            $verifyType = ($phoneVerification && !$user['is_phone_verified']) ? 'phone_verification' : 'email_verification';
            $verifyIdentity = ($phoneVerification && !$user['is_phone_verified']) ? $user['phone'] : $user['email'];
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $verifyType == 'phone_verification' ? translate('Please_verify_your_phone') : translate('Please_verify_your_email'),
                    'redirect_url' => route('customer.auth.check-verification', ['identity' => base64_encode($verifyIdentity), 'type' => base64_encode($verifyType)]),
                ]);
            }
            return redirect(route('customer.auth.check-verification', ['identity' => base64_encode($verifyIdentity), 'type' => base64_encode($verifyType)]));
        }

        $loginMedia = $user['phone'] ? 'phone' : 'email';
        if (auth('customer')->attempt([$loginMedia => $user[$loginMedia], 'password' => $request['password']], $remember)) {
            if (!$user['is_active']) {
                auth()->guard('customer')->logout();
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => translate('your_account_is_suspended'),
                    ]);
                }
                Toastr::error(translate('your_account_is_suspended'));
                return back()->withInput();
            }
			
			$seller = Seller::where('username', $user['username'])->first();

    if ($seller && $seller->status == 'approved') {
        auth()->guard('seller')->loginUsingId($seller->id);
    }

            CustomerManager::updateCustomerSessionData(userId: auth('customer')->id());
            CartManager::cartListSessionToDatabase();
            $this->customerRepo->updateWhere(params: ['id' => $user['id']], data: $this->customerAuthService->getCustomerLoginDataReset());
            $redirectUrl = $this->customerAuthService->getCustomerLoginPreviousRoute(previousUrl: url()->previous());

            // Trigger SSO login to account folder
            $ssoService = app(\App\Services\SSOService::class);
            $ssoResult = $ssoService->syncLogin($user, $request['password']);
            
            // Check if SSO was successful
            if (!$ssoResult['success']) {
                // SSO failed - logout from main script
                auth()->guard('customer')->logout();
                auth()->guard('seller')->logout();
                
                \Log::error('SSO Sync Failed - Main Login Aborted', [
                    'user_id' => $user['id'],
                    'error' => $ssoResult['error'] ?? 'Unknown error'
                ]);
                
                Toastr::error(translate('Unable to sync with account system. Please try again.'));
                
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => translate('Unable to sync with account system. Please try again.'),
                        'redirect_url' => ''
                    ]);
                }
                return back()->withInput();
            }
            
            // SSO successful - proceed with login
            \Log::info('SSO Sync Successful - Login Complete', [
                'user_id' => $user['id'],
                'sso_url' => $ssoResult['url'] ?? null
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => translate('login_successful'),
                    'redirect_url' => $redirectUrl,
                    'reload' => true,
                    'sso_url' => $ssoResult['url'] ?? null,
                ]);
            }

            Toastr::success(translate('welcome_to') . ' ' . getWebConfig(name: 'company_name') . '!');
            return back();
        } else {
            // Login attempt check start
            if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->diffInSeconds() <= $tempBlockTime) {
                $time = $tempBlockTime - Carbon::parse($user->temp_block_time)->diffInSeconds();
                $ajaxMessage = [
                    'status' => 'error',
                    'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url' => ''
                ];
                Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
            } elseif ($user['is_temp_blocked'] == 1 && Carbon::parse($user['temp_block_time'])->diffInSeconds() >= $tempBlockTime) {

                $this->customerRepo->updateWhere(params: ['id' => $user['id']], data: $this->customerAuthService->getCustomerLoginDataReset());
                $ajaxMessage = [
                    'status' => 'error',
                    'message' => translate('credentials_doesnt_match'),
                    'redirect_url' => ''
                ];
                Toastr::error(translate('credentials_doesnt_match'));

            } elseif ($user['login_hit_count'] >= $maxLoginHit && $user['is_temp_blocked'] == 0) {
                $this->customerRepo->updateWhere(params: ['id' => $user['id']], data: [
                    'is_temp_blocked' => 1,
                    'temp_block_time' => now(),
                    'updated_at' => now()
                ]);

                $time = $tempBlockTime - Carbon::parse($user['temp_block_time'])->diffInSeconds();

                $ajaxMessage = [
                    'status' => 'error',
                    'message' => translate('too_many_attempts._please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                    'redirect_url' => ''
                ];
                Toastr::error(translate('too_many_attempts._please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
            } else {
                $ajaxMessage = [
                    'status' => 'error',
                    'message' => translate('credentials_doesnt_match'),
                    'redirect_url' => ''
                ];
                Toastr::error(translate('credentials_doesnt_match'));
            }
            $user = $this->customerRepo->getByIdentity(filters: ['identity' => $request['user_identity']]);
            $this->customerRepo->updateWhere(params: ['id' => $user['id']], data: [
                'login_hit_count' => ($user['login_hit_count'] + 1),
                'updated_at' => now()
            ]);
            // Login attempt check end

            if ($request->ajax()) {
                return response()->json($ajaxMessage);
            }
            return back()->withInput();
        }
    }


    public function getCustomerVerificationCheck($request, $user, $phoneVerification, $emailVerification)
    {
        $response = null;
        $token = $this->customerAuthService->getCustomerVerificationToken();
        $firebaseOTPVerification = getWebConfig(name: 'firebase_otp_verification') ?? [];

        if ($phoneVerification && $user['phone'] && !$user['is_phone_verified'] && $firebaseOTPVerification && $firebaseOTPVerification['status']) {
            $response = $this->firebaseService->sendOtp($user['phone']);
            if ($response['status'] == 'success') {
                $token = $response['sessionInfo'];
            }
        } else if ($phoneVerification && $user['phone'] && !$user['is_phone_verified']) {
            $response = $this->customerAuthService->sendCustomerPhoneVerificationToken($user['phone'], $token);
        } else if ($emailVerification && $user['email'] && !$user['is_email_verified']) {
            $response = $this->customerAuthService->sendCustomerEmailVerificationToken($user, $token);
        }

        if ($response && $response['status'] == 'error') {
            Toastr::error($response['message']);
            return back();
        }

        if ($response && $response['status'] == 'success') {
            $verifyIdentify = $phoneVerification && !$user['is_phone_verified'] ? $user?->phone : $user?->email;
            $this->phoneOrEmailVerificationRepo->updateOrCreate(params: ['phone_or_email' => $verifyIdentify], value: [
                'phone_or_email' => $verifyIdentify,
                'token' => $token,
            ]);
        }
        return $response;
    }

    public function loginVerifyPhone(Request $request): View|JsonResponse
    {
        $token = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => base64_decode($request['identity'])]);
        $otpResendTime = getWebConfig(name: 'otp_resend_time') > 0 ? getWebConfig(name: 'otp_resend_time') : 0;
        if ($token) {
            $token_time = Carbon::parse($token['created_at']);
            $convertTime = $token_time->addSeconds($otpResendTime);
            $getTimeInSecond = $convertTime > Carbon::now() ? Carbon::now()->diffInSeconds($convertTime) : 0;
        } else {
            $getTimeInSecond = 0;
        }
        $userVerify = 0;

        return view(VIEW_FILE_NAMES['customer_auth_verify_otp_login'], [
            'userVerify' => $userVerify,
            'identity' => $request['identity'],
            'otpResendTime' => $otpResendTime,
            'getTimeInSecond' => $getTimeInSecond,
            'otpFromType' => $request['action'] ?? '',
        ]);
    }

    public function verifyAccount(Request $request): View|RedirectResponse|JsonResponse
    {
        if (!$request->has('token') || empty($request['token'])) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('The_token_field_is_required'),
                ]);
            }
            Toastr::error(translate('The_token_field_is_required'));
            return redirect()->back();
        }

        $phoneVerification = getLoginConfig(key: 'phone_verification');
        $identity = base64_decode($request['identity']);
        $firebaseOTPVerification = getWebConfig(name: 'firebase_otp_verification') ?? [];

        if ($firebaseOTPVerification && $firebaseOTPVerification['status'] && empty($request['g-recaptcha-response'])) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('ReCAPTCHA_Failed')
                ]);
            }
            Toastr::error(translate('ReCAPTCHA_Failed'));
            return redirect()->back();
        }

        $maxOTPHit = getWebConfig(name: 'maximum_otp_hit') ?? 5;
        $maxOTPHitTime = getWebConfig(name: 'otp_resend_time') ?? 60;// seconds
        $tempBlockTime = getWebConfig(name: 'temporary_block_time') ?? 600; // seconds
        $verificationData = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => $identity]);
        $OTPVerificationData = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => $identity, 'token' => $request['token']]);

        if ($verificationData) {
            $validateBlock = 0;
            $errorMsg = translate('OTP_is_not_matched');
            if (isset($verificationData->temp_block_time) && Carbon::parse($verificationData->temp_block_time)->DiffInSeconds() <= $tempBlockTime) {
                $time = $tempBlockTime - Carbon::parse($verificationData->temp_block_time)->DiffInSeconds();
                $validateBlock = 1;
                $errorMsg = translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans();
            } else if ($verificationData['is_temp_blocked'] == 1 && Carbon::parse($verificationData['updated_at'])->DiffInSeconds() >= $tempBlockTime) {
                $this->phoneOrEmailVerificationRepo->updateOrCreate(params: ['phone_or_email' => $identity], value: [
                    'otp_hit_count' => 0,
                    'is_temp_blocked' => 0,
                    'temp_block_time' => null,
                ]);
                $validateBlock = 1;
                $errorMsg = translate('OTP_is_not_matched');
            } else if ($verificationData['otp_hit_count'] >= $maxOTPHit && Carbon::parse($verificationData['updated_at'])->DiffInSeconds() < $maxOTPHitTime && $verificationData['is_temp_blocked'] == 0) {
                $this->phoneOrEmailVerificationRepo->updateOrCreate(params: ['phone_or_email' => $identity], value: [
                    'is_temp_blocked' => 1,
                    'temp_block_time' => now(),
                ]);

                $validateBlock = 1;
                $time = $tempBlockTime - Carbon::parse($verificationData['temp_block_time'])->DiffInSeconds();
                $errorMsg = translate('Too_many_attempts.') .' '. translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans();
            }
            $verificationData = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => $identity]);
            $this->phoneOrEmailVerificationRepo->updateOrCreate(params: ['phone_or_email' => $identity], value: [
                'otp_hit_count' => ($verificationData['otp_hit_count'] + 1),
                'updated_at' => now(),
            ]);
            if ($validateBlock) {
                if (request()->ajax()) {
                    return response()->json([
                        'status' => 0,
                        'message' => $errorMsg
                    ]);
                }
                Toastr::error($errorMsg);
                return redirect()->back();
            }

            $tokenVerifyStatus = false;
            if ($firebaseOTPVerification && $firebaseOTPVerification['status']) {
                $firebaseVerify = $this->firebaseService->verifyOtp($verificationData['token'], $verificationData['phone_or_email'], $request['token']);
                $tokenVerifyStatus = (boolean)($firebaseVerify['status'] == 'success');
                if (!$tokenVerifyStatus) {
                    $verificationData = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => $identity]);
                    $this->phoneOrEmailVerificationRepo->updateOrCreate(params: ['phone_or_email' => $identity], value: [
                        'otp_hit_count' => ($verificationData['otp_hit_count'] + 1),
                        'updated_at' => now(),
                        'temp_block_time' => null,
                    ]);
                    Toastr::error(translate(strtolower($firebaseVerify['errors'])));
                    return back();
                }
            } else {
                $tokenVerifyStatus = (boolean)$OTPVerificationData;
            }

            if ($tokenVerifyStatus) {
                if (isset($OTPVerificationData->temp_block_time) && \Illuminate\Support\Carbon::parse($OTPVerificationData->temp_block_time)->DiffInSeconds() <= $tempBlockTime) {
                    $time = $tempBlockTime - Carbon::parse($OTPVerificationData->temp_block_time)->DiffInSeconds();
                    $errorMsg = translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans();
                    if (request()->ajax()) {
                        return response()->json([
                            'status' => 0,
                            'message' => $errorMsg
                        ]);
                    }
                    Toastr::error($errorMsg);
                    return redirect()->back();
                }

                $user = $this->customerRepo->getByIdentity(filters: ['identity' => $identity]);
                if ($user) {
                    $this->customerRepo->updateWhere(params: ['phone' => $identity], data: [
                        'is_phone_verified' => 1,
                    ]);
                    $this->phoneOrEmailVerificationRepo->delete(params: ['phone_or_email' => $identity]);
                    if (isset($request['type']) && $request['type'] == 'password-reset') {
                        auth('customer')->login($user);
                        CustomerManager::updateCustomerSessionData(userId: auth('customer')->id());
                        return redirect()->route('home');
                    } else {
                        auth('customer')->login($user);
                        CustomerManager::updateCustomerSessionData(userId: auth('customer')->id());
                        Toastr::success(translate('login_successful'));
                        return redirect()->route('home');
                    }
                } else {
                    // If user is not found, handle the case (e.g., return an error)
                    $errorMsg = translate('User_not_found');
                    if (request()->ajax()) {
                        return response()->json([
                            'status' => 0,
                            'message' => $errorMsg
                        ]);
                    }
                    Toastr::error($errorMsg);
                    return redirect()->back();
                }
            }
        }

        $errorMsg = translate('OTP_is_not_matched');
        if (request()->ajax()) {
            return response()->json([
                'status' => 0,
                'message' => $errorMsg
            ]);
        }
        Toastr::error($errorMsg);
        return redirect()->back();
    }

    

    public function resendOTPCode(Request $request): JsonResponse|RedirectResponse
    {
        $user = $this->customerRepo->getFirstWhere(params: ['phone' => base64_decode($request['identity'])]);
        if (!$user) {
            Toastr::error(translate('User_not_found'));
            return back();
        }
        $token = $this->phoneOrEmailVerificationRepo->getFirstWhere(params: ['phone_or_email' => $user['phone']]);
        $otpResendTime = getWebConfig(name: 'otp_resend_time') > 0 ? getWebConfig(name: 'otp_resend_time') : 0;

        // Time Difference in Minutes
        if ($token) {
            $tokenTime = Carbon::parse($token->created_at);
            $addTime = $tokenTime->addSeconds($otpResendTime);
            $timeDifferance = $addTime > Carbon::now() ? Carbon::now()->diffInSeconds($addTime) : 0;
        } else {
            $timeDifferance = 0;
        }

        $newTokenGenerate = (env('APP_MODE') == 'live') ? rand(100000, 999999) : 123456;
        if ($timeDifferance == 0) {
            if ($token) {
                $token->token = $newTokenGenerate;
                $token->otp_hit_count = 0;
                $token->is_temp_blocked = 0;
                $token->temp_block_time = null;
                $token->created_at = now();
                $token->save();
            } else {
                $new_token = new PhoneOrEmailVerification();
                $new_token->phone_or_email = $user['email'];
                $new_token->token = $newTokenGenerate;
                $new_token->created_at = now();
                $new_token->updated_at = now();
                $new_token->save();
            }

            $phone_verification = getLoginConfig(key:'phone_verification');
            $email_verification = getLoginConfig(key:'email_verification');
            if ($phone_verification && !$user->is_phone_verified) {
                SMSModule::sendCentralizedSMS($user['phone'], $newTokenGenerate);
            }

            if ($email_verification && !$user['is_email_verified']) {
                $emailServicesSmtp = getWebConfig(name: 'mail_config');
                if ($emailServicesSmtp['status'] == 0) {
                    $emailServicesSmtp = getWebConfig(name: 'mail_config_sendgrid');
                }
                if ($emailServicesSmtp['status'] == 1) {
                    try {
                        $data = [
                            'userName' => $user['f_name'],
                            'subject' => translate('registration_Verification_Code'),
                            'title' => translate('registration_Verification_Code'),
                            'verificationCode' => $newTokenGenerate,
                            'userType' => 'customer',
                            'templateName' => 'registration-verification',
                        ];
                        event(new EmailVerificationEvent(email: $user['email'], data: $data));
                    } catch (\Exception $exception) {
                        return response()->json([
                            'status' => "0",
                        ]);
                    }
                }
            }
            if (request()->ajax()) {
                return response()->json(['status' => 1, 'new_time' => $otpResendTime]);
            } else {
                Toastr::success(translate('Resend_OTP_successfully'));
                return back();
            }
        } else {
            if (request()->ajax()) {
                return response()->json(['status' => 0]);
            } else {
                Toastr::error(translate('Resend_OTP_unsuccessful'));
                return back();
            }
        }
    }

    public function firebaseAuthVerify(Request $request): View|RedirectResponse
    {
        return view(VIEW_FILE_NAMES['customer_auth_verify_firebase_auth_verify'], [
            'phone' => $request['phone'],
            'session' => $request['session'],
        ]);
    }

    public function firebaseAuthVerifySubmit(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'phone' => 'required',
            'code' => 'required',
        ]);

        $firebaseOTPVerification = getWebConfig(name: 'fcm_credentials');
        $webApiKey = $firebaseOTPVerification ? $firebaseOTPVerification['apiKey'] : '';

        $response = Http::post('https://identitytoolkit.googleapis.com/v1/accounts:signInWithPhoneNumber?key=' . $webApiKey, [
            'sessionInfo' => $request['session'],
            'phoneNumber' => '+' . $request['phone'],
            'code' => $request['code'],
        ]);

        $responseData = $response->json();
        if (isset($responseData['error'])) {
            Toastr::error(translate(strtolower($responseData['error']['message'])));
            return back();
        }
        
        // Check if user already exists based on phone number
        $user = $this->customerRepo->getFirstWhere(params: ['phone' => $responseData['phoneNumber']]);

        if ($user) { //
            // User exists, update and log in
            $this->customerRepo->updateWhere(params: ['phone' => $responseData['phoneNumber']], data: ['is_phone_verified' => 1]);
            auth('customer')->login($user);
            return redirect()->route('home');
        } else { //
            // User does not exist, do not create or log in
            Toastr::error(translate('User_not_found_with_this_phone_number'));
            return back();
        }
    }
}
