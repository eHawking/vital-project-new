@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    @media (max-width: 768px) {
        .inner-dashboard-container { padding: 10px !important; }
        .premium-card { border-radius: 12px !important; }
        .stats-grid { grid-template-columns: 1fr !important; }
    }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
    .table-custom th { background: rgba(128,128,128,0.05) !important; color: var(--text-muted) !important; font-weight: 600; padding: 12px 15px; border-bottom: 1px solid rgba(128,128,128,0.1) !important; }
    .table-custom td { padding: 12px 15px; border-bottom: 1px solid rgba(128,128,128,0.05) !important; color: var(--text-primary) !important; }
    .table-custom tbody tr:hover { background: rgba(128,128,128,0.03) !important; }
    .mobile-card-item { background: rgba(128,128,128,0.03); border: 1px solid rgba(128,128,128,0.1); border-radius: 12px; padding: 15px; margin-bottom: 10px; }
</style>

<div class="container-fluid px-4 py-3 inner-dashboard-container">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="m-0"><i class="bi bi-ticket-perforated"></i> @lang('DSP Vouchers')</h4>
            <p class="text-muted small m-0">@lang('Manage your DSP gift vouchers')</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-4">
        <div class="premium-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small">@lang('Total Vouchers')</p>
                    <h3 class="m-0 fw-bold">{{ $totalVouchers }}</h3>
                </div>
                <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-ticket-perforated-fill fs-4"></i>
                </div>
            </div>
        </div>
        <div class="premium-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small">@lang('Used Vouchers')</p>
                    <h3 class="m-0 fw-bold">{{ $usedVouchers }}</h3>
                </div>
                <div class="icon-box variant-green" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
            </div>
        </div>
        <div class="premium-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small">@lang('Redeem Voucher')</p>
                    <button class="btn btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#redeemVoucherModal" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; color: #fff;">
                        <i class="bi bi-gift-fill"></i> @lang('Redeem')
                    </button>
                </div>
                <div class="icon-box variant-pink" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-gift-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Vouchers Table -->
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
                            <div class="d-flex align-items-center gap-2">
                                <code class="text-info" style="background: rgba(13,202,240,0.1); border-radius: 5px; padding: 5px 10px;">
                                    <span id="voucherCodePartial{{ $voucher->id }}">{{ substr($voucher->code, 0, 5) }}*****</span>
                                    <span id="voucherCodeFull{{ $voucher->id }}" style="display: none;">{{ $voucher->code }}</span>
                                </code>
                                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCodeVisibility('{{ $voucher->id }}')"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-success" onclick="copyVoucherCode('{{ $voucher->code }}')"><i class="bi bi-clipboard"></i></button>
                            </div>
                        </td>
                        <td><small>{{ showDateTime($voucher->created_at, 'd M Y') }}</small></td>
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
                                <small>{{ showDateTime($voucher->used_at, 'd M Y') }}</small>
                            @else
                                <span class="text-muted">@lang('N/A')</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>{{ __($emptyMessage) }}
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
                        <span class="text-muted small">#{{ $startNumber + $key }}</span>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <code class="text-info small" style="background: rgba(13,202,240,0.1); border-radius: 5px; padding: 3px 8px;">{{ substr($voucher->code, 0, 8) }}...</code>
                            <button class="btn btn-sm btn-outline-success py-0 px-2" onclick="copyVoucherCode('{{ $voucher->code }}')"><i class="bi bi-clipboard"></i></button>
                        </div>
                    </div>
                    @if($voucher->is_used)
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Used')</span>
                    @else
                        <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-clock-fill"></i> @lang('Unused')</span>
                    @endif
                </div>
                <div class="row g-2">
                    <div class="col-6"><small class="text-muted">@lang('Generated'):</small><br><strong>{{ showDateTime($voucher->created_at, 'd M Y') }}</strong></div>
                    <div class="col-6">
                        <small class="text-muted">@lang('Used By'):</small><br>
                        @if($voucher->is_used && $voucher->used_by_id)
                            @php $usedByUser = \App\Models\User::find($voucher->used_by_id); @endphp
                            <strong class="text-primary">{{ $usedByUser ? strtoupper($usedByUser->username) : 'N/A' }}</strong>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>{{ __($emptyMessage) }}
            </div>
            @endforelse
        </div>

        @if ($vouchers->hasPages())
        <div class="mt-3">{{ paginateLinks($vouchers) }}</div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

<div class="modal fade premium-modal" id="redeemVoucherModal" tabindex="-1" aria-labelledby="redeemVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid rgba(128,128,128,0.1); border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border-radius: 16px 16px 0 0;">
                <h5 class="modal-title text-white" id="redeemVoucherModalLabel"><i class="bi bi-gift-fill me-2"></i> @lang('Redeem Voucher')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="redeemVoucherForm" action="{{ route('user.voucher.redeem') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="voucherCodeInput" class="form-label text-muted small text-uppercase">@lang('Enter Voucher Code')</label>
                        <input type="text" name="code" id="voucherCodeInput" class="form-control" style="background: rgba(128,128,128,0.05); border: 1px solid rgba(128,128,128,0.2); color: var(--text-primary); border-radius: 10px; padding: 12px;" required>
                    </div>

                    @if (auth()->user()->plan_id == 0)
                    <input type="hidden" name="redeem_type" value="self_activation">
                    <p class="mb-2 fw-bold text-center">@lang('Select Your Activation:')</p>
                    <div class="position-container justify-content-center d-flex gap-3">
                        <div class="position-item selectable-circle selected">
                            <div class="circle-icon text-success"><i class="las la-user-check"></i></div>
                            <span>@lang('My DSP')</span>
                        </div>
                    </div>
                    <p class="text-center mt-2 text-muted small">@lang('Your plan will be activated after redeeming the voucher.')</p>

                    @elseif(auth()->user()->plan_id >= 1)
                    <input type="hidden" name="redeem_type" value="create_dsp">
                    <div class="form-group">
                        <label for="placementUsername" class="form-label text-muted small text-uppercase">@lang('Placement Username')</label>
                        <input type="text" name="placement_username" id="placementUsername" class="form-control" style="background: rgba(128,128,128,0.05); border: 1px solid rgba(128,128,128,0.2); color: var(--text-primary); border-radius: 10px; padding: 12px;" required>
                        <div id="placementUserFeedback" class="mt-1"></div>
                    </div>

                    <div id="placementDetails" style="display: none;">
                        <label class="form-label mt-3 text-muted small text-uppercase">@lang('Select Position')</label>
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
                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; padding: 12px; font-weight: 600; color: #fff; border-radius: 10px;">@lang('Redeem Voucher')</button>
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
    /* Custom Styles for DSP Vouchers */
    .selectable-circle {
        cursor: pointer;
        border: 2px solid rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 15px;
        width: 100px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .selectable-circle:hover {
        background: rgba(255,255,255,0.05);
    }
    .selectable-circle.selected {
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.1);
    }
    .selectable-circle.selected .circle-icon {
        color: #28a745 !important;
    }
    .selectable-circle .circle-icon {
        font-size: 24px;
        margin-bottom: 5px;
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