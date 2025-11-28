<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Models\PrimarySeller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersSummeryController extends Controller
{
    /**
     * Display a summary of all users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function summary(Request $request)
    {
        $pageTitle = 'Users Summary';
        $search = $request->search;

        // Start building the query for users, eager loading relationships for performance
        $usersQuery = User::with(['refBy', 'userExtra']);

        // If a search term is provided, filter by exact username
        if ($search) {
            $usersQuery->where('username', $search);
        }

        // Paginate the results
        $users = $usersQuery->latest()->paginate(getPaginate());

        // Get user IDs and usernames from the paginated collection for bulk fetching
        $userIds = $users->pluck('id')->all();
        $usernames = $users->pluck('username')->all();

        // Fetch additional data in bulk to avoid N+1 query issues
        $dspCounts = User::whereIn('dsp_ref_by', $usernames)
                            ->select('dsp_ref_by', DB::raw('count(*) as total'))
                            ->groupBy('dsp_ref_by')
                            ->pluck('total', 'dsp_ref_by');

        $vendorCounts = PrimarySeller::whereIn('username', $usernames)
                            ->select('username', DB::raw('count(*) as total'))
                            ->groupBy('username')
                            ->pluck('total', 'username');

        // Correctly fetch total completed withdrawals for each user
        $totalWithdrawals = Withdrawal::whereIn('user_id', $userIds)
                            ->where('status', 1) // 1 = status for completed
                            ->select('user_id', DB::raw('sum(amount) as total'))
                            ->groupBy('user_id')
                            ->pluck('total', 'user_id');
        
        // Fetch total successful deposits for each user
        $totalDeposits = Deposit::whereIn('user_id', $userIds)
                            ->where('status', 1) // 1 = status for successful
                            ->select('user_id', DB::raw('sum(amount) as total'))
                            ->groupBy('user_id')
                            ->pluck('total', 'user_id');

        // Attach the bulk-fetched data to each user object
        $users->each(function ($user) use ($dspCounts, $vendorCounts, $totalWithdrawals, $totalDeposits) {
            $user->own_dsp = $dspCounts[$user->username] ?? 0;
            $user->own_vendors = $vendorCounts[$user->username] ?? 0;
            $user->total_withdraw = $totalWithdrawals[$user->id] ?? 0;
            $user->total_deposit = $totalDeposits[$user->id] ?? 0;
        });

        // Overall User Counts for summary boxes
        $totalFreeUsers = User::where('plan_id', 0)->count();
        $totalPaidUsers = User::where('plan_id', '!=', 0)->whereNull('dsp_ref_by')->count();
        $totalDsp = User::whereNotNull('dsp_ref_by')->count();
        $totalDDSUsers = User::where('username', 'like', 'dds%')->count();
        $totalVendors = PrimarySeller::count();

        // Calculate total earnings, checking for column existence to prevent errors
        $userTableColumns = Schema::getColumnListing('users');
        $earningColumns = [
            'bv', 'dds_ref_bonus', 'weekly_bonus', 'pair_bonus', 'dsp_ref_bonus',
            'shop_bonus', 'shop_reference', 'franchise_bonus', 'franchise_ref_bonus',
            'city_ref_bonus', 'leadership_bonus', 'company_partner_bonus',
            'product_partner_bonus', 'product_partner_ref_bonus', 'vendor_ref_bonus',
            'royalty_bonus'
        ];
        $referenceEarningColumns = [
            'dds_ref_bonus', 'shop_reference', 'franchise_ref_bonus', 'city_ref_bonus',
            'product_partner_ref_bonus', 'vendor_ref_bonus'
        ];

        $totalEarnings = 0;
        foreach ($earningColumns as $column) {
            if (in_array($column, $userTableColumns)) {
                $totalEarnings += User::sum($column);
            }
        }

        $totalReferenceEarnings = 0;
        foreach ($referenceEarningColumns as $column) {
            if (in_array($column, $userTableColumns)) {
                $totalReferenceEarnings += User::sum($column);
            }
        }

        // Fetch all ranks for use in the view
        $ranks = DB::table('ranks')->orderBy('requirement')->get();

        return view('admin.users_summery', compact(
            'pageTitle', 'search',
            'totalFreeUsers',
            'totalPaidUsers',
            'totalDsp',
            'totalDDSUsers',
            'totalVendors',
            'totalEarnings',
            'totalReferenceEarnings',
            'users',
            'ranks'
        ));
    }
}
