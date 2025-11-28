<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Lib\Intended;
use App\Constants\Status;
use App\Models\UserExtra;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm(Request $request)
    {
		// Check if ref and position parameters exist
		if (!$request->has('ref') || !$request->has('position')) {
			$notify[] = ['error', 'Referral link is required. Please use a valid referral link to register.'];
			return redirect()->route('home')->withNotify($notify);
		}
		
        $pageTitle = "Register";
        
        // Clean and normalize the referral username
        $refUsername = trim($request->ref);
        // Remove any URL protocols or www
        $refUsername = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $refUsername);
        $refUsername = rtrim($refUsername, '/');
        
        // Normalize position parameter
        $position = strtolower(trim($request->position));
        if ($position !== 'left' && $position !== 'right') {
            $position = ($position === '1' || $position === 'l') ? 'left' : 'right';
        }
        
        if ($refUsername && $position) {

            $refUser = User::where('username', $refUsername)->first();
            if ($refUser == null) {
                $notify[] = ['error', 'Invalid Referral link. The referrer username "' . $refUsername . '" does not exist.'];
                return redirect()->route('home')->withNotify($notify);
            }
			
			if ($refUser->plan_id == 0 && $refUser->is_btp == 0 && $refUser->is_share == 0) {
            $notify[] = [
                'error',
                'This user does not have an active BTP or DSP plan. You need to purchase the BTP package before you can refer others. <a href="https://dewdropskin.com/product/dewdropskin-build-team-product-btp-promo-team-booster-sodB4P"  target="_blank">Buy BTP Package</a>'
            ];
            return back()->withNotify($notify);
        }

            // Convert position string to number for getPosition function
            $positionNum = $position == 'left' ? 1 : 2;
            $pos = getPosition($refUser->id, $positionNum);

            $referrer = User::find($pos['pos_id']);

            if ($pos['position'] == 1)
                $getPosition = 'Left';
            else {
                $getPosition = 'Right';
            }

            $joining = "<span class='help-block2'><strong class='text--success'>You are joining under $referrer->username ($referrer->firstname $referrer->lastname) at $getPosition  </strong></span>";
        } else {
            $refUser = null;
            $joining = null;
            $positionNum = null;
            $pos = null;
            $referrer = null;
            $getPosition = null;
        }

        Intended::identifyRoute();
        // Pass positionNum as position for view compatibility
        return view('Template::user.auth.register', compact('pageTitle', 'pos', 'refUser', 'referrer', 'getPosition', 'joining'))->with('position', $positionNum);
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'referBy'      => 'required|string|max:160',
            'position'      => 'required|integer',
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required' => 'The last name field is required'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if (!gs('registration')) {
            return back();
        }

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }



    protected function create(array $data)
    {
        $userCheck = User::where('username', $data['referBy'])->first();
        $pos = getPosition($userCheck->id, $data['position']);
		
        $last_dds = User::where('username', 'LIKE', 'dds%')->count();

        //User Create
        $user            = new User();
        $user->ref_by       = $userCheck->id;
        $user->pos_id       = $pos['pos_id'];
        $user->position     = $pos['position'];
        $user->email     = strtolower($data['email']);
		$user->username     = 'dds000' . ++$last_dds;
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];
        $user->password  = Hash::make($data['password']);
        $user->kv = gs('kv') ? Status::NO : Status::YES;
        $user->ev = gs('ev') ? Status::NO : Status::YES;
        $user->sv = gs('sv') ? Status::NO : Status::YES;
        $user->ts = Status::DISABLE;
        $user->tv = Status::ENABLE;
        $user->save();
		
		// Insert user data into external MySQL store
        DB::connection('mysql_store')
        ->table('users')
        ->insert([
        'username'       => $user->username,
		'name'           => $user->firstname . ' ' . $user->lastname,
        'f_name'         => $user->firstname,
        'l_name'         => $user->lastname,
        'email'          => $user->email,
        'phone'          => null,
        'password'       => Hash::make($data['password']),
        'street_address' => null,
        'country'        => null,
        'city'           => null,
        'zip'            => null,
        'created_at'     => now(),
        'updated_at'     => now(),
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();


        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email', $request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile', $request->mobile)->where('dial_code', $request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->cnicnumber) {
            // Sanitize to digits and check against stored 13-digit numeric value
            $cnic = preg_replace('/\D/', '', $request->cnicnumber);
            $exist['data'] = User::where('cnicnumber', $cnic)->exists();
            $exist['type'] = 'cnic';
            $exist['field'] = 'CNIC Number';
        }
        if ($request->username) {
            $exist['data'] = User::where('username', $request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered(Request $request, $user)
    {
        $user_extras = new UserExtra();
        $user_extras->user_id = $user->id;
        $user_extras->save();
        updateFreeCount($user->id);
        
        // Generate reverse SSO token to auto-login to main script
        $ssoService = app(\App\Services\SSOService::class);
        $ssoResult = $ssoService->generateReverseLoginToken($user);
        
        if ($ssoResult['success']) {
            // Store SSO URL in flash session for one-time use
            session()->flash('reverse_sso_url', $ssoResult['url']);
            // Also put in regular session as backup
            session()->put('reverse_sso_url_backup', $ssoResult['url']);
            
            \Log::info('Reverse SSO Initiated After Registration', [
                'user_id' => $user->id,
                'sso_url' => $ssoResult['url'],
                'session_id' => session()->getId()
            ]);
        }
        
        return to_route('user.home');
    }
}