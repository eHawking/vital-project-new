@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">

       <!-- Total DSP Count -->
<div class="col-xxl-3 col-sm-6">
    <x-widget 
        value="{{ $totalDSPCount }}" 
        title="Total DSP Count" 
        style="6" 
	    link="{{ route('admin.users.paid') }}"
        icon="las la-users" 
        bg="success" 
        outline="false" />
</div>

<!-- Total Collected Amount -->
<div class="col-xxl-3 col-sm-6">
    <x-widget 
        value="{{ showAmount($totalCollectedAmount) }}" 
        title="Total Collected Amount" 
        style="6" 
        icon="las la-coins" 
        bg="primary" 
        outline="false" />
</div>

<!-- Total Current Balance -->
<div class="col-xxl-3 col-sm-6">
    <x-widget 
        value="{{ showAmount($totalCurrentBalance) }}" 
        title="Total Current Balance" 
        style="6" 
        icon="las la-wallet" 
        bg="warning" 
        outline="false" />
</div>


        @php
            $wallets = [
                'dsp_balance' => ['title' => 'DSP Balance', 'icon' => 'las la-wallet', 'bg' => 'primary'],
                'direct_reference_balance' => ['title' => 'Direct Reference Balance', 'icon' => 'las la-user-friends', 'bg' => 'success'],
                'pair_balance' => ['title' => 'Pair Balance', 'icon' => 'las la-link', 'bg' => 'warning'],
                'reward_balance' => ['title' => 'Reward Balance', 'icon' => 'las la-gift', 'bg' => 'info'],
                'weekly_promo_balance' => ['title' => 'Weekly Promo Balance', 'icon' => 'las la-calendar-week', 'bg' => 'primary'],
                'budget_promo_balance' => ['title' => 'Budget Promo Balance', 'icon' => 'las la-money-bill-wave', 'bg' => 'success'],
                'event_balance' => ['title' => 'Event Balance', 'icon' => 'las la-calendar', 'bg' => 'info'],
                'visit_outside_balance' => ['title' => 'Visit Outside Balance', 'icon' => 'las la-route', 'bg' => 'warning'],
                'shipping_balance' => ['title' => 'Shipping Balance', 'icon' => 'las la-truck', 'bg' => 'danger'],
                'office_balance' => ['title' => 'Office Balance', 'icon' => 'las la-building', 'bg' => 'primary'],
                'it_balance' => ['title' => 'IT Balance', 'icon' => 'las la-laptop', 'bg' => 'info'],
                'pintapay_balance' => ['title' => 'PintaPay Balance', 'icon' => 'las la-credit-card', 'bg' => 'success'],
                'products_balance' => ['title' => 'Products Balance', 'icon' => 'las la-boxes', 'bg' => 'warning'],
                'bbs_balance' => ['title' => 'BBS Balance', 'icon' => 'las la-hand-holding-usd', 'bg' => 'danger'],
                'student_balance' => ['title' => 'Student Balance', 'icon' => 'las la-user-graduate', 'bg' => 'info'],
                'extra_balance' => ['title' => 'Extra Balance', 'icon' => 'las la-coins', 'bg' => 'primary'],
                'company_balance' => ['title' => 'Company Balance', 'icon' => 'las la-briefcase', 'bg' => 'success'],
            ];
        @endphp

        @foreach ($wallets as $key => $wallet)
            <div class="col-xxl-3 col-sm-6">
                <x-widget 
                    value="{{ showAmount($balances[$key] ?? 0) }}" 
                    title="{{ $wallet['title'] }}" 
                    style="6" 
                    icon="{{ $wallet['icon'] }}" 
                    bg="{{ $wallet['bg'] }}" 
                    outline="false" />
            </div>
        @endforeach
    </div>
@endsection
