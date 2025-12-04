<?php

namespace App\Http\Controllers\User;

use App\Models\Form;
use App\Models\User;
use App\Models\BvLog;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Product;
use App\Constants\Status;
use App\Lib\FormProcessor;
use App\Models\Withdrawal;
use App\Models\DeviceToken;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PrimarySeller;
use App\Models\PrimaryShop;
use App\Models\Rank;
use App\Models\UserReward;
use App\Models\BbsUser;

class UserController extends Controller
{
   public function home()
{
    $userId = auth()->id();

    // Fetch rank and reward details
    $rankData = DB::table('users as u')
        ->leftJoin('ranks as r', 'u.pairs', '>=', 'r.requirement')
        ->leftJoin('ranks as nr', 'r.next_rank', '=', 'nr.name')
        ->select(
            'u.pairs',
            'r.name as current_rank',
            'r.image as current_rank_image',
            'r.reward as current_reward',
            'r.reward_image as current_reward_image',
            'r.budget as current_budget',
            'r.requirement as current_requirement',
            'nr.name as next_rank',
            'nr.requirement as next_requirement',
            'nr.reward as next_reward',
            'nr.reward_image as next_reward_image',
            'nr.budget as next_budget',
            'nr.image as next_rank_image'
        )
        ->where('u.id', $userId)
        ->orderBy('r.requirement', 'desc')
        ->first();

    // If no rank is found, set defaults for the first rank
    if (!$rankData || !$rankData->current_rank) {
        $firstRank = Rank::orderBy('requirement', 'asc')->first();
        $pairs = auth()->user()->pairs ?? 0;
        $currentRank = 'User';
        $currentRankImage = 'User-Rank.png';
        $currentReward = 'N/A';
        $currentBudget = 'N/A';
        $nextRank = $firstRank->name ?? 'Master';
        $nextRequirement = $firstRank->requirement ?? 1;
        $nextReward = $firstRank->reward ?? 'Pair Earning';
        $nextRewardImage = $firstRank->reward_image ?? '1 Pair.png';
        $nextBudget = $firstRank->budget ?? null;
        $nextRankImage = $firstRank->image ?? 'Master-Rank.png';
    } else {
        $pairs = $rankData->pairs;
        $currentRank = $rankData->current_rank;
        $currentRankImage = $rankData->current_rank_image;
        $currentReward = $rankData->current_reward;
        $currentBudget = $rankData->current_budget;
        $nextRank = $rankData->next_rank;
        $nextRequirement = $rankData->next_requirement;
        $nextReward = $rankData->next_reward;
        $nextRewardImage = $rankData->next_reward_image;
        $nextBudget = $rankData->next_budget;
        $nextRankImage = $rankData->next_rank_image;
    }

    $pairsRemaining = $nextRequirement > 0 ? max(0, $nextRequirement - $pairs) : 0;
    $progress = $nextRequirement > 0 ? intval(($pairs / $nextRequirement) * 100) : 100;

    // Existing dashboard data
    $pageTitle        = 'Dashboard';
    $totalDeposit     = Deposit::where('user_id', auth()->id())->where('status', 1)->sum('amount');
    $totalWithdraw    = Withdrawal::where('user_id', auth()->id())->where('status', 1)->sum('amount');
    $completeWithdraw = Withdrawal::where('user_id', auth()->id())->where('status', 1)->count();
    $pendingWithdraw  = Withdrawal::where('user_id', auth()->id())->where('status', 2)->count();
    $totalRef         = User::where('ref_by', auth()->id())->count();
    $totalBvCut       = BvLog::where('user_id', auth()->id())->where('trx_type', '-')->sum('amount');

    // Pass all data to the view
    return view('Template::user.dashboard', compact(
        'pageTitle',
        'totalDeposit',
        'totalWithdraw',
        'completeWithdraw',
        'pendingWithdraw',
        'totalRef',
        'totalBvCut',
        'pairs',
        'currentRank',
        'currentRankImage',
        'currentReward',
        'currentBudget',
        'nextRank',
        'nextRequirement',
        'nextReward',
        'nextRewardImage',
        'nextBudget',
        'nextRankImage',
        'pairsRemaining',
        'progress'
    ));
}

	public function showRewards()
{
    $user = auth()->user();
    $pageTitle = 'Ranks & Rewards';

    // 1. Fetch data for Current/Next Rank Card
    $rankData = DB::table('users as u')
        ->leftJoin('ranks as r', 'u.pairs', '>=', 'r.requirement')
        ->leftJoin('ranks as nr', 'r.next_rank', '=', 'nr.name')
        ->select(
            'r.name as current_rank', 'r.image as current_rank_image',
            'nr.name as next_rank', 'nr.image as next_rank_image', 'nr.reward as next_reward'
        )
        ->where('u.id', $user->id)
        ->orderBy('r.requirement', 'desc')
        ->first();

    if (!$rankData || !$rankData->current_rank) {
        $firstRank = Rank::orderBy('requirement', 'asc')->first();
        $currentRankName = 'No Rank';
        $currentRankImage = 'default.png';
        $nextRankName = $firstRank->name ?? 'N/A';
        $nextRankImage = $firstRank->image ?? 'default.png';
        $nextReward = $firstRank->reward ?? 'N/A';
    } else {
        $currentRankName = $rankData->current_rank;
        $currentRankImage = $rankData->current_rank_image;
        $nextRankName = $rankData->next_rank;
        $nextRankImage = $rankData->next_rank_image;
        $nextReward = $rankData->next_reward;
    }

    // 2. Fetch data for Rewards Summary Card using UserReward Model
    $totalRewardsEarned = UserReward::where('user_id', $user->id)->count();
    $pendingRewards = UserReward::where('user_id', $user->id)->where('status', 'pending')->count();
    $deliveredRewards = UserReward::where('user_id', $user->id)->where('status', 'delivered')->count();

    // 3. Fetch data for BBS ID Card using Rank and UserReward Models
    $bbsUser = BbsUser::where('user_id', $user->id)->first();
    $userRewardsRanks = UserReward::where('user_id', $user->id)->pluck('rank_id');
    $isEmperor = Rank::where('name', 'Emperor')->whereIn('id', $userRewardsRanks)->exists();

    // 4. Fetch data for Rewards History Tab using UserReward Model with Rank relationship
    $rewardsHistory = UserReward::where('user_id', $user->id)
        ->with('rank') // Eager load the rank details
        ->orderBy('created_at', 'desc')
        ->get();

    // Fetch all ranks for the "All Ranks" tab using the Rank model
    $ranks = Rank::orderBy('requirement', 'asc')->get();
    $userPairs = $user->pairs ?? 0;

    return view('Template::user.rewards', compact(
        'pageTitle', 'ranks', 'userPairs',
        'currentRankName', 'currentRankImage', 'nextRankName', 'nextRankImage', 'nextReward',
        'totalRewardsEarned', 'pendingRewards', 'deliveredRewards',
        'bbsUser', 'isEmperor',
        'rewardsHistory'
    ));
}




    public function brightFuture()
    {
        $pageTitle = 'Bright Future Plan';
        $user = auth()->user();
        $maxCap = 400000;
        $receivedAmount = $user->bright_future_balance;
        $remainingAmount = max(0, $maxCap - $receivedAmount);
        $progress = min(100, ($receivedAmount / $maxCap) * 100);

        $transactions = Transaction::where('user_id', $user->id)
            ->where('remark', 'bright_future_profit')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('Template::user.bright_future', compact(
            'pageTitle',
            'user',
            'receivedAmount',
            'remainingAmount',
            'progress',
            'transactions',
            'maxCap'
        ));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::where('user_id', auth()->id())->distinct('remark')->orderBy('remark')->whereNotNull('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Document';
        abort_if($user->kv == Status::VERIFIED, 403);
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->firstOrFail();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData                   = $formProcessor->processFormData($request, $formData);
        $user->kyc_data             = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv                   = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        // Sanitize CNIC to digits only for validation and storage
        if ($request->has('cnicnumber')) {
            $request->merge([
                'cnicnumber' => preg_replace('/\D/', '', (string) $request->input('cnicnumber')),
            ]);
        }

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            // Pakistani states are selected from dropdown on the client; accept any string here
            'state'        => ['required','string','max:100'],
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
            // CNIC: 13 digits, unique across users table
            'cnicnumber'   => ['required','regex:/^[0-9]{13}$/','unique:users,cnicnumber'],
        ]);


       

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
       


        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->cnicnumber   = $request->cnicnumber; // already sanitized to 13 digits
       
        $user->country_name = @$request->country;
        $user->dial_code    = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'quantity'   => 'required|integer|gt:0',
            'product_id' => 'required|integer|gt:0'
        ]);

        $product = Product::hasCategory()->active()->find($request->product_id);

        if (!$product) {
            $notify[] = ['error', 'Product not found'];
            return back()->withNotify($notify);
        }

        if ($request->quantity > $product->quantity) {
            $notify[] = ['error', 'Requested quantity is not available in stock'];
            return back()->withNotify($notify);
        }
        $user       = auth()->user();
        $totalPrice = $product->price * $request->quantity;
        if ($user->balance < $totalPrice) {
            $notify[] = ['error', 'Balance is not sufficient'];
            return back()->withNotify($notify);
        }
        $user->balance -= $totalPrice;
        $user->save();

        $product->quantity -= $request->quantity;
        $product->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $totalPrice;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = $product->name . ' item purchase';
        $transaction->trx          = getTrx();
        $transaction->save();

        $order              = new Order();
        $order->user_id     = $user->id;
        $order->product_id  = $product->id;
        $order->quantity    = $request->quantity;
        $order->price       = $product->price;
        $order->total_price = $totalPrice;
        $order->trx         = $transaction->trx;
        $order->status      = 0;
        $order->save();

        notify($user, 'ORDER_PLACED', [
            'product_name' => $product->name,
            'quantity'     => $request->quantity,
            'price'        => showAmount($product->price, currencyFormat: false),
            'total_price'  => showAmount($totalPrice, currencyFormat: false),
            'trx'          => $transaction->trx,
        ]);

        $notify[] = ['success', 'Order placed successfully'];
        return back()->withNotify($notify);
    }

    public function indexTransfer()
    {
        $pageTitle = 'Balance Transfer';
        return view('Template::user.balanceTransfer', compact('pageTitle'));
    }

    public function searchUser(Request $request)
    {
        $transUser = User::where('username', $request->username)->orwhere('email', $request->username)->count();
        if ($transUser == 1) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function balanceTransfer(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'amount'   => 'required|numeric|min:0',
        ]);

        $general = gs();

        $user      = User::find(auth()->id());
        $transUser = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if ($transUser == '') {
            $notify[] = ['error', 'Username not found'];
            return back()->withNotify($notify);
        }
        if ($transUser->username == $user->username) {
            $notify[] = ['error', 'Balance transfer not possible in your own account'];
            return back()->withNotify($notify);
        }
        if ($transUser->email == $user->email) {
            $notify[] = ['error', 'Balance transfer not possible in your own account'];
            return back()->withNotify($notify);
        }

        $charge = $general->bal_trans_fixed_charge + (($request->amount * $general->bal_trans_per_charge) / 100);
        $amount = $request->amount + $charge;

        if ($user->balance < $amount) {
            $notify[] = ['error', 'Insufficient Balance'];
            return back()->withNotify($notify);
        }

        $user->balance -= $amount;
        $user->save();

        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->trx          = $trx;
        $transaction->user_id      = $user->id;
        $transaction->trx_type     = '-';
        $transaction->remark       = 'balance_transfer';
        $transaction->details      = 'Balance transferred to ' . $transUser->username;
        $transaction->amount       = $request->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = $charge;
        $transaction->save();

        notify($user, 'BAL_SEND', [
            'amount'      => showAmount($request->amount, currencyFormat: false),
            'username'    => $transUser->username,
            'trx'         => $trx,
            'charge'      => showAmount($charge, currencyFormat: false),
            'balance_now' => showAmount($user->balance, currencyFormat: false),
        ]);

        $transUser->balance += $request->amount;
        $transUser->save();

        $transaction               = new Transaction();
        $transaction->trx          = $trx;
        $transaction->user_id      = $transUser->id;
        $transaction->remark       = 'balance_receive';
        $transaction->details      = 'Balance receive From ' . $user->username;
        $transaction->amount       = $request->amount;
        $transaction->post_balance = $transUser->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->save();

        notify($transUser, 'BAL_RECEIVE', [
            'amount'      => showAmount($request->amount, currencyFormat: false),
            'trx'         => $trx,
            'username'    => $user->username,
            'charge'      => 0,
            'balance_now' => showAmount($transUser->balance, currencyFormat: false),
        ]);

        $notify[] = ['success', 'Balance Transferred Successfully.'];
        return back()->withNotify($notify);
    }

    public function orders()
    {
        $pageTitle = 'Orders';
        $orders    = Order::where('user_id', auth()->user()->id)->with('product')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.orders', compact('pageTitle', 'orders'));
    }
	
	public function notifications(Request $request) {
    $pageTitle = 'Notifications';
    $emptyMessage = 'No records found';

    // Retrieve top seller
    $top_seller = DB::connection('mysql_store')->table('orders')
        ->select('seller_id', DB::raw('COUNT(*) as order_count'))
        ->where('seller_is', 'seller')  // Fixed: Added the missing semicolon here.
        ->groupBy('seller_id')
        ->orderByDesc('order_count')
        ->first();
    
    // Retrieve top customer
    $top_customer = DB::connection('mysql_store')->table('orders')
        ->select('customer_id', DB::raw('COUNT(*) as order_count'))
        ->where('customer_type', 'customer')
        ->groupBy('customer_id')
        ->orderByDesc('order_count')
        ->first();
    
    // Retrieve most recent user entry (based on username matching 'dds000')
    $recent_entry = User::where('username', 'Like', '%dds000%')->orderBy('id', 'DESC')->first();
    
    // Retrieve seller user details if top seller exists
    $seller_user = null;
    if ($top_seller) {
        $seller = DB::connection('mysql_store')->table('sellers')->where('id', $top_seller->seller_id)->first();
        $seller_user = User::where('username', $seller->username ?? '')->first();  // Handle possible null username
    }
    
    // Retrieve customer user details if top customer exists
    $customer_user = null;
    if ($top_customer) {
        $customer = DB::connection('mysql_store')->table('users')->where('id', $top_customer->customer_id)->first();
        $customer_user = User::where('username', $customer->username ?? '')->first();  // Handle possible null username
    }

   // Retrieve transactions from the last 7 days and user details for each transaction
$transactions = DB::connection('mysql')->table('transactions')
    ->where('created_at', '>=', now()->subDays(7))
    ->orderBy('created_at', 'desc')
    ->paginate(10);  // Paginate results, 20 transactions per page

// Enrich each transaction with user details
$transactions->getCollection()->transform(function ($transaction) {
    // Get user details using the user_id from the transactions table
    $user = DB::connection('mysql')->table('users')->where('id', $transaction->user_id)->first();

    // Enrich transaction with user details
    $transaction->name = $user ? $user->firstname . ' ' . $user->lastname : 'Unknown';
    $transaction->username = $user ? $user->username : 'Unknown';
    $transaction->city = $user ? $user->city : 'Unknown';
    $transaction->amount = $transaction->amount ?? 0;  // Ensure amount is set, default to 0 if missing
    $transaction->details = $transaction->details ?? 'Unknown';  // Ensure details are set, default to 'Unknown'

    return $transaction;
});

		
    return view('Template::user.notifications', compact(
        'pageTitle', 'emptyMessage', 'transactions', 'seller_user', 
        'top_seller', 'customer_user', 'top_customer', 'recent_entry'
    ));
}

	
	public function shops_franchises()
    {
        $pageTitle = 'Shops & Franchises';
        $emptyMessage = 'No records found.';

        // Fetch sellers where status is 'approved'
        $sellers = PrimarySeller::where('status', 'approved') // Changed to 'approved'
                                ->with('primaryShop')
                                ->paginate(getPaginate());

        // Count each type of vendor where status is 'approved'
        $shops = PrimarySeller::where('status', 'approved')->where('vendor_type', 'shop')->count(); // Changed to 'approved'
        $franchises = PrimarySeller::where('status', 'approved')->where('vendor_type', 'franchise')->count(); // Changed to 'approved'
        $vendors = PrimarySeller::where('status', 'approved')->where('vendor_type', 'vendor')->count(); // Changed to 'approved'

        // Pass all the data to the view
        return view('Template::user.shops_franchises', compact(
            'pageTitle',
            'emptyMessage',
            'sellers',
            'shops',
            'franchises',
            'vendors'
        ));
    }
	
	public function mySummery()
{
    $pageTitle = 'My Summary';
    $user = auth()->user();

    // User Information
    $username = $user->username;
    $name = $user->firstname . ' ' . $user->lastname;
    $city = $user->city;

    // Get the positional parent instead of the referrer
    $positionalParentId = getPositionId($user->id);
    $positionalParent = $positionalParentId ? User::find($positionalParentId) : null;
    $underUser = $positionalParent ? $positionalParent->username : 'N/A';

    $positionId = getPositionLocation($user->id);
    $positions = mlmPositions();
    $position = $positions[$positionId] ?? 'N/A';


    // Rank - reusing logic from home()
    $rankData = DB::table('users as u')
        ->leftJoin('ranks as r', 'u.pairs', '>=', 'r.requirement')
        ->select('r.name as current_rank')
        ->where('u.id', $user->id)
        ->orderBy('r.requirement', 'desc')
        ->first();
    $rank = $rankData ? $rankData->current_rank : 'User';

    // Calculate the user's absolute level in the tree
    $level = getUserDepthInTree($user->id);

    // User Counts
    $totalFreeUsers = User::where('ref_by', $user->id)->where('plan_id', 0)->count();
    $totalPaidUsers = User::where('ref_by', $user->id)->where('plan_id', '!=', 0)->count();
    $myTotalDsp = User::where('dsp_ref_by', $user->username)->count();
    $myVendors = PrimarySeller::where('username', $user->username)->count();

    // Earnings
    $totalEarnings = $user->bv +
                     $user->dds_ref_bonus +
                     $user->weekly_bonus +
                     $user->pair_bonus +
                     $user->dsp_ref_bonus +
                     $user->shop_bonus +
                     $user->shop_reference +
                     $user->franchise_bonus +
                     $user->franchise_ref_bonus +
                     $user->city_ref_bonus +
                     $user->leadership_bonus +
                     $user->company_partner_bonus +
                     $user->product_partner_bonus +
                     $user->product_partner_ref_bonus +
                     $user->vendor_ref_bonus +
                     $user->royalty_bonus;

    $totalReferenceEarnings = $user->dds_ref_bonus +
                              $user->shop_reference +
                              $user->franchise_ref_bonus +
                              $user->city_ref_bonus +
                              $user->product_partner_ref_bonus +
                              $user->vendor_ref_bonus;

    // Fetch all ranks to calculate for each user in the view
    $ranks = Rank::orderBy('requirement')->get();

    // Fetch the referred users (uses 'page' query parameter by default)
    $referredUsers = User::where('ref_by', $user->id)->latest()->paginate(getPaginate());

    // Fetch the DSP users (uses a custom 'dsp_page' query parameter and eager-loads the 'refBy' relationship)
    $dspUsers = User::where('dsp_ref_by', $user->username)
                    ->with('refBy') // Eager-load the referrer's data
                    ->latest()
                    ->paginate(getPaginate(10), ['*'], 'dsp_page');


    return view('Template::user.my_summery', compact(
        'pageTitle',
        'username', 'name', 'city', 'underUser', 'position', 'rank', 'level',
        'totalFreeUsers', 'totalPaidUsers', 'myTotalDsp', 'myVendors',
        'totalEarnings', 'totalReferenceEarnings',
        'referredUsers', 'ranks', 'user',
        'dspUsers'
    ));
}
	
}