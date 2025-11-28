<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Utils\Helpers;
use App\Models\AddFundBonusCategories;
use App\Models\SecondaryUser; 
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function App\Utils\payment_gateways;

class UserWalletController extends Controller
{

    public function index(Request $request): View|RedirectResponse
    {
        $walletStatus = getWebConfig(name: 'wallet_status');
        if ($walletStatus == 1) {
			
			$user = auth('customer')->user();

            // Use the SecondaryUser model to get the current balance
            $current_balance = SecondaryUser::where('username', $user->username)->value('balance') ?? 0;
			
            $transactionTypes = $this->getSelectTransactionTypes(types: $request->get('types', []));
            $totalWalletBalance = auth('customer')->user()->wallet_balance;

            $walletTransactionList = $this->getWalletTransactionList(request: $request, types: $transactionTypes);
            $paymentGatewayList = payment_gateways();
            $addFundBonusList = $this->getAddFundBonusList();

            $filterCount = count($request['types']??[]) + (int)!empty($request['transaction_range']) + (int)!empty($request['filter_by']);

            if ($request->has('flag') && $request['flag'] == 'success') {
                Toastr::success(translate('add_fund_to_wallet_success'));
                return redirect()->route('wallet');
            } else if ($request->has('flag') && $request['flag'] == 'fail') {
                Toastr::error(translate('add_fund_to_wallet_unsuccessful'));
                return redirect()->route('wallet');
            }

            return view(VIEW_FILE_NAMES['user_wallet'], [
                'totalWalletBalance' => $totalWalletBalance,
                'walletTransactionList' => $walletTransactionList,
                'paymentGatewayList' => $paymentGatewayList,
                'addFundBonusList' => $addFundBonusList,
                'transactionTypes' => $request->get('types', []),
                'filterCount' => $filterCount,
                'filterBy' => $request['filter_by'] ?? '',
                'transactionRange' => $request['transaction_range'] ?? '',
				'current_balance' => $current_balance,
            ]);

        } else {
            Toastr::warning(translate('access_denied!'));
            return redirect()->route('home');
        }
    }

    public function myWalletAccount(): View
    {
        return view(VIEW_FILE_NAMES['wallet_account']);
    }

    private function getWalletTransactionList(object|array $request, array $types)
    {
        $startDate = '';
        $endDate = '';
        if (isset($request['transaction_range']) && !empty($request['transaction_range'])) {
            $dates = explode(' - ', $request['transaction_range']);
            if (count($dates) !== 2 || !checkDateFormatInMDY($dates[0]) || !checkDateFormatInMDY($dates[1])) {
                Toastr::error(translate('Invalid_date_range_format'));
                return back();
            }
            $startDate = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d') . ' 00:00:00';
            $endDate = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d') . ' 23:59:59';
        }
        return WalletTransaction::where('user_id', auth('customer')->id())
            ->when($request->has('filter_by') && in_array($request['filter_by'], ['debit', 'credit']), function ($query) use ($request) {
                $query->when($request['filter_by'] == 'debit', function ($query) {
                    $query->where('debit', '!=', 0);
                })->when($request['filter_by'] == 'credit', function ($query) {
                    $query->where('debit', '=', 0);
                });
            })
            ->when(!empty($startDate) && !empty($endDate), function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($types) || in_array('added_via_payment_method', $request['types'] ?? []) || in_array('earned_by_referral', $request['types'] ?? []), function ($query) use ($types, $request) {
                $query->where(function ($subResult) use ($types, $request) {
                    if (!empty($types)) {
                        $subResult->whereIn('transaction_type', $types);
                    }
                    if (in_array('added_via_payment_method', $request['types'] ?? [])) {
                        $subResult->orWhere('reference', 'add_funds_to_wallet');
                    }
                    if (in_array('earned_by_referral', $request['types'] ?? [])) {
                        $subResult->orWhere('reference', 'earned_by_referral');
                    }
                });
            })
            ->latest()
            ->paginate(10)->appends(request()->query());
    }

    public function getAddFundBonusList()
    {
        return AddFundBonusCategories::where('is_active', 1)
            ->whereDate('start_date_time', '<=', date('Y-m-d'))
            ->whereDate('end_date_time', '>=', date('Y-m-d'))
            ->get();
    }

    public function getSelectTransactionTypes($types): array
    {
        $typeMapping = [
            'order_refund' => 'order_refund',
            'order_place' => 'order_place',
            'loyalty_point' => 'loyalty_point',
            'add_fund' => 'add_fund',
            'add_fund_by_admin' => 'add_fund_by_admin',
        ];

        foreach ($typeMapping as $key => $value) {
            if (in_array($key, $types)) {
                $transactionTypes[] = $value;
            }
        }
        return $transactionTypes ?? [];
    }
	
	/**
     * Transfer balance from secondary DB to main wallet
     */
    public function transferBalanceToWallet(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
    ]);

    $mainUser = auth('customer')->user(); // Get the authenticated user from the main DB
    $amount = $request->input('amount');

    // Begin database transactions for both databases
    DB::beginTransaction();
    DB::connection('mysql2')->beginTransaction();

    try {
        // Fetch the user from the secondary database using the SecondaryUser model
        $secondaryUser = SecondaryUser::where('username', $mainUser->username)->first();

        if (!$secondaryUser) {
            throw new \Exception(translate('No matching user found in the secondary database.'));
        }

        // Check if the user has enough balance in the secondary database
        $currentBalance = $secondaryUser->balance;

        if ($currentBalance < $amount) {
            throw new \Exception(translate('Insufficient balance in the secondary database.'));
        }

        $transactionIdx = strtoupper(bin2hex(random_bytes(6)));


        // Generate a unique transaction ID for the transfer
        $transactionId = (string) \Illuminate\Support\Str::uuid();

        // Deduct the balance from the secondary database user using the Eloquent model
        $secondaryUser->decrement('balance', $amount);

        // Insert a transaction record into the secondary database
        // Note: This still uses the Query Builder as no 'SecondaryTransaction' model was provided.
        DB::connection('mysql2')->table('transactions')->insert([
            'user_id' => $secondaryUser->id,
            'amount' => $amount,
            'charge' => 0,
            'post_balance' => $currentBalance - $amount,
            'trx_type' => '-',
            'trx' => $transactionIdx,
            'remark' => 'transfer_to_deposit_wallet',
            'details' => 'Transferred to Deposit Wallet',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Increment the wallet balance for the user in the primary database
        DB::table('users')
            ->where('username', $mainUser->username)
            ->increment('wallet_balance', $amount);

        // Get the updated wallet balance
        $updatedWalletBalance = DB::table('users')
            ->where('username', $mainUser->username)
            ->value('wallet_balance');

        // Insert a transaction record into the wallet_transactions table
        DB::table('wallet_transactions')->insert([
            'user_id' => $mainUser->id,
            'transaction_id' => $transactionId,
            'credit' => $amount,
            'debit' => 0.00,
            'admin_bonus' => 0.00,
            'balance' => $updatedWalletBalance,
            'transaction_type' => 'received_from_current_balance',
            'payment_method' => 'balance_transfer',
            'reference' => 'balance_transfer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Commit the transactions for both databases
        DB::commit();
        DB::connection('mysql2')->commit();

        // Success message
        Toastr::success(translate('Current Balance transferred successfully to Deposit Wallet.'));
        return redirect()->route('wallet');
    } catch (\Exception $e) {
        // Rollback transactions on any error
        DB::rollBack();
        DB::connection('mysql2')->rollBack();

        // Display error message
        Toastr::error($e->getMessage());
        return redirect()->route('wallet');
    }
}
}