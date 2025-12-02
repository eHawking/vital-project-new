<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BrightFutureController extends Controller
{
    public function activeUsers()
    {
        $pageTitle = 'Bright Future Plan Users';
        $users = User::where('bright_future_plan', 1)->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.bright_future.index', compact('pageTitle', 'users'));
    }

    public function manualProfit(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'amount' => 'required|numeric|gt:0',
            'date' => 'required|date',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user->bright_future_plan != 1) {
            $notify[] = ['error', 'User does not have Bright Future Plan active'];
            return back()->withNotify($notify);
        }

        $user->bright_future_balance += $request->amount;
        $user->save();

        $date = Carbon::parse($request->date);

        $trx = new Transaction();
        $trx->user_id = $user->id;
        $trx->amount = $request->amount;
        $trx->post_balance = $user->bright_future_balance;
        $trx->charge = 0;
        $trx->trx_type = '+';
        $trx->details = $user->username . ' has been credited with a daily profit of ' . showAmount($request->amount) . ' under the Bright Future Plan for ' . $date->format('d F Y');
        $trx->remark = 'bright_future_profit_manual';
        $trx->trx = getTrx();
        $trx->created_at = $date; // Set the created_at to the selected date
        $trx->save();

        $notify[] = ['success', 'Profit added successfully'];
        return back()->withNotify($notify);
    }
}
