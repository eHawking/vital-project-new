<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoyaltyBonus;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class RoyaltyBonusController extends Controller
{
    /**
     * Display the Royalty Bonus Management Page.
     *
     * @return \Illuminate\View\View
     */
    public function setDateForm()
    {
        $royaltyBonus = RoyaltyBonus::first();

        $btpCount = DB::table('users')
            ->where('is_btp', 1)
            ->where('is_ineligible', 0)
            ->where('royalty_bonus', '<', 2600)
            ->count();

        $btpCapitalBackCount = DB::table('users')
            ->where('is_btp', 1)
            ->where('royalty_bonus', '>=', 2600)
            ->count();

        $totalDistributedBonus = DB::table('users')->sum('royalty_bonus');

        $lastDistributedBonus = $royaltyBonus->distributed_bonus ?? 0;

        $pageTitle = "Manage Royalty Bonus Schedule";

        return view('admin.bonus.schedule', compact(
            'royaltyBonus',
            'btpCount',
            'btpCapitalBackCount',
            'totalDistributedBonus',
            'lastDistributedBonus',
            'pageTitle'
        ));
    }

    /**
     * Save the distribution day for Royalty Bonus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDate(Request $request)
    {
        $request->validate([
            'set_date' => 'required|integer|min:1|max:5',
        ]);

        $royaltyBonus = RoyaltyBonus::firstOrNew();
        $royaltyBonus->set_day = $request->set_date;
        $royaltyBonus->save();

        $notify[] = ['success', 'Royalty Bonus distribution day has been set successfully.'];
        return back()->withNotify($notify);
    }

    /**
     * Save transaction detail for distribution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTransactionDetail(Request $request)
    {
        $request->validate([
            'transaction_detail' => 'required|string|max:255',
        ]);

        $royaltyBonus = RoyaltyBonus::firstOrNew();
        $royaltyBonus->transaction_detail = $request->transaction_detail;
        $royaltyBonus->save();

        $notify[] = ['success', 'Transaction detail has been updated successfully.'];
        return back()->withNotify($notify);
    }

    /**
     * Distribute the Royalty Bonus manually.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function distributeBonus()
    {
        $royaltyBonus = RoyaltyBonus::first();

        if (!$royaltyBonus || !$royaltyBonus->set_day) {
            $notify[] = ['error', 'Royalty Bonus distribution day is not set.'];
            return back()->withNotify($notify);
        }

        $result = $this->processBonusDistribution($royaltyBonus);

        if ($result['status'] === 'error') {
            $notify[] = ['error', $result['message']];
        } else {
            $notify[] = ['success', $result['message']];
        }

        return back()->withNotify($notify);
    }

    /**
     * Cron-based distribution of Royalty Bonus.
     */
    public function distributeBonusCron()
    {
        $royaltyBonus = RoyaltyBonus::first();

        if (!$royaltyBonus || !$royaltyBonus->set_day) {
            return response()->json(['status' => 'error', 'message' => 'No distribution day set.']);
        }

        $currentDay = Carbon::now()->day;

        if ($currentDay != $royaltyBonus->set_day) {
            return response()->json(['status' => 'error', 'message' => 'Not the scheduled day for distribution.']);
        }

        $result = $this->processBonusDistribution($royaltyBonus);

        return response()->json(['status' => $result['status'], 'message' => $result['message']]);
    }

    /**
     * Process bonus distribution logic.
     *
     * @param  \App\Models\RoyaltyBonus  $royaltyBonus
     * @return array
     */
    protected function processBonusDistribution($royaltyBonus)
    {
        $eligibleUsers = DB::table('users')
            ->where('is_btp', 1)
            ->where('royalty_bonus', '<', 2600)
            ->where('is_ineligible', 0)
            ->get();

        if ($eligibleUsers->isEmpty()) {
            return ['status' => 'error', 'message' => 'No eligible users for Royalty Bonus distribution.'];
        }

        $bonusPool = $royaltyBonus->current_royalty_bonus;
        $userCount = $eligibleUsers->count();
        $bonusPerUser = $bonusPool / $userCount;

        if ($bonusPerUser <= 0) {
            return ['status' => 'error', 'message' => 'Insufficient bonus pool to distribute.'];
        }

        foreach ($eligibleUsers as $user) {
			
			    $trx = getTrx();

            // Increment user balance
            DB::table('users')->where('id', $user->id)->increment('balance', $bonusPerUser);

            $description = str_replace(
                ['{month_name}', '{amount}'],
                [Carbon::now()->format('F'), number_format($bonusPerUser, 2)],
                $royaltyBonus->transaction_detail ?? 'Royalty Bonus Distribution'
            );
       

            // Create a new transaction record
            $transaction = new Transaction();
            $transaction->trx = $trx; // Unique transaction ID
            $transaction->user_id = $user->id;
            $transaction->trx_type = '+';
            $transaction->remark = 'royalty_bonus';
            $transaction->details = $description;
            $transaction->amount = $bonusPerUser;
            $transaction->post_balance = DB::table('users')->where('id', $user->id)->value('balance');
            $transaction->charge = 0; // No charge for this transaction
            $transaction->save();

            // Update user's royalty_bonus total
            $totalRoyaltyBonus = DB::table('users')->where('id', $user->id)->value('royalty_bonus') + $bonusPerUser;
            DB::table('users')->where('id', $user->id)->update(['royalty_bonus' => $totalRoyaltyBonus]);

            // Mark user as ineligible if their royalty_bonus exceeds the threshold
            if ($totalRoyaltyBonus >= 2600) {
                DB::table('users')->where('id', $user->id)->update(['is_ineligible' => 1]);
            }
        }

        // Update the RoyaltyBonus record
        $royaltyBonus->last_distributed_date = Carbon::now()->toDateString();
        $royaltyBonus->distributed_bonus = $bonusPool;
        $royaltyBonus->current_royalty_bonus = 0;
        $royaltyBonus->save();

        return ['status' => 'success', 'message' => 'Royalty Bonus has been distributed successfully.'];
    }
	
	public function listEligibleUsers(Request $request)
{
    $search = $request->query('search');

    $query = User::where('is_btp', 1);

    if ($search) {
        $query->where(function ($q) use ($search) {
            // Exact match
            $q->orWhere('username', '=', $search)
              ->orWhere('firstname', '=', $search)
              ->orWhere('lastname', '=', $search)
              ->orWhereRaw("CONCAT(firstname, ' ', lastname) = ?", [$search])

              // Starts with match
              ->orWhere('username', 'like', "$search%")
              ->orWhere('firstname', 'like', "$search%")
              ->orWhere('lastname', 'like', "$search%")
              ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["$search%"]);
        });
    }

    $eligibleUsers = $query->orderByDesc('created_at')->paginate(20);
    $eligibleUsers->appends(['search' => $search]);

    $pageTitle = "Eligible Users";
    return view('admin.bonus.eligible_users', compact('eligibleUsers', 'pageTitle', 'search'));
}
}
