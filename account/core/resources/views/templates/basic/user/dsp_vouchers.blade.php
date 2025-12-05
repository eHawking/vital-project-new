@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    /* Theme Text Colors */
    h1, h2, h3, h4, h5, h6 {
        color: #ffffff;
    }
    [data-theme="light"] h1,
    [data-theme="light"] h2,
    [data-theme="light"] h3,
    [data-theme="light"] h4,
    [data-theme="light"] h5,
    [data-theme="light"] h6 {
        color: #1a1f2e;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .page-subtitle {
        color: #6c757d;
    }

    /* Mobile Full Width Adjustments */
    @media (max-width: 768px) {
        .inner-dashboard-container,
        .container-fluid.px-4,
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            width: 100% !important;
            margin: 0 !important;
        }
        .premium-card {
            width: 100% !important;
            margin-bottom: 10px;
            border-radius: 12px !important;
        }
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .stats-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Stat Card */
    .stat-card {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 20px;
    }
    [data-theme="light"] .stat-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .stat-card h3 {
        color: #ffffff;
    }
    [data-theme="light"] .stat-card h3 {
        color: #1a1f2e;
    }
    .stat-card .stat-label {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .stat-card .stat-label {
        color: #6c757d;
    }

    /* Table Custom */
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom th {
        background: rgba(128,128,128,0.05) !important;
        color: rgba(255,255,255,0.6) !important;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.1) !important;
    }
    [data-theme="light"] .table-custom th {
        color: #6c757d !important;
    }
    .table-custom td {
        color: #ffffff !important;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.05) !important;
        vertical-align: middle;
    }
    [data-theme="light"] .table-custom td {
        color: #1a1f2e !important;
    }
    .table-custom tbody tr:hover {
        background: rgba(128,128,128,0.03) !important;
    }

    /* Mobile Card Item */
    .mobile-card-item {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        color: #ffffff;
    }
    [data-theme="light"] .mobile-card-item {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .mobile-card-item .text-muted-custom {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .mobile-card-item .text-muted-custom {
        color: #6c757d;
    }

    /* Premium Pagination */
    .premium-pagination .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 10px;
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .premium-pagination .page-btn {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .premium-pagination .page-btn:hover {
        background: var(--color-primary);
        border-color: var(--color-primary);
        color: #fff;
    }
    .premium-pagination .page-btn.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-color: transparent;
        color: #fff;
    }
    .premium-pagination .page-btn.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
    .premium-pagination .page-dots {
        color: rgba(255,255,255,0.5);
        padding: 0 5px;
    }
    [data-theme="light"] .premium-pagination .page-dots {
        color: #6c757d;
    }

    /* Voucher Code Box */
    .voucher-code-box code {
        background: rgba(13, 202, 240, 0.1) !important;
        color: #0dcaf0 !important;
    }

    /* Modal Styling */
    .premium-modal .modal-content {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
    }
    [data-theme="light"] .premium-modal .modal-content {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .premium-modal .modal-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-radius: 16px 16px 0 0;
        border: none;
        padding: 20px;
    }
    .premium-modal .modal-title {
        color: #ffffff !important;
    }
    .premium-modal .modal-body {
        padding: 25px;
    }
    .premium-modal .form-label {
        color: rgba(255,255,255,0.6);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    [data-theme="light"] .premium-modal .form-label {
        color: #6c757d;
    }
    .premium-modal .form-control {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        border-radius: 10px;
        padding: 12px 15px;
    }
    [data-theme="light"] .premium-modal .form-control {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .premium-modal .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
    .premium-modal .modal-text {
        color: #ffffff;
    }
    [data-theme="light"] .premium-modal .modal-text {
        color: #1a1f2e;
    }
    .premium-modal .modal-text-muted {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .premium-modal .modal-text-muted {
        color: #6c757d;
    }

    /* Selectable Circles */
    .selectable-circle {
        cursor: pointer;
        border: 2px solid rgba(128,128,128,0.3);
        border-radius: 12px;
        padding: 15px;
        width: 100px;
        text-align: center;
        transition: all 0.3s ease;
        background: #242938;
    }
    [data-theme="light"] .selectable-circle {
        background: #f8f9fa;
        border: 2px solid rgba(0,0,0,0.1);
    }
    .selectable-circle:hover {
        border-color: var(--color-primary);
    }
    .selectable-circle.selected {
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.15);
    }
    .selectable-circle.selected .circle-icon {
        color: #28a745 !important;
    }
    .selectable-circle .circle-icon {
        font-size: 24px;
        margin-bottom: 5px;
        color: #ffffff;
    }
    [data-theme="light"] .selectable-circle .circle-icon {
        color: #1a1f2e;
    }
    .selectable-circle span {
        color: #ffffff;
    }
    [data-theme="light"] .selectable-circle span {
        color: #1a1f2e;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-ticket-perforated"></i> @lang('DSP Vouchers')</h4>
        <p class="page-subtitle small m-0">@lang('Manage your DSP gift vouchers')</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1 small">@lang('Total Vouchers')</p>
                    <h3 class="mb-0 fw-bold">{{ $totalVouchers }}</h3>
                </div>
                <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-ticket-perforated-fill fs-4"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1 small">@lang('Used Vouchers')</p>
                    <h3 class="mb-0 fw-bold">{{ $usedVouchers }}</h3>
                </div>
                <div class="icon-box variant-green" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1 small">@lang('Redeem Voucher')</p>
                    <button class="btn btn-sm mt-2 pulse-animation" data-bs-toggle="modal" data-bs-target="#redeemVoucherModal" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; color: #fff;">
                        <i class="bi bi-gift-fill"></i> @lang('Redeem Now')
                    </button>
                </div>
                <div class="icon-box variant-pink" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-gift-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Vouchers Card -->
    <div class="premium-card mb-4">
        <h5 class="mb-4"><i class="bi bi-list-ul"></i> @lang('My Vouchers')</h5>

        @php
            $currentPage = $vouchers->currentPage();
            $perPage = $vouchers->perPage();
            $startNumber = (($currentPage - 1) * $perPage) + 1;
        @endphp

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>@lang('No.')</th>
                        <th>@lang('Voucher Code')</th>
                        <th>@lang('Generated On')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Used By')</th>
                        <th>@lang('Used On')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $key => $voucher)
                        <tr>
                            <td class="fw-bold">{{ $startNumber + $key }}</td>
                            <td>
                                <div class="voucher-code-box d-flex align-items-center gap-2">
                                    <code style="border-radius: 5px; padding: 5px 10px;">
                                        <span id="voucherCodePartial{{ $voucher->id }}">{{ substr($voucher->code, 0, 5) }}*****</span>
                                        <span id="voucherCodeFull{{ $voucher->id }}" style="display: none;">{{ $voucher->code }}</span>
                                    </code>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleCodeVisibility('{{ $voucher->id }}')">
                                        <i class="bi bi-eye" id="eyeIcon{{ $voucher->id }}"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="copyVoucherCode('{{ $voucher->code }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </td>
                            <td>{{ showDateTime($voucher->created_at, 'd M Y') }}</td>
                            <td>
                                @if($voucher->is_used)
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Used')</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-clock-fill"></i> @lang('Unused')</span>
                                @endif
                            </td>
                            <td>
                                @if($voucher->is_used && $voucher->used_by_id)
                                    @php $usedByUser = \App\Models\User::find($voucher->used_by_id); @endphp
                                    @if($usedByUser)
                                        <strong class="text-primary">{{ strtoupper($usedByUser->username) }}</strong><br>
                                        <small class="text-muted">{{ $usedByUser->fullname }}</small>
                                    @else
                                        <span class="text-muted">@lang('User not found')</span>
                                    @endif
                                @else
                                    <span class="text-muted"><i class="bi bi-dash-circle"></i> @lang('Not used yet')</span>
                                @endif
                            </td>
                            <td>
                                @if($voucher->used_at)
                                    {{ showDateTime($voucher->used_at, 'd M Y') }}
                                @else
                                    <span class="text-muted">@lang('N/A')</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                {{ __($emptyMessage) }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none">
            @forelse($vouchers as $key => $voucher)
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted-custom small">#{{ $startNumber + $key }}</span>
                            <div class="voucher-code-box mt-1">
                                <code style="border-radius: 5px; padding: 3px 8px; font-size: 0.85rem;">
                                    <span id="voucherCodePartialMobile{{ $voucher->id }}">{{ substr($voucher->code, 0, 5) }}*****</span>
                                    <span id="voucherCodeFullMobile{{ $voucher->id }}" style="display: none;">{{ $voucher->code }}</span>
                                </code>
                            </div>
                        </div>
                        @if($voucher->is_used)
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Used')</span>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-clock-fill"></i> @lang('Unused')</span>
                        @endif
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleCodeVisibilityMobile('{{ $voucher->id }}')">
                            <i class="bi bi-eye" id="eyeIconMobile{{ $voucher->id }}"></i> @lang('Show')
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="copyVoucherCode('{{ $voucher->code }}')">
                            <i class="bi bi-clipboard"></i> @lang('Copy')
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted-custom">@lang('Generated'):</small><br>
                            <strong>{{ showDateTime($voucher->created_at, 'd M Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom">@lang('Used On'):</small><br>
                            <strong>{{ $voucher->used_at ? showDateTime($voucher->used_at, 'd M Y') : 'N/A' }}</strong>
                        </div>
                        @if($voucher->is_used && $voucher->used_by_id)
                            @php $usedByUser = \App\Models\User::find($voucher->used_by_id); @endphp
                            @if($usedByUser)
                                <div class="col-12">
                                    <small class="text-muted-custom">@lang('Used By'):</small><br>
                                    <strong class="text-primary">{{ strtoupper($usedByUser->username) }}</strong>
                                    <small class="text-muted-custom">- {{ $usedByUser->fullname }}</small>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <span class="text-muted-custom">{{ __($emptyMessage) }}</span>
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if($vouchers->hasPages())
            <div class="premium-pagination mt-4">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    @if ($vouchers->onFirstPage())
                        <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $vouchers->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                    @endif

                    @php
                        $currentPg = $vouchers->currentPage();
                        $lastPage = $vouchers->lastPage();
                        $start = max(1, $currentPg - 2);
                        $end = min($lastPage, $currentPg + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $vouchers->url(1) }}" class="page-btn">1</a>
                        @if($start > 2)<span class="page-dots">...</span>@endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $vouchers->url($i) }}" class="page-btn {{ $i == $currentPg ? 'active' : '' }}">{{ $i }}</a>
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)<span class="page-dots">...</span>@endif
                        <a href="{{ $vouchers->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                    @endif

                    @if ($vouchers->hasMorePages())
                        <a href="{{ $vouchers->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">@lang('Page') {{ $vouchers->currentPage() }} @lang('of') {{ $vouchers->lastPage() }} ({{ $vouchers->total() }} @lang('items'))</small>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

<!-- Premium Modal -->
<div class="modal fade premium-modal" id="redeemVoucherModal" tabindex="-1" aria-labelledby="redeemVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="redeemVoucherModalLabel"><i class="bi bi-gift-fill me-2"></i>@lang('Redeem Voucher')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="redeemVoucherForm" action="{{ route('user.voucher.redeem') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="voucherCodeInput" class="form-label">@lang('Enter Voucher Code')</label>
                        <input type="text" name="code" id="voucherCodeInput" class="form-control" placeholder="@lang('Enter your voucher code')" required>
                    </div>

                    @if (auth()->user()->plan_id == 0)
                        <input type="hidden" name="redeem_type" value="self_activation">
                        <p class="mb-2 fw-bold text-center modal-text">@lang('Select Your Activation:')</p>
                        <div class="position-container justify-content-center d-flex gap-3">
                            <div class="position-item selectable-circle selected">
                                <div class="circle-icon text-success"><i class="las la-user-check"></i></div>
                                <span>@lang('My DSP')</span>
                            </div>
                        </div>
                        <p class="text-center mt-2 modal-text-muted small">@lang('Your plan will be activated after redeeming the voucher.')</p>

                    @elseif(auth()->user()->plan_id >= 1)
                        <input type="hidden" name="redeem_type" value="create_dsp">
                        <div class="form-group">
                            <label for="placementUsername" class="form-label">@lang('Placement Username')</label>
                            <input type="text" name="placement_username" id="placementUsername" class="form-control" placeholder="@lang('Enter placement username')" required>
                            <div id="placementUserFeedback" class="mt-1"></div>
                        </div>

                        <div id="placementDetails" style="display: none;">
                            <label class="form-label mt-3">@lang('Select Position')</label>
                            <div class="position-container d-flex gap-3 justify-content-center">
                                <div class="position-item selectable-circle" data-position="1">
                                    <div class="circle-icon"><i class="las la-arrow-left"></i></div>
                                    <span>@lang('Left')</span>
                                </div>
                                <div class="position-item selectable-circle" data-position="2">
                                    <div class="circle-icon"><i class="las la-arrow-right"></i></div>
                                    <span>@lang('Right')</span>
                                </div>
                            </div>
                            <input type="hidden" name="position" id="selectedPosition">
                            <div id="joinUnderInfo" class="mt-2 text-center"></div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <button type="submit" class="btn w-100 pulse-animation" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; padding: 12px; font-weight: 600; color: #fff; border-radius: 10px;">@lang('Redeem Voucher')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush

@push('script-lib')
<script>
    (function($) {
        "use strict";

        function handleRedemptionLogic() {
            let placementUserId = null;

            $('#placementUsername').on('focusout', function() {
                let username = $(this).val().trim();
                $('#placementDetails').slideUp();
                $('#joinUnderInfo').html('');
                $('#selectedPosition').val('');
                $('.position-item.selectable-circle').removeClass('selected');
                
                if (!username) {
                    $('#placementUserFeedback').html('');
                    return;
                }

                $.ajax({
                    url: "{{ route('user.voucher.check_placement_user') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}", username: username },
                    success: function(response) {
                        if (response.success) {
                            placementUserId = response.user.id;
                            $('#placementUserFeedback').html(`<span class="text-success">${response.msg}</span>`);
                            $('#placementDetails').slideDown();
                        } else {
                            placementUserId = null;
                            $('#placementUserFeedback').html(`<span class="text-danger">${response.msg}</span>`);
                        }
                    }
                });
            });

            $('.position-item.selectable-circle').on('click', function() {
                $('.position-item.selectable-circle').removeClass('selected');
                $(this).addClass('selected');
                let position = $(this).data('position');
                $('#selectedPosition').val(position);

                if (!placementUserId || !position) return;

                $.ajax({
                    url: "{{ route('user.voucher.get_placement_position') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}", referrer_id: placementUserId, position: position },
                    success: function(response) {
                        if (response.success) {
                            $('#joinUnderInfo').html(`<p class="text-success small">The new DSP will be placed under <strong>${response.join_under}</strong>.</p>`);
                        } else {
                            $('#joinUnderInfo').html(`<p class="text-danger small">${response.msg}</p>`);
                        }
                    }
                });
            });
        }

        if ('{{ auth()->user()->plan_id }}' >= 1) {
            handleRedemptionLogic();
        }

        $('#redeemVoucherForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message);
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        notify('error', response.message);
                        submitBtn.prop('disabled', false).text(originalBtnText);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An unexpected error occurred.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).map(e => e[0]).join(' ');
                        } else if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }
                    notify('error', errorMessage);
                    submitBtn.prop('disabled', false).text(originalBtnText);
                }
            });
        });

    })(jQuery);

    function toggleCodeVisibility(voucherId) {
        const partialCode = document.getElementById(`voucherCodePartial${voucherId}`);
        const fullCode = document.getElementById(`voucherCodeFull${voucherId}`);
        const eyeIcon = document.getElementById(`eyeIcon${voucherId}`);

        if (fullCode.style.display === 'none') {
            fullCode.style.display = 'inline';
            partialCode.style.display = 'none';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            fullCode.style.display = 'none';
            partialCode.style.display = 'inline';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    function toggleCodeVisibilityMobile(voucherId) {
        const partialCode = document.getElementById(`voucherCodePartialMobile${voucherId}`);
        const fullCode = document.getElementById(`voucherCodeFullMobile${voucherId}`);
        const eyeIcon = document.getElementById(`eyeIconMobile${voucherId}`);

        if (fullCode.style.display === 'none') {
            fullCode.style.display = 'inline';
            partialCode.style.display = 'none';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            fullCode.style.display = 'none';
            partialCode.style.display = 'inline';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    function copyVoucherCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            notify('success', 'Voucher code copied successfully!');
        });
    }
</script>
@endpush