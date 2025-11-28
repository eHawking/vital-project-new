<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\MembershipUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\WalletTransactionRepository;
use App\Utils\Convert;
use App\Utils\CustomerManager;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
	public function __construct(
        private readonly WalletTransactionRepository                $walletTransactionRepo,
    )
    {
    }
    /**
     * Display a listing of available memberships.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all available memberships from the database
        $memberships = Membership::all();

        // Check if the user is logged in
        if (auth('customer')->check()) {
            // Get the authenticated user's purchased memberships
            $userMemberships = auth('customer')->user()->memberships ?? collect();
        } else {
            // Return an empty collection if the user is not authenticated
            $userMemberships = collect();
        }

        // Return the view with memberships and user's purchased memberships
        return view('theme-views.memberships.index', compact('memberships', 'userMemberships'));
    }

    /**
     * Handle the purchase of a membership by the authenticated user.
     *
     * @param  int  $id  Membership ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy($id)
{
    // Ensure the user is authenticated before purchasing
    if (!auth('customer')->check()) {
        return response()->json(['message' => 'You need to be logged in to purchase a membership.'], 401);
    }

    // Get the authenticated user's ID
    $userId = auth('customer')->id();

    // Find the user by ID using the User model
    $user = User::findOrFail($userId);
	$userWallet = DB::connection('mysql2')->table('users')->where('username', $user->username)->first();

    // Find the membership the user wants to purchase by ID
    $membership = Membership::findOrFail($id);

    // Check if the user has enough funds in their wallet
    if ($user->wallet_balance < $membership->fee) {
        return response()->json(['message' => 'Insufficient funds. Please add funds to your wallet.'], 400);
    }

    // Check if the user has already purchased this membership using the MembershipUser model
    if ($membership->type !== 'shop' && $membership->type !== 'vendor') {
        $existingMembership = MembershipUser::where('user_id', $user->id)
            ->where('membership_id', $id)
            ->first();

        if ($existingMembership) {
            return response()->json(['message' => 'You have already purchased this membership.'], 400);
        }
    }

		$membershipFee = preg_replace('/\.00$/', '', webCurrencyConverter($membership->fee));
    CustomerManager::create_wallet_transaction($user->id, Convert::default($membership->fee), "purchased_{$membership->name}",'membership payment');
	if($userWallet){
		DB::connection('mysql2')->table('transactions')->insert([
            'user_id' => $userWallet->id,
            'amount' => $membership->fee,
            'trx_type' => '-',
            'remark' => ucfirst(str_replace('_', ' ', $membership->name)),
            'details' => "Purchased $membership->name of {$membershipFee} from deposit wallet.",
            'created_at' => now(),
        ]);
		
		// Update the user's wallet status based on the membership type
        if ($membership->type === 'shop') {
            DB::connection('mysql2')->table('users')->where('id', $userWallet->id)->update(['is_shop' => 1]);
        } elseif ($membership->type === 'vendor') {
            DB::connection('mysql2')->table('users')->where('id', $userWallet->id)->update(['is_vendor' => 1]);
        } elseif ($membership->type === 'franchise') {
            DB::connection('mysql2')->table('users')->where('id', $userWallet->id)->update(['is_franchise' => 1]);
        }
	}

    // Create a new entry in the pivot table using the MembershipUser model
    MembershipUser::create([
        'user_id' => $user->id,
        'membership_id' => $id,
    ]);
		
		// Update the user's status based on the membership type
	if ($membership->type === 'shop') {
        $user->update(['is_shop' => 1]);
    } elseif ($membership->type === 'vendor') {
        $user->update(['is_vendor' => 1]);
    } elseif ($membership->type === 'franchise') {
        $user->update(['is_franchise' => 1]);
    }

    // Return a success response
    return response()->json([
        'message' => "$membership->name purchased successfully."
    ], 200);
}

}
