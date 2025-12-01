<!-- Generate Voucher Modal -->
<div class="modal fade" id="generateVoucherModal" tabindex="-1" aria-labelledby="generateVoucherModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <div class="modal-header border-0" style="background: linear-gradient(to right, rgba(var(--rgb-primary), 0.1), rgba(var(--rgb-primary), 0.05));">
                <h5 class="modal-title text-white fw-bold" id="generateVoucherModalLabel">
                    <i class="las la-ticket-alt me-2 text-primary"></i>Generate DSP Voucher
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="icon-box mx-auto mb-3 variant-primary" style="width: 80px; height: 80px; font-size: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(var(--rgb-primary), 0.1); color: var(--color-primary);">
                        <i class="las la-gift"></i>
                    </div>
                    <h4 class="text-white mb-1">Generate Your Reward</h4>
                    <p class="text-white-50">Convert your PV points into valuable vouchers</p>
                </div>

                @php
                    $userPV = auth()->user()->pv ?? 0;
                    $vouchersToGenerate = floor($userPV / 100);
                @endphp

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="text-white-50 d-block mb-1">Available PV</small>
                            <h4 class="text-primary mb-0">{{ number_format($userPV, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="text-white-50 d-block mb-1">Voucher Cost</small>
                            <h4 class="text-white mb-0">100 <small class="fs-6 text-muted">PV</small></h4>
                        </div>
                    </div>
                </div>

                <div class="alert" style="background: rgba(var(--rgb-primary), 0.1); border: 1px solid rgba(var(--rgb-primary), 0.2); border-radius: 12px;">
                    <div class="d-flex align-items-center gap-3">
                        <i class="las la-info-circle fs-3 text-primary"></i>
                        <div class="text-start">
                            <p class="text-white mb-0 small">You can currently generate <strong class="text-white">{{ $vouchersToGenerate }}</strong> {{ $vouchersToGenerate == 1 ? 'voucher' : 'vouchers' }}.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button" class="btn btn-dark btn-sm text-white-50" data-bs-dismiss="modal">Close</button>
                @if ($vouchersToGenerate > 0)
                    <button type="button" id="proceedToConfirmBtn" class="btn btn-primary btn-sm px-4">
                        Generate Now <i class="las la-arrow-right ms-1"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-secondary btn-sm px-4" disabled>Insufficient PV</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="icon-box mx-auto mb-3" style="width: 70px; height: 70px; font-size: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(234, 88, 12, 0.1); color: #ea580c;">
                        <i class="las la-exclamation-triangle"></i>
                    </div>
                    <h4 class="text-white mb-2">Are you sure?</h4>
                    <p class="text-white-50 mb-0 px-4">This will deduct <strong class="text-white">100 PV</strong> from your account balance. This action cannot be undone.</p>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-dark px-4" data-bs-target="#generateVoucherModal" data-bs-toggle="modal">
                        Cancel
                    </button>
                    <button type="button" id="confirmYes" class="btn btn-primary px-4">
                        <i class="las la-check-circle me-1"></i> Yes, Generate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="generatedCodeModal" tabindex="-1" aria-labelledby="generatedCodeModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <div class="modal-header border-0" style="background: linear-gradient(to right, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));">
                <h5 class="modal-title text-success fw-bold" id="generatedCodeModalLabel">
                    <i class="las la-check-double me-2"></i>Success!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="icon-box mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="las la-ticket-alt"></i>
                    </div>
                    <h4 class="text-white mb-2">Voucher Generated</h4>
                    <p class="text-white-50">Your voucher code is ready to use.</p>
                </div>

                <div class="bg-dark p-3 rounded-3 border border-secondary border-opacity-25 mb-3 position-relative overflow-hidden">
                    <small class="text-white-50 d-block mb-2 text-start text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1px;">Voucher Code</small>
                    <div class="input-group">
                        <input type="text" id="generatedVoucherCode" value="" readonly class="form-control bg-transparent border-0 text-white fw-bold fs-5 text-center" style="box-shadow: none; font-family: monospace; letter-spacing: 2px;">
                        <button class="btn btn-outline-primary rounded-3 ms-2" type="button" id="copyVoucherCode">
                            <i class="las la-copy"></i> Copy
                        </button>
                    </div>
                </div>
                
                <div id="copySuccessMessage" class="text-success small fw-bold" style="display: none;">
                    <i class="las la-check me-1"></i> Copied to clipboard!
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>