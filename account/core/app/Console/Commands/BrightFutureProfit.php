<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;

class BrightFutureProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bright-future:profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute daily profit to Bright Future plan users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $general = gs();
        $profitAmount = $general->bright_future_daily_profit;

        if ($profitAmount <= 0) {
            if ($this->output) {
                $this->info('Profit amount is 0 or less. No distribution.');
            }
            return Command::SUCCESS;
        }

        $users = User::where('bright_future_plan', 1)->get();

        $maxCap = 400000;

        foreach ($users as $user) {
            // Check if user has reached the max cap
            if ($user->bright_future_balance >= $maxCap) {
                continue;
            }

            // Calculate actual profit to distribute (don't exceed cap)
            $actualProfit = $profitAmount;
            if ($user->bright_future_balance + $profitAmount > $maxCap) {
                $actualProfit = $maxCap - $user->bright_future_balance;
            }

            if ($actualProfit <= 0) continue;

            // Check if user already received profit today
            $today = now()->format('Y-m-d');
            $transaction = Transaction::where('user_id', $user->id)
                ->where('remark', 'bright_future_profit')
                ->whereDate('created_at', $today)
                ->first();

            if (!$transaction) {
                $user->bright_future_balance += $actualProfit;
                $user->save();

                $trx = new Transaction();
                $trx->user_id = $user->id;
                $trx->amount = $actualProfit;
                $trx->post_balance = $user->bright_future_balance;
                $trx->charge = 0;
                $trx->trx_type = '+';
                // Format: USERNAME has been credited with a daily profit of AMOUNT under the PLAN_NAME for DATE MONTH
                $dateMonth = now()->format('d F');
                $trx->details = $user->username . ' has been credited with a daily profit of ' . getAmount($actualProfit) . ' ' . $general->cur_text . ' under the Bright Future Plan for ' . $dateMonth;
                $trx->remark = 'bright_future_profit';
                $trx->trx = getTrx();
                $trx->save();
            }
        }

        if ($this->output) {
            $this->info('Bright Future profit distributed successfully.');
        }
        return Command::SUCCESS;
    }
}
