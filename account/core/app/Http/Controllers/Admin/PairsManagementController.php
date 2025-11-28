<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserExtra;
use App\Models\PairsManagement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SplQueue;

class PairsManagementController extends Controller
{
    /**
     * Display the pairs management page with summary cards and a table of users.
     */
    public function index()
    {
        $pageTitle = 'Pairs Management';
        $emptyMessage = 'No users found.';

        // --- Data Calculation for Summary Cards ---
        $totalAllPairs = UserExtra::sum(DB::raw('LEAST(paid_left, paid_right)'));
        $pairsBudget = DB::table('bonus_wallets')->value('pair_balance');
        $distributedBonus = Transaction::whereIn('remark', ['pair_bonus', 'Pair Bonus'])->sum('amount');

        // Fetch users with their extra data and total pair bonus
        $users = User::with('userExtra')
            ->select('users.*', DB::raw('(SELECT SUM(amount) FROM transactions WHERE user_id = users.id AND remark IN ("pair_bonus", "Pair Bonus")) as total_pair_bonus'))
            ->orderBy('id', 'asc')
            ->paginate(getPaginate());
        
        return view('admin.pairs_management', compact(
            'pageTitle',
            'users',
            'emptyMessage',
            'totalAllPairs',
            'pairsBudget',
            'distributedBonus'
        ));
    }

    /**
     * Get the pair bonus transaction log for a specific user via AJAX.
     */
    public function getPairBonusLog(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $logs = Transaction::where('user_id', $request->user_id)
                            ->whereIn('remark', ['pair_bonus', 'Pair Bonus'])
                            ->latest()
                            ->get();
        return response()->json(['success' => true, 'logs' => $logs]);
    }

    /**
     * Display the pairs management settings page.
     */
    public function settings()
    {
        $pageTitle = 'Pair Limit Settings';
        $settings = PairsManagement::firstOrFail();
        return view('admin.pairs_settings', compact('pageTitle', 'settings'));
    }

    /**
     * Update the pairs management settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'pairs_bonus_limit' => 'required|integer|min:0',
            'password' => 'nullable|string|min:6',
        ]);

        $settings = PairsManagement::firstOrFail();
        $settings->pairs_bonus_limit = $request->pairs_bonus_limit;

        if ($request->filled('password')) {
            $settings->password = $request->password;
        }

        $settings->save();

        $notify[] = ['success', 'Pairs management settings updated successfully.'];
        return back()->withNotify($notify);
    }

    /**
     * Verify the security password via AJAX.
     */
    public function verifyPassword(Request $request)
    {
        $request->validate(['password' => 'required']);
        $settings = PairsManagement::firstOrFail();

        if (Hash::check($request->password, $settings->password)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Update the limit status via AJAX after password verification.
     */
    public function updateLimitStatus(Request $request)
    {
        $request->validate(['status' => 'required|boolean']);
        
        $settings = PairsManagement::firstOrFail();
        $settings->limit_status = $request->status;
        $settings->save();

        return response()->json(['success' => true, 'message' => 'Limit status updated successfully.']);
    }

    /**
     * Get the summarized pair counts for a user's downline, broken down by level.
     */
    public function getDownlinePairs(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::find($request->user_id);

        $leftChild = getPositionUser($user->id, 1);
        $rightChild = getPositionUser($user->id, 2);

        $leftPairsByLevel = $leftChild ? $this->sumPairsInSubtreeByLevel($leftChild->id) : [];
        $rightPairsByLevel = $rightChild ? $this->sumPairsInSubtreeByLevel($rightChild->id) : [];

        return response()->json([
            'success' => true,
            'left_pairs_by_level' => $leftPairsByLevel,
            'right_pairs_by_level' => $rightPairsByLevel,
        ]);
    }

    /**
     * Sums up all pairs in a given subtree, grouped by level, using Breadth-First Search.
     */
    private function sumPairsInSubtreeByLevel($userId)
    {
        $pairsByLevel = [];
        if (!$userId) {
            return $pairsByLevel;
        }

        $queue = new SplQueue();
        $queue->enqueue([$userId, 1]);
        $visited = [];

        while (!$queue->isEmpty()) {
            [$currentUserId, $level] = $queue->dequeue();

            if (isset($visited[$currentUserId])) {
                continue;
            }
            $visited[$currentUserId] = true;

            $currentUser = User::with('userExtra')->find($currentUserId);

            if ($currentUser && $currentUser->userExtra) {
                $pairs = min($currentUser->userExtra->paid_left ?? 0, $currentUser->userExtra->paid_right ?? 0);

                if ($pairs > 0) {
                    if (!isset($pairsByLevel[$level])) {
                        $pairsByLevel[$level] = 0;
                    }
                    $pairsByLevel[$level] += $pairs;
                }

                $leftChild = getPositionUser($currentUserId, 1);
                if ($leftChild) {
                    $queue->enqueue([$leftChild->id, $level + 1]);
                }

                $rightChild = getPositionUser($currentUserId, 2);
                if ($rightChild) {
                    $queue->enqueue([$rightChild->id, $level + 1]);
                }
            }
        }
        return $pairsByLevel;
    }
}
