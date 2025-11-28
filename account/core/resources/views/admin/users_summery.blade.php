@extends('admin.layouts.app')

@section('panel')
<div class="row">
    {{-- Total DDS Users Card (Full Row) --}}
    <div class="col-lg-12 mb-30">
        <div class="dashboard-w1 bg--purple b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $totalDDSUsers }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total DDS Users')</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Other Summary Boxes --}}
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $totalFreeUsers }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total DDS Unpaid Users')</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--success b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-user-check"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $totalPaidUsers }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total DSP Paid Users')</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--info b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-briefcase"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $totalDsp }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total Own DSP Users')</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--warning b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-store"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $totalVendors }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total Vendors')</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6 col-sm-12 mb-30">
        <div class="dashboard-w1 bg--dark b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ showAmount($totalEarnings, currencyFormat: true) }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total Earnings of All Users')</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-sm-12 mb-30">
        <div class="dashboard-w1 bg--danger b-radius--10 box-shadow">
            <div class="icon">
                <i class="fa fa-user-plus"></i>
            </div>
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ showAmount($totalReferenceEarnings, currencyFormat: true) }}</span>
                </div>
                <div class="desciption">
                    <span>@lang('Total Reference Earnings of All Users')</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
				<div class="d-flex justify-content-end">
                <form action="{{ route('admin.users.summary') }}" method="GET" class="form-inline float-sm-right bg--white m-3">
                    <div class="input-group has_append">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search by Username')" value="{{ $search ?? '' }}">
                        
                            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                       
                    </div>
                </form>
					 </div>
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Ref By')</th>
                                <th>@lang('Under User')</th>
                                <th>@lang('Own Pairs')</th>
                                <th>@lang('Paid Left')</th>
                                <th>@lang('Paid Right')</th>
                                <th>@lang('Own DSP')</th>
                                <th>@lang('Own Vendors')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Deposit')</th>
                                <th>@lang('Withdraw')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Position')</th>
                                <th>@lang('Rank')</th>
                                <th>@lang('Level')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $posId = getPositionId($user->id);
                                    $positionalUser = $posId ? \App\Models\User::find($posId) : null;
                                    $positionName = mlmPositions()[getPositionLocation($user->id)] ?? 'N/A';
                                    $userRank = 'User';
                                    foreach ($ranks as $rank) {
                                        if ($user->pairs >= $rank->requirement) {
                                            $userRank = $rank->name;
                                        }
                                    }
                                    $level = getUserDepthInTree($user->id);
                                @endphp
                                <tr>
                                    <td data-label="@lang('S.N.')">{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                    <td data-label="@lang('Username')">
                                        <a href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a>
                                        <br>
                                        {{ $user->fullname }}
                                    </td>
                                    <td data-label="@lang('Ref By')">
                                        @if ($user->refBy)
                                            <a href="{{ route('admin.users.detail', $user->refBy->id) }}">{{ $user->refBy->username }}</a>
                                            <br>
                                            {{ $user->refBy->fullname }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td data-label="@lang('Under User')">
                                        @if ($positionalUser)
                                            <a href="{{ route('admin.users.detail', $positionalUser->id) }}">{{ $positionalUser->username }}</a>
                                            <br>
                                            {{ $positionalUser->fullname }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td data-label="@lang('Own Pairs')">{{ $user->pairs ?? 0 }}</td>
                                    <td data-label="@lang('Paid Left')">{{ $user->userExtra->paid_left ?? 0 }}</td>
                                    <td data-label="@lang('Paid Right')">{{ $user->userExtra->paid_right ?? 0 }}</td>
                                    <td data-label="@lang('Own DSP')">{{ $user->own_dsp ?? 0 }}</td>
                                    <td data-label="@lang('Own Vendors')">{{ $user->own_vendors ?? 0 }}</td>
                                    <td data-label="@lang('Balance')">{{ showAmount($user->balance) }}</td>
                                    <td data-label="@lang('Deposit')">{{ showAmount($user->total_deposit) }}</td>
                                    <td data-label="@lang('Withdraw')">{{ showAmount($user->total_withdraw) }}</td>
                                    <td data-label="@lang('City')">{{ $user->city ?? 'N/A' }}</td>
                                    <td data-label="@lang('Position')">{{ $positionName }}</td>
                                    <td data-label="@lang('Rank')">{{ $userRank }}</td>
                                    <td data-label="@lang('Level')">{{ $level }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">@lang('No users found.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table></div>
            </div>
            @if ($users->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($users->appends(['search' => $search])) }}
            </div>
            @endif
        </div></div>
</div>
@endsection

@push('style')
<style>
    @media (max-width: 991px) {
        .table.style--two tbody tr {
           border: 2px dashed #0000005c;
    display: block;
    margin-bottom: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);

    transition: box-shadow 0.3s ease-in-out;
        }
    }
</style>
@endpush
