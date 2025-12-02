<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrightFutureController extends Controller
{
    public function activeUsers()
    {
        $pageTitle = 'Bright Future Plan Users';
        $users = User::where('bright_future_plan', 1)->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.bright_future.index', compact('pageTitle', 'users'));
    }

    public function manualProfit()
    {
        $pageTitle = 'Manual Profit Distribution';
        return view('admin.bright_future.manual_profit', compact('pageTitle'));
    }

    public function manualProfitSubmit(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'amount' => 'required|numeric|gt:0',
            'date' => 'required|date',
            'time' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user->bright_future_plan != 1) {
            $notify[] = ['error', 'User does not have an active Bright Future Plan'];
            return back()->withNotify($notify);
        }

        // Combine date and time
        $dateTime = $request->date . ' ' . $request->time;
        
        // Add profit to balance
        $user->bright_future_balance += $request->amount;
        $user->save();

        // Create Transaction
        $trx = new Transaction();
        $trx->user_id = $user->id;
        $trx->amount = $request->amount;
        $trx->post_balance = $user->bright_future_balance;
        $trx->charge = 0;
        $trx->trx_type = '+';
        $trx->details = 'Manual Bright Future Profit: ' . $request->amount . ' ' . gs()->cur_text;
        $trx->remark = 'bright_future_manual_profit';
        $trx->trx = getTrx();
        $trx->created_at = $dateTime;
        $trx->save();

        $notify[] = ['success', 'Profit added successfully to ' . $user->username];
        return back()->withNotify($notify);
    }
}
