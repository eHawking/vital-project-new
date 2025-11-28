<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BbsRank;
use App\Models\BbsUser;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\Request;

class BbsRewardsController extends Controller
{
    /**
     * Display the BBS Rewards page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $pageTitle = 'BBS Rewards';

        // Count how many of the user's DSP members are Emperors.
        // First, find the requirement for the 'Emperor' rank.
        $emperorRankDetails = Rank::where('name', 'Emperor')->first();
        $emperorRequirement = $emperorRankDetails ? $emperorRankDetails->requirement : -1;

        $emperorCount = 0;
        if ($emperorRequirement != -1) {
            $emperorCount = User::where('dsp_ref_by', $user->username)
                ->where('pairs', '>=', $emperorRequirement)
                ->count();
        }

        // Fetch all BBS ranks to display on the page.
        $bbsRanks = BbsRank::orderBy('requirement', 'asc')->get();

        return view('Template::user.bbs_rewards', compact('pageTitle', 'bbsRanks', 'emperorCount'));
    }
}
