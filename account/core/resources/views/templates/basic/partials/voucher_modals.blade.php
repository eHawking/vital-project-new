<div class="modal fade" id="generateVoucherModal" tabindex="-1" aria-labelledby="generateVoucherModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateVoucherModalLabel">Generate DSP Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $userPV = auth()->user()->pv ?? 0;
                    $vouchersToGenerate = floor($userPV / 100);
                @endphp
                <p>Your available Point Vouchers (PV): <strong>{{ number_format($userPV, 2) }} PV</strong></p>
                <p>Each voucher costs <strong>100 PV</strong>.</p>
                <p>You can generate <strong>{{ $vouchersToGenerate }}</strong> {{ $vouchersToGenerate == 1 ? 'voucher' : 'vouchers' }}.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if ($vouchersToGenerate > 0)
                    {{-- This button now uses data attributes to open the confirmation modal --}}
                  <button type="button" id="proceedToConfirmBtn" class="btn btn-primary">Generate DSP Voucher</button>
                @else
                    <button type="button" class="btn btn-primary" disabled>Not Enough PV</button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Voucher Generation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to generate a voucher?</p>
                <p class="text-danger fw-bold">This will deduct 100 PV from your account. This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                {{-- This button now takes the user back to the first modal --}}
                <button type="button" class="btn btn-secondary" data-bs-target="#generateVoucherModal" data-bs-toggle="modal">Cancel</button>
                <button type="button" id="confirmYes" class="btn btn-primary">Yes, Generate</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="generatedCodeModal" tabindex="-1" aria-labelledby="generatedCodeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatedCodeModalLabel">Voucher Generated Successfully</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Your voucher code is ready. You can copy it below.</p>
                <div class="input-group mb-3">
                    <input type="text" id="generatedVoucherCode" value="" readonly class="form-control" placeholder="Your Code">
                    <button class="btn btn-outline-primary" type="button" id="copyVoucherCode">
                        <i class="bi bi-copy"></i>
                        <span>Copy</span>
                    </button>
                </div>
                <div id="copySuccessMessage" class="text-success" style="display: none;">Copied to clipboard!</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>