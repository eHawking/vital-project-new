<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BrightFutureController extends Controller
{
    public function activeUsers()
    {
        $pageTitle = 'Bright Future Plan Users';
        $users = User::where('bright_future_plan', 1)->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.bright_future.index', compact('pageTitle', 'users'));
    }

    public function storeManualProfit(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'amount' => 'required|numeric|gt:0',
            'date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $user = User::where('username', $request->username)->firstOrFail();

        if ($user->bright_future_plan != 1) {
            $notify[] = ['error', 'User is not subscribed to the Bright Future Plan'];
            return back()->withNotify($notify);
        }

        $user->bright_future_balance += $request->amount;
        $user->save();

        $trx = new Transaction();
        $trx->user_id = $user->id;
        $trx->amount = $request->amount;
        $trx->post_balance = $user->bright_future_balance;
        $trx->charge = 0;
        $trx->trx_type = '+';
        
        $date = \Carbon\Carbon::parse($request->date);
        $formattedDate = $date->format('d F Y');
        
        // "USERNAME has been credited with a daily profit of AMOUNT WITH CURRENCY under the PLAN_NAME for DATE MONTH."
        $details = $user->username . ' has been credited with a daily profit of ' . showAmount($request->amount) . ' ' . gs()->cur_text . ' under the Bright Future Plan for ' . $formattedDate . '.';
        
        $trx->details = $details;
        $trx->remark = 'bright_future_profit_manual';
        $trx->trx = getTrx();
        $trx->created_at = $date;
        $trx->updated_at = $date;
        $trx->save();

        $notify[] = ['success', 'Manual profit added successfully'];
        return back()->withNotify($notify);
    }
}
