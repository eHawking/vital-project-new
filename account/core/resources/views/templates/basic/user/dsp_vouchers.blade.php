@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row mb-4">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="text-white m-0"><i class="bi bi-ticket-perforated"></i> @lang('DSP Vouchers')</h4>
                <p class="text-white-50 small m-0">@lang('Manage your DSP gift vouchers')</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-3">
    <div class="col-lg-4 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Total Vouchers')</h6>
                    <h3 class="text-white m-0">{{ $totalVouchers }}</h3>
                </div>
                <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-ticket-perforated-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Used Vouchers')</h6>
                    <h3 class="text-white m-0">{{ $usedVouchers }}</h3>
                </div>
                <div class="icon-box variant-green" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Redeem Voucher')</h6>
                    <button class="btn btn-primary btn-sm mt-2 pulse-animation" data-bs-toggle="modal" data-bs-target="#redeemVoucherModal" style="background: var(--grad-primary); border: none;">
                        <i class="bi bi-gift-fill"></i> @lang('Redeem Now')
                    </button>
                </div>
                <div class="icon-box variant-pink" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-gift-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vouchers Table -->
<div class="premium-card mb-4">
    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
        <h5 class="title text-white m-0"><i class="bi bi-list-ul"></i> @lang('My Vouchers')</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-hash"></i> @lang('No.')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-upc"></i> @lang('Voucher Code')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-calendar-plus"></i> @lang('Generated On')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-check2-circle"></i> @lang('Status')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-person"></i> @lang('Used By')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-calendar-check"></i> @lang('Used On')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $currentPage = $vouchers->currentPage();
                    $perPage = $vouchers->perPage();
                    $startNumber = (($currentPage - 1) * $perPage) + 1;
                    @endphp
                    @forelse($vouchers as $key => $voucher)
                    <tr style="background: rgba(255,255,255,0.05);">
                        <td data-label="@lang('No.')" class="text-white">{{ $startNumber + $key }}</td>
                        <td data-label="@lang('Voucher Code')" class="text-white">
                            <div class="voucher-code-box d-flex align-items-center gap-2">
                                <code class="voucher-code text-info bg-transparent border border-info" style="border-radius: 5px; padding: 5px;">
                                    <span id="voucherCodePartial{{ $voucher->id }}">{{ substr($voucher->code, 0, 5) }}*****</span>
                                    <span id="voucherCodeFull{{ $voucher->id }}" style="display: none;">{{ $voucher->code }}</span>
                                </code>
                                <button class="btn btn-sm btn-outline-light border-secondary" onclick="toggleCodeVisibility('{{ $voucher->id }}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="copyVoucherCode('{{ $voucher->code }}')">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </td>
                        <td data-label="@lang('Generated On')" class="text-white">
                            <small>{{ showDateTime($voucher->created_at, 'd M Y') }}</small>
                        </td>
                        <td data-label="@lang('Status')" class="text-white">
                            @if($voucher->is_used)
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Used')</span>
                            @else
                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill"><i class="bi bi-clock-fill"></i> @lang('Unused')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Used By')" class="text-white">
                            @if($voucher->is_used && $voucher->used_by_id)
                                @php
                                $usedByUser = \App\Models\User::find($voucher->used_by_id);
                                @endphp
                                @if($usedByUser)
                                    <div class="user-info">
                                        <strong class="text-primary">{{ strtoupper($usedByUser->username) }}</strong><br>
                                        <small class="text-white-50">{{ $usedByUser->fullname }}</small>
                                    </div>
                                @else
                                    <span class="text-white-50">@lang('User not found')</span>
                                @endif
                            @else
                                <span class="text-white-50"><i class="bi bi-dash-circle"></i> @lang('Not used yet')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Used On')" class="text-white">
                            @if($voucher->used_at)
                                <small>{{ showDateTime($voucher->used_at, 'd M Y') }}</small>
                            @else
                                <span class="text-white-50">@lang('N/A')</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-white-50">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                            {{ __($emptyMessage) }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($vouchers->hasPages())
        <div class="p-3">
            {{ paginateLinks($vouchers) }}
        </div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

<div class="modal fade" id="redeemVoucherModal" tabindex="-1" aria-labelledby="redeemVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="redeemVoucherModalLabel"><i class="bi bi-gift-fill"></i> @lang('Redeem Voucher')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="redeemVoucherForm" action="{{ route('user.voucher.redeem') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="voucherCodeInput" class="form-label text-white-50">@lang('Enter Voucher Code')</label>
                        <input type="text" name="code" id="voucherCodeInput" class="form-control bg-transparent text-white border-secondary" required>
                    </div>

                    @if (auth()->user()->plan_id == 0)
                    {{-- SCENARIO 1: User is becoming a DSP --}}
                    <input type="hidden" name="redeem_type" value="self_activation">
                    <p class="mb-2 fw-bold text-center text-white">@lang('Select Your Activation:')</p>
                    <div class="position-container justify-content-center d-flex gap-3">
                        <div class="position-item selectable-circle selected bg-transparent border-secondary">
                            <div class="circle-icon text-success"><i class="las la-user-check"></i></div>
                            <span class="text-white">@lang('My DSP')</span>
                        </div>
                    </div>
                    <p class="text-center mt-2 text-white-50 small">@lang('Your plan will be activated after redeeming the voucher.')</p>

                    @elseif(auth()->user()->plan_id >= 1)
                    {{-- SCENARIO 2: User is creating a new DSP for someone else --}}
                    <input type="hidden" name="redeem_type" value="create_dsp">

                    <div class="form-group">
                        <label for="placementUsername" class="form-label text-white-50">@lang('Placement Username')</label>
                        <input type="text" name="placement_username" id="placementUsername" class="form-control bg-transparent text-white border-secondary" required>
                        <div id="placementUserFeedback" class="mt-1"></div>
                    </div>

                    <div id="placementDetails" style="display: none;">
                        <label class="form-label mt-3 text-white-50">@lang('Select Position')</label>
                        <div class="position-container d-flex gap-3 justify-content-center">
                            <div class="position-item selectable-circle bg-transparent border-secondary" data-position="1">
                                <div class="circle-icon text-white"><i class="las la-arrow-left"></i></div>
                                <span class="text-white">@lang('Left')</span>
                            </div>
                            <div class="position-item selectable-circle bg-transparent border-secondary" data-position="2">
                                <div class="circle-icon text-white"><i class="las la-arrow-right"></i></div>
                                <span class="text-white">@lang('Right')</span>
                            </div>
                        </div>
                        <input type="hidden" name="position" id="selectedPosition">
                        <div id="joinUnderInfo" class="mt-2 text-center"></div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 pulse-animation" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;">@lang('Redeem Voucher')</button>
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