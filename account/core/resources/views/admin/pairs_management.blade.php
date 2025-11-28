@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-4">
        {{-- Settings Button --}}
        <div class="col-md-6">
            <a href="{{ route('admin.pairs.management.settings') }}" class="btn btn-lg btn-outline--primary w-100">
                <i class="las la-cog"></i> @lang('Pair Limit Settings')
            </a>
        </div>
        {{-- Simulator Button --}}
        <div class="col-md-6">
            <button class="btn btn-lg btn-outline--success w-100" data-bs-toggle="modal" data-bs-target="#simulator-modal">
                <i class="las la-calculator"></i> @lang('Run Compensation Plan Simulator')
            </button>
        </div>
    </div>

    {{-- Summary Cards Row --}}
    <div class="row gy-4 mb-4">
        <div class="col-xl-6 col-sm-6">
            <div class="card bg--primary has-link">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-sitemap fa-3x text--white"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--white text-thin">@lang('Total Pairs of All Users')</span>
                            <h2 class="text--white">{{ $totalAllPairs }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-sm-6">
            <div class="card bg--success has-link">
                 <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lar la-money-bill-alt fa-3x text--white"></i>
                        </div>
                        <div class="col-8 text-end">
                             <span class="text--white text-thin">@lang('Distributed Bonus')</span>
                            <h2 class="text--white">{{ showAmount($distributedBonus) }}</h2>
                        </div>
                         <div class="col-12 mt-2">
                             <small class="text--white">@lang('Current Budget'): {{ showAmount($pairsBudget) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Main User Pairs Table --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Left Free')</th>
                                <th>@lang('Right Free')</th>
                                <th>@lang('Left Paid')</th>
                                <th>@lang('Right Paid')</th>
                                <th>@lang('Total Pairs')</th>
                                <th>@lang('Pair Bonus')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{$user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                    </span>
                                </td>
                                <td>{{ @$user->userExtra->free_left ?? 0 }}</td>
                                <td>{{ @$user->userExtra->free_right ?? 0 }}</td>
                                <td>{{ @$user->userExtra->paid_left ?? 0 }}</td>
                                <td>{{ @$user->userExtra->paid_right ?? 0 }}</td>
                                <td>
                                    @php
                                        $totalPairs = min(@$user->userExtra->paid_left ?? 0, @$user->userExtra->paid_right ?? 0);
                                    @endphp
                                    <span class="badge badge--success">{{ $totalPairs }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline--info view-bonus-log"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->fullname }}">
                                        {{ showAmount($user->total_pair_bonus ?? 0) }}
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline--primary view-downline-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#downline-pairs-modal"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->fullname }}">
                                        <i class="las la-sitemap"></i> @lang('View Downline')
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table></div>
                </div>
                @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pair Bonus Log Modal --}}
    <div class="modal fade" id="bonus-log-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Pair Bonus History for') <span class="fw-bold" id="bonus-log-user-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bonus-log-loader" class="text-center">
                        <div class="spinner-border" role="status"></div>
                    </div>
                    <div id="bonus-log-content-area" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Details')</th>
                                    </tr>
                                </thead>
                                <tbody id="bonus-log-table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Downline Pairs Modal --}}
    <div class="modal fade" id="downline-pairs-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Downline Pairs Summary for') <span class="fw-bold" id="modal-user-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-loader" class="text-center">
                        <div class="spinner-border" role="status"></div>
                        <p class="mt-2">@lang('Calculating totals, please wait...')</p>
                    </div>
                    <div id="modal-content-area" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>@lang('Level')</th>
                                        <th>@lang('Left Pairs')</th>
                                        <th>@lang('Right Pairs')</th>
                                    </tr>
                                </thead>
                                <tbody id="pairs-level-table-body">
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th>@lang('Total')</th>
                                        <th id="total-left-pairs-sum">0</th>
                                        <th id="total-right-pairs-sum">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Simulator Modal --}}
    <div class="modal fade" id="simulator-modal" tabindex="-1" role="dialog" aria-labelledby="simulatorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Compensation Plan Simulator')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header"><h6 class="card-title">@lang('Simulation Parameters')</h6></div>
                                <div class="card-body">
                                    <div class="form-group mb-2">
                                        <label class="fw-bold">@lang('DSP Voucher Price')</label>
                                        <input type="number" id="sim-voucher-price" class="form-control" value="6800">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="fw-bold">@lang('Pairs Budget per Voucher')</label>
                                        <input type="number" id="sim-pairs-budget" class="form-control" value="1100">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="fw-bold">@lang('Pair Bonus Amount')</label>
                                        <input type="number" id="sim-pair-bonus" class="form-control" value="200">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="fw-bold">@lang('Target Number of Users')</label>
                                        <input type="number" id="sim-users" class="form-control" value="1024">
                                    </div>
                                    {{-- Pair Limit Switch and Input --}}
                                    <div class="form-group mb-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="sim-enable-pair-limit">
                                            <label class="form-check-label" for="sim-enable-pair-limit">@lang('Enable Per-User Pairs Limit')</label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-2" id="pair-limit-input-group" style="display: none;">
                                        <label class="fw-bold">@lang('Pairs Limit Per User')</label>
                                        <input type="number" id="sim-pair-limit" class="form-control" value="500">
                                    </div>
                                    {{-- NEW: Only Pairs Till Limit Switch --}}
                                    <div class="form-group mb-2" id="only-pairs-till-limit-group" style="display: none;">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="sim-only-pairs-till-limit">
                                            <label class="form-check-label" for="sim-only-pairs-till-limit">@lang('Only Pairs till limit')</label>
                                        </div>
                                    </div>

                                    <button id="run-simulation-btn" class="btn btn-primary w-100 mt-3">@lang('Calculate')</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-4 mt-lg-0">
                            <div id="simulation-results">
                                <div class="alert alert-info" role="alert">
                                  <h4 class="alert-heading">@lang('Ready to Simulate!')</h4>
                                  <p>@lang('Enter your simulation parameters and click "Calculate".')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
(function($){
    "use strict";

    // --- New Pair Bonus Log Modal ---
    $('.view-bonus-log').on('click', function () {
        var modal = $('#bonus-log-modal');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        modal.find('#bonus-log-user-name').text(userName);
        modal.find('#bonus-log-loader').show();
        modal.find('#bonus-log-content-area').hide();
        $('#bonus-log-table-body').empty();
        modal.modal('show');

        $.ajax({
            url: "{{ route('admin.pairs.management.bonus.log') }}",
            method: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if(response.success) {
                    let logs = response.logs;
                    let tableBody = $('#bonus-log-table-body');
                    
                    if (logs.length === 0) {
                        tableBody.html(`<tr><td colspan="3" class="text-center">@lang('No pair bonus history found.')</td></tr>`);
                    } else {
                        logs.forEach(log => {
                            let date = new Date(log.created_at).toLocaleString();
                            let amount = parseFloat(log.amount).toFixed(2);
                            let row = `<tr>
                                            <td>${date}</td>
                                            <td>{{ gs()->cur_sym }}${amount}</td>
                                            <td>${log.details}</td>
                                       </tr>`;
                            tableBody.append(row);
                        });
                    }
                }
            },
            complete: function() {
                modal.find('#bonus-log-loader').hide();
                modal.find('#bonus-log-content-area').show();
            }
        });
    });

    // --- View Downline Modal Logic ---
    $('.view-downline-btn').on('click', function () {
        var modal = $('#downline-pairs-modal');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        modal.find('#modal-user-name').text(userName);
        modal.find('#modal-loader').show();
        modal.find('#modal-content-area').hide();
        $('#pairs-level-table-body').empty();
        $('#total-left-pairs-sum').text(0);
        $('#total-right-pairs-sum').text(0);
        $.ajax({
            url: "{{ route('admin.pairs.management.downline.summary') }}",
            method: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if(response.success) {
                    let leftData = response.left_pairs_by_level;
                    let rightData = response.right_pairs_by_level;
                    let tableBody = $('#pairs-level-table-body');
                    let allLevels = [...new Set([...Object.keys(leftData), ...Object.keys(rightData)])].map(Number).sort((a, b) => a - b);
                    let totalLeft = 0;
                    let totalRight = 0;
                    if (allLevels.length === 0) {
                        tableBody.html(`<tr><td colspan="3" class="text-center">@lang('No downline pairs found.')</td></tr>`);
                    } else {
                        allLevels.forEach(level => {
                            let leftPairs = leftData[level] || 0;
                            let rightPairs = rightData[level] || 0;
                            totalLeft += leftPairs;
                            totalRight += rightPairs;
                            let row = `<tr><td><b>@lang('Level') ${level}</b></td><td>${leftPairs}</td><td>${rightPairs}</td></tr>`;
                            tableBody.append(row);
                        });
                    }
                    $('#total-left-pairs-sum').text(totalLeft);
                    $('#total-right-pairs-sum').text(totalRight);
                }
            },
            complete: function() {
                modal.find('#modal-loader').hide();
                modal.find('#modal-content-area').show();
            }
        });
    });

    // --- Simulator Logic ---
    $('#sim-enable-pair-limit').on('change', function() {
        if ($(this).is(':checked')) {
            $('#pair-limit-input-group').slideDown();
            $('#only-pairs-till-limit-group').slideDown();
        } else {
            $('#pair-limit-input-group').slideUp();
            $('#only-pairs-till-limit-group').slideUp();
        }
    });

    $('#run-simulation-btn').on('click', function() {
        const params = {
            voucherPrice: BigInt($('#sim-voucher-price').val() || 0),
            budgetPerVoucher: BigInt($('#sim-pairs-budget').val() || 0),
            pairBonus: BigInt($('#sim-pair-bonus').val() || 0),
            currency: "{{ gs('cur_text') }}",
            targetUsers: BigInt($('#sim-users').val() || 0),
            enablePairLimit: $('#sim-enable-pair-limit').is(':checked'),
            pairLimit: BigInt($('#sim-pair-limit').val() || 0),
            onlyPairsTillLimit: $('#sim-only-pairs-till-limit').is(':checked')
        };
        let results = runSimulation(params);
        displayResults(results);
    });

    function runSimulation(p) {
        if (p.targetUsers <= 0) {
             return { isError: true, message: `@lang('Please enter a valid target number of users.')` };
        }
        
        let N = 0;
        let cumulativeUsers = 0n;
        while (cumulativeUsers < p.targetUsers) {
            N++;
            if (N > 60) break; 
            cumulativeUsers += 2n ** BigInt(N);
        }
        const calcLevels = N - 1;

        if (p.enablePairLimit && p.pairLimit > 0n) {
            let breakdownHtml = `
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>@lang('Level')</th>
                            <th>@lang('Pairs')</th>
                            <th>@lang('Users')</th>
                            <th>@lang('Pairs Limit')</th>
                            <th>@lang('Users x Pairs Limit')</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            let grandTotalForPayout = 0n;

            for (let level = 0; level <= calcLevels; level++) {
                const users = (level === 0) ? 1n : (2n ** BigInt(level));
                const userLabel = (level === 0) ? `<b>@lang('Mr. You')</b>` : level;
                const actualPairsPerUser = (level === 0) ? (2n ** BigInt(calcLevels)) : (2n ** BigInt(calcLevels - level));
                
                const isCapped = actualPairsPerUser > p.pairLimit;
                const pairsForPayout = isCapped ? p.pairLimit : actualPairsPerUser;
                const totalLevelPairsForPayout = users * pairsForPayout;
                
                if (p.onlyPairsTillLimit) {
                    if (isCapped) {
                        grandTotalForPayout += totalLevelPairsForPayout;
                    } else {
                        break; 
                    }
                } else {
                    grandTotalForPayout += totalLevelPairsForPayout;
                }

                const userText = users === 1n ? `@lang('User')` : `@lang('Users')`;
                const finalColDisplay = `${totalLevelPairsForPayout.toLocaleString()} (${users.toLocaleString()} ${userText})`;
                const cellStyle = isCapped ? 'style="text-decoration: underline;"' : '';

                breakdownHtml += `
                    <tr>
                        <td class="text-center" ${cellStyle}>${userLabel}</td>
                        <td class="text-center" ${cellStyle}>${actualPairsPerUser.toLocaleString()}</td>
                        <td class="text-center" ${cellStyle}>${users.toLocaleString()}</td>
                        <td class="text-center" ${cellStyle}>${pairsForPayout.toLocaleString()}</td>
                        <td class="text-end fw-bold" ${cellStyle}>${finalColDisplay}</td>
                    </tr>`;
            }

             breakdownHtml += `
                </tbody>
                <tfoot class="table-dark">
                     <tr>
                        <th colspan="4" class="text-end">@lang('Grand Total Pairs for Payout'):</th>
                        <th class="text-end">${grandTotalForPayout.toLocaleString()}</th>
                    </tr>
                </tfoot>
                </table>
                </div>`;
            
            if (p.onlyPairsTillLimit){
                breakdownHtml += `<div class="alert alert-info mt-2">@lang('Note: The calculation stops at the first level where potential pairs do not exceed the limit.')</div>`;
            }


            return {
                payoutGrandTotal: grandTotalForPayout,
                breakdownHtml: breakdownHtml,
                params: p
            };
        }

        let breakdownHtml = `
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>@lang('Level')</th>
                            <th>@lang('Users')</th>
                            <th>@lang('Pairs')</th>
                            <th>@lang('Pairs Per User')</th>
                            <th>@lang('Total Pairs This Level')</th>
                        </tr>
                    </thead>
                    <tbody>`;

        let grandTotalForPayout = 0n;

        for (let level = 0; level <= calcLevels; level++) {
            const users = (level === 0) ? 1n : (2n ** BigInt(level));
            const userLabel = (level === 0) ? `<b>@lang('Mr. You')</b>` : level;
            
            let pairsValue;
            if (level === 0) {
                pairsValue = 1n;
            } else {
                pairsValue = 2n ** BigInt(level - 1);
            }

            const actualPairsPerUser = (level === 0) ? (2n ** BigInt(calcLevels)) : (2n ** BigInt(calcLevels - level));
            const totalLevelPairsForPayout = users * actualPairsPerUser;
            grandTotalForPayout += totalLevelPairsForPayout;

            const userText = users === 1n ? `@lang('User')` : `@lang('Users')`;
            const pairsPerUserDisplay = `${actualPairsPerUser.toLocaleString()} (${users.toLocaleString()} ${userText})`;

            breakdownHtml += `
                <tr>
                    <td class="text-center">${userLabel}</td>
                    <td class="text-center">${users.toLocaleString()}</td>
                    <td class="text-center">${pairsValue.toLocaleString()}</td>
                    <td class="text-center">${pairsPerUserDisplay}</td>
                    <td class="text-end fw-bold">${totalLevelPairsForPayout.toLocaleString()}</td>
                </tr>`;
        }

        const finalLevelUsers = 2n ** BigInt(N);
        const lastLevelPairs = 2n ** BigInt(N - 1);
        breakdownHtml += `
            <tr>
                <td class="text-center">${N}</td>
                <td class="text-center">${finalLevelUsers.toLocaleString()}</td>
                <td class="text-center">${lastLevelPairs.toLocaleString()}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
            </tr>
            </tbody>
            <tfoot class="table-dark">
                 <tr>
                    <th colspan="4" class="text-end">@lang('Grand Total Pairs for Payout'):</th>
                    <th class="text-end">${grandTotalForPayout.toLocaleString()}</th>
                </tr>
            </tfoot>
            </table>
            </div>
            <div class="alert alert-warning mt-2">@lang('Note: Without a pair limit, the calculation is based on a perfect binary tree model that can contain the target number of users.')</div>
            `;

        return {
            payoutGrandTotal: grandTotalForPayout,
            breakdownHtml: breakdownHtml,
            params: p
        };
    }

    function displayResults(r) {
        if (r.isError) {
            $('#simulation-results').html(`<div class="alert alert-danger">${r.message}</div>`);
            return;
        }

        const finalVouchersSold = r.params.targetUsers + 1n;
        const voucherNote = `(${r.params.targetUsers.toLocaleString()} @lang('downline') + 1)`;
        const grandTotalBonusPayout = r.payoutGrandTotal * r.params.pairBonus;

        let grandTotalNote = '';
        if (r.params.enablePairLimit && r.params.onlyPairsTillLimit) {
            grandTotalNote = ` <small class="text-muted">(@lang('Only capped levels included'))</small>`;
        }
        const grandTotalDisplayHtml = `<span class="fw-bold">${r.payoutGrandTotal.toLocaleString()}</span>${grandTotalNote}`;

        const totalRevenue = finalVouchersSold * r.params.voucherPrice;
        const totalBudgetPool = finalVouchersSold * r.params.budgetPerVoucher;
        const netPosition = totalBudgetPool - grandTotalBonusPayout;
        const isSecure = netPosition >= 0n;
        const conclusionClass = isSecure ? 'alert-success' : 'alert-danger';
        const conclusionTitle = isSecure ? '✅ @lang('Plan is Sustainable')' : '❌ @lang('Plan is Unsustainable')';
        const conclusionText = isSecure ? `@lang('The total bonus payout is within the allocated pairs budget.')` : `@lang('The total bonus payout EXCEEDS the allocated pairs budget.')`;

        const summaryHtml = `
            <div class="card"><div class="card-header"><h6 class="card-title">@lang('Overall Simulation Summary')</h6></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>@lang('Vouchers Sold (Users):')</strong> <span>${finalVouchersSold.toLocaleString()} <small class="text-muted">${voucherNote}</small></span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>@lang('Total Revenue Collected:')</strong> <span>${totalRevenue.toLocaleString()} ${r.params.currency}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>@lang('Total Pairs Budget Collected:')</strong> <span>${totalBudgetPool.toLocaleString()} ${r.params.currency}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white"><strong>@lang('Grand Total Pairs for Payout:'):</strong> ${grandTotalDisplayHtml}</li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white"><strong>@lang('Grand Total Bonus Payout:')</strong> <span class="fw-bold">${grandTotalBonusPayout.toLocaleString()} ${r.params.currency}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>@lang('Net Position (Budget - Payout):')</strong> <span class="fw-bold text-${isSecure ? 'success':'danger'}">${netPosition.toLocaleString()} ${r.params.currency}</span></li>
            </ul></div>
            <div class="card mt-3"><div class="card-header"><h6 class="card-title">@lang('Detailed Breakdown / Calculation')</h6></div><div class="card-body">${r.breakdownHtml}</div></div>
            <div class="alert ${conclusionClass} mt-3"><h4 class="alert-heading">${conclusionTitle}</h4><p>${conclusionText}</p></div>
        `;
        $('#simulation-results').html(summaryHtml);
    }
})(jQuery);
</script>
@endpush
