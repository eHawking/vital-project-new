<style>
    .premium-modal-content {
        background: rgba(30, 41, 59, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        color: white;
    }

    .premium-modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 20px 24px;
    }

    .premium-modal-title {
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .premium-modal-body {
        padding: 24px;
    }

    .premium-modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding: 20px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .premium-info-box {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .premium-info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .premium-info-item:last-child {
        margin-bottom: 0;
    }

    .premium-info-item strong {
        color: white;
        font-weight: 600;
    }

    .premium-input-group {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        padding: 5px;
        display: flex;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 15px;
    }

    .premium-input-field {
        background: transparent;
        border: none;
        color: var(--color-primary) !important;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 10px 15px;
        flex: 1;
        letter-spacing: 1px;
        font-family: monospace;
    }

    .premium-input-field:focus {
        outline: none;
        background: transparent;
        box-shadow: none;
    }

    .premium-copy-btn {
        background: var(--grad-primary);
        border: none;
        border-radius: 8px;
        color: white;
        padding: 0 20px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .premium-copy-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(var(--rgb-primary), 0.3);
    }

    .btn-premium-primary {
        background: var(--grad-primary);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-premium-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(var(--rgb-primary), 0.4);
        color: white;
    }

    .btn-premium-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-premium-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .btn-close-white {
        filter: invert(1);
        opacity: 0.7;
    }
</style>

<!-- GENERATE MODAL -->
<div class="modal fade" id="generateVoucherModal" tabindex="-1" aria-labelledby="generateVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header premium-modal-header">
                <h5 class="modal-title premium-modal-title" id="generateVoucherModalLabel">
                    <i class="las la-magic text-primary"></i> Generate DSP Voucher
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body premium-modal-body">
                @php
                    $userPV = auth()->user()->pv ?? 0;
                    $vouchersToGenerate = floor($userPV / 100);
                @endphp
                
                <div class="text-center mb-4">
                    <div style="width: 60px; height: 60px; background: rgba(var(--rgb-primary), 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="las la-ticket-alt text-primary" style="font-size: 32px;"></i>
                    </div>
                    <h5 class="text-white">Create New Voucher</h5>
                    <p class="text-white-50 mb-0">Convert your PV into DSP Vouchers</p>
                </div>

                <div class="premium-info-box">
                    <div class="premium-info-item">
                        <span>Available PV</span>
                        <strong>{{ number_format($userPV, 2) }} PV</strong>
                    </div>
                    <div class="premium-info-item">
                        <span>Cost per Voucher</span>
                        <strong>100.00 PV</strong>
                    </div>
                    <div class="premium-info-item">
                        <span>Max Convertible</span>
                        <strong class="text-primary">{{ $vouchersToGenerate }} Vouchers</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer premium-modal-footer">
                <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Cancel</button>
                @if ($vouchersToGenerate > 0)
                  <button type="button" id="proceedToConfirmBtn" class="btn btn-premium-primary">
                      Next Step <i class="las la-arrow-right ms-1"></i>
                  </button>
                @else
                    <button type="button" class="btn btn-premium-primary" disabled>Insufficient PV</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header premium-modal-header">
                <h5 class="modal-title premium-modal-title" id="confirmationModalLabel">
                    <i class="las la-exclamation-circle text-warning"></i> Confirm Action
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body premium-modal-body text-center">
                <div style="width: 80px; height: 80px; background: rgba(255, 193, 7, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="las la-question text-warning" style="font-size: 40px;"></i>
                </div>
                
                <h4 class="text-white mb-3">Are you sure?</h4>
                <p class="text-white-50 mb-4">
                    You are about to generate a voucher. <br>
                    <strong class="text-danger">100 PV</strong> will be deducted from your account immediately.
                </p>
            </div>
            <div class="modal-footer premium-modal-footer justify-content-center">
                <button type="button" class="btn btn-premium-secondary" data-bs-target="#generateVoucherModal" data-bs-toggle="modal">Go Back</button>
                <button type="button" id="confirmYes" class="btn btn-premium-primary">
                    <i class="las la-check-circle me-1"></i> Yes, Generate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="generatedCodeModal" tabindex="-1" aria-labelledby="generatedCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal-content">
            <div class="modal-header premium-modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body premium-modal-body text-center pt-0">
                <div style="width: 80px; height: 80px; background: rgba(46, 204, 113, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="las la-check text-success" style="font-size: 40px;"></i>
                </div>
                
                <h4 class="text-white mb-2">Success!</h4>
                <p class="text-white-50 mb-4">Your voucher has been generated successfully.</p>
                
                <div class="premium-input-group">
                    <input type="text" id="generatedVoucherCode" value="" readonly class="premium-input-field form-control" placeholder="Code...">
                    <button class="premium-copy-btn" type="button" id="copyVoucherCode">
                        <i class="las la-copy"></i> Copy
                    </button>
                </div>
                <div id="copySuccessMessage" class="text-success mt-2 small fw-bold" style="display: none; opacity: 0; transition: opacity 0.3s;">
                    <i class="las la-check-circle"></i> Copied to clipboard!
                </div>
            </div>
            <div class="modal-footer premium-modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-premium-secondary w-50" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>