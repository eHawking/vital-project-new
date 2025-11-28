@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="dashboard-header mb-4">
                <h2 class="page-title"><i class="bi bi-ticket-perforated"></i> @lang('DSP Vouchers')</h2>
                <p class="page-subtitle">@lang('Manage your DSP gift vouchers')</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-1">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Total Vouchers')</h6>
                        <h3 class="ammount theme-two">{{ $totalVouchers }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-ticket-perforated-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-2">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Used Vouchers')</h6>
                        <h3 class="ammount theme-two">{{ $usedVouchers }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-3">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Redeem Voucher')</h6>
                        <button class="btn btn-light btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#redeemVoucherModal">
                            <i class="bi bi-gift-fill"></i> @lang('Redeem Now')
                        </button>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-gift-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vouchers Table -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-item mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-list-ul"></i> @lang('My Vouchers')</h4>
                </div>
                <div class="table-responsive">
                    <table class="transection-table-2">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> @lang('No.')</th>
                                <th><i class="bi bi-upc"></i> @lang('Voucher Code')</th>
                                <th><i class="bi bi-calendar-plus"></i> @lang('Generated On')</th>
                                <th><i class="bi bi-check2-circle"></i> @lang('Status')</th>
                                <th><i class="bi bi-person"></i> @lang('Used By')</th>
                                <th><i class="bi bi-calendar-check"></i> @lang('Used On')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $currentPage = $vouchers->currentPage();
                            $perPage = $vouchers->perPage();
                            $startNumber = (($currentPage - 1) * $perPage) + 1;
                            @endphp
                            @forelse($vouchers as $key => $voucher)
                            <tr>
                                <td data-label="@lang('No.')">{{ $startNumber + $key }}</td>
                                <td data-label="@lang('Voucher Code')">
                                    <div class="voucher-code-box d-flex align-items-center gap-2">
                                        <code class="voucher-code">
                                            <span id="voucherCodePartial{{ $voucher->id }}">{{ substr($voucher->code, 0, 5) }}*****</span>
                                            <span id="voucherCodeFull{{ $voucher->id }}" style="display: none;">{{ $voucher->code }}</span>
                                        </code>
                                        <button class="btn btn-sm btn-outline-primary" onclick="toggleCodeVisibility('{{ $voucher->id }}')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="copyVoucherCode('{{ $voucher->code }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>
                                <td data-label="@lang('Generated On')">
                                    <small>{{ showDateTime($voucher->created_at, 'd M Y') }}</small>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($voucher->is_used)
                                        <span class="badge badge--success"><i class="bi bi-check-circle-fill"></i> @lang('Used')</span>
                                    @else
                                        <span class="badge badge--warning"><i class="bi bi-clock-fill"></i> @lang('Unused')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Used By')">
                                    @if($voucher->is_used && $voucher->used_by_id)
                                        @php
                                        $usedByUser = \App\Models\User::find($voucher->used_by_id);
                                        @endphp
                                        @if($usedByUser)
                                            <div class="user-info">
                                                <strong class="text-primary">{{ strtoupper($usedByUser->username) }}</strong><br>
                                                <small class="text-muted">{{ $usedByUser->fullname }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">@lang('User not found')</span>
                                        @endif
                                    @else
                                        <span class="text-muted"><i class="bi bi-dash-circle"></i> @lang('Not used yet')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Used On')">
                                    @if($voucher->used_at)
                                        <small>{{ showDateTime($voucher->used_at, 'd M Y') }}</small>
                                    @else
                                        <span class="text-muted">@lang('N/A')</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                    {{ __($emptyMessage) }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($vouchers->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    {{ paginateLinks($vouchers) }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

<div class="modal fade" id="redeemVoucherModal" tabindex="-1" aria-labelledby="redeemVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title" id="redeemVoucherModalLabel"><i class="bi bi-gift-fill"></i> @lang('Redeem Voucher')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="redeemVoucherForm" action="{{ route('user.voucher.redeem') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="voucherCodeInput">@lang('Enter Voucher Code')</label>
                        <input type="text" name="code" id="voucherCodeInput" class="form-control" required>
                    </div>

                    @if (auth()->user()->plan_id == 0)
                    {{-- SCENARIO 1: User is becoming a DSP --}}
                    <input type="hidden" name="redeem_type" value="self_activation">
                    <p class="mb-2 fw-bold text-center">@lang('Select Your Activation:')</p>
                    <div class="position-container justify-content-center">
                        <div class="position-item selectable-circle selected">
                            <div class="circle-icon"><i class="las la-user-check"></i></div>
                            <span>@lang('My DSP')</span>
                        </div>
                    </div>
                    <p class="text-center mt-2">@lang('Your plan will be activated after redeeming the voucher.')</p>

                    @elseif(auth()->user()->plan_id >= 1)
                    {{-- SCENARIO 2: User is creating a new DSP for someone else --}}
                    <input type="hidden" name="redeem_type" value="create_dsp">

                    <div class="form-group">
                        <label for="placementUsername" class="form-label">@lang('Placement Username')</label>
                        <input type="text" name="placement_username" id="placementUsername" class="form-control" required>
                        <div id="placementUserFeedback" class="mt-1"></div>
                    </div>

                    <div id="placementDetails" style="display: none;">
                        <label class="form-label mt-3">@lang('Select Position')</label>
                        <div class="position-container">
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

                    <div class="mt-3">
                        <button type="submit" class="btn btn--primary w-100">@lang('Redeem Voucher')</button>
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

@push('style')
<style>
/* DSP Vouchers Page Custom Styles */
.dashboard-header {
    text-align: center;
    padding: 20px 0;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    font-size: 16px;
    color: var(--text-secondary);
    opacity: 0.8;
}

/* Voucher Code Styling */
.voucher-code {
    background: rgba(102, 126, 234, 0.1);
    padding: 8px 12px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    font-weight: 600;
    color: var(--accent-blue);
}

.voucher-code-box {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Badge Styling */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge--success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3);
}

.badge--warning {
    background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(242, 153, 74, 0.3);
}

.badge i {
    font-size: 10px;
}

/* Table Enhancements */
.transection-table-2 {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.transection-table-2 thead th {
    background: var(--gradient-purple-blue);
    color: #ffffff;
    padding: 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none;
}

.transection-table-2 thead th:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

.transection-table-2 thead th:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.transection-table-2 thead th i {
    margin-right: 5px;
    opacity: 0.9;
}

.transection-table-2 tbody tr {
    background: var(--card-bg);
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.transection-table-2 tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.transection-table-2 tbody td {
    padding: 15px;
    color: var(--text-primary);
    border: none;
    vertical-align: middle;
}

.transection-table-2 tbody td:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    font-weight: 700;
    color: var(--accent-blue);
}

.transection-table-2 tbody td:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.text-primary {
    color: var(--accent-blue) !important;
}

/* Modern Modal */
.modern-modal .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.gradient-header {
    background: var(--gradient-purple-blue);
    color: #ffffff;
    padding: 20px;
    border: none;
}

.gradient-header .modal-title {
    font-weight: 700;
    font-size: 18px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }
    
    .voucher-code-box {
        flex-wrap: wrap;
    }
    
    .transection-table-2 {
        border-spacing: 0;
    }
    
    .transection-table-2 thead {
        display: none;
    }
    
    .transection-table-2 tbody tr {
        display: block;
        margin-bottom: 15px;
        border-radius: 10px;
    }
    
    .transection-table-2 tbody td {
        display: block;
        text-align: right;
        padding: 10px 15px;
        border-radius: 0 !important;
    }
    
    .transection-table-2 tbody td:first-child {
        border-top-left-radius: 10px !important;
        border-top-right-radius: 10px !important;
    }
    
    .transection-table-2 tbody td:last-child {
        border-bottom-left-radius: 10px !important;
        border-bottom-right-radius: 10px !important;
    }
    
    .transection-table-2 tbody td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: var(--text-secondary);
    }
    
    .badge {
        font-size: 10px;
        padding: 4px 10px;
    }
}

    .position-container {
        display: flex;
        gap: 20px;
        justify-content: space-around;
        margin-top: 10px;
    }
    .position-item.selectable-circle {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        border: 2px solid #ddd;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .position-item.selectable-circle.selected {
        border-color: #28a745;
        color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
    }
    .position-item.selectable-circle .circle-icon {
        font-size: 36px;
    }
    .position-item.selectable-circle span {
        font-weight: bold;
        margin-top: 5px;
    }
</style>
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
                            $('#joinUnderInfo').html(`<p class="text--success small">The new DSP will be placed under <strong>${response.join_under}</strong>.</p>`);
                        } else {
                            $('#joinUnderInfo').html(`<p class="text--danger small">${response.msg}</p>`);
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
        const eyeIcon = partialCode.nextElementSibling.querySelector('i');

        if (fullCode.style.display === 'none') {
            fullCode.style.display = 'inline';
            partialCode.style.display = 'none';
            eyeIcon.classList.replace('la-eye', 'la-eye-slash');
        } else {
            fullCode.style.display = 'none';
            partialCode.style.display = 'inline';
            eyeIcon.classList.replace('la-eye-slash', 'la-eye');
        }
    }

    function copyVoucherCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            notify('success', 'Voucher code copied successfully!');
        });
    }
</script>
@endpush