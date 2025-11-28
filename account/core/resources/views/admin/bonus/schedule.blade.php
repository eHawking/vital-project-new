@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">
    <!-- Current Bonus Pool -->
    <div class="col-xxl-3 col-sm-6">
        <x-widget value="{{ number_format($royaltyBonus->current_royalty_bonus ?? 0, 2) }}" 
                  title="Current Bonus Pool" style="6" icon="las la-coins" bg="primary" outline="false" />
    </div>

   <!-- Eligible Users -->
<div class="col-xxl-3 col-sm-6">
    <x-widget 
        value="{{ $btpCount }}" 
        title="Eligible Users" 
        style="6" 
        icon="las la-users" 
        bg="success" 
        outline="false"
        link="{{ route('admin.bonus.eligible_users') }}"
    />
</div>
	

    <!-- BTP Capital Back Count -->
    <div class="col-xxl-3 col-sm-6">
        <x-widget value="{{ $btpCapitalBackCount }}" 
                  title="BTP Capital Back Users" style="6" icon="las la-chart-line" bg="info" outline="false" />
    </div>

    <!-- Total Distributed Bonus -->
    <div class="col-xxl-3 col-sm-6">
        <x-widget value="{{ number_format($totalDistributedBonus, 2) }}" 
                  title="Total Distributed Bonus" style="6" icon="las la-hand-holding-usd" bg="danger" outline="false" />
    </div>

    <!-- Last Distributed Bonus -->
    <div class="col-xxl-3 col-sm-6">
        <x-widget value="{{ number_format($lastDistributedBonus, 2) }}" 
                  title="Last Distributed Bonus" style="6" icon="las la-hand-holding-usd" bg="success" outline="false" />
    </div>

    <!-- Last Distribution Date -->
    <div class="col-xxl-3 col-sm-6">
        <x-widget value="{{ $royaltyBonus->last_distributed_date ?? 'N/A' }}" 
                  title="Last Distribution Date" style="6" icon="las la-calendar-check" bg="warning" outline="false" />
    </div>
</div><!-- row end -->

<div class="row gy-4 mt-4">
    <!-- Set Distribution Date Form -->
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Set Royalty Bonus Distribution Date</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bonus.set_date.save') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="set_date" class="form-label">Select Distribution Day (1st to 5th)</label>
                        <input type="number" id="set_date" name="set_date" class="form-control" 
                               min="1" max="5" value="{{ $royaltyBonus->set_day ?? '' }}" required>
                        <small class="form-text text-muted">Enter a day between 1 and 5. The bonus will be distributed on this day for every upcoming month.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Day</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Transaction Detail -->
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Transaction Detail</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bonus.transaction_detail.save') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="transaction_detail" class="form-label">Transaction Detail</label>
                        <textarea id="transaction_detail" name="transaction_detail" class="form-control" rows="4" required>{{ $royaltyBonus->transaction_detail ?? 'Royalty Bonus for {month_name} of {amount}' }}</textarea>
                        <small class="form-text text-muted">
                            Use the following tags:
                            <ul>
                                <li><code>{month_name}</code> - Replaced with the current month name.</li>
                                <li><code>{amount}</code> - Replaced with the distributed bonus amount for the user.</li>
                            </ul>
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Transaction Detail</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- row end -->

<div class="row gy-4 mt-4">
    <!-- Manual Bonus Distribution -->
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manually Distribute Bonus</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmDistributionModal">
                    Distribute Bonus
                </button>
                <small class="form-text text-muted mt-2">Click the button to manually trigger the Royalty Bonus distribution to eligible users.</small>
            </div>
        </div>
    </div>
</div><!-- row end -->

<!-- Modal for Confirmation -->
<div class="modal fade" id="confirmDistributionModal" tabindex="-1" aria-labelledby="confirmDistributionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDistributionModalLabel">Confirm Distribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to distribute the Royalty Bonus? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="distributeBonusForm" action="{{ route('admin.bonus.distribute') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
