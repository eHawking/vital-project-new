{{-- Buy Now Referral Modal --}}
<div class="modal fade" id="buyNowReferralModal" tabindex="-1" role="dialog" aria-labelledby="buyNowReferralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <button type="button" class="btn-close position-absolute bg-white rounded-circle shadow-sm" onclick="$('#buyNowReferralModal').modal('hide')" aria-label="Close" style="top: 15px; right: 20px; z-index: 1050; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.3);">
                <i class="bi bi-x" style="font-size: 24px; color: #6366f1;"></i>
            </button>
            
            <div class="modal-body p-0">
                {{-- Header Section with Gradient --}}
                <div class="text-center py-4 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="mb-3">
                        <div style="width: 80px; height: 80px; margin: 0 auto; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-cart-check-fill" style="font-size: 45px; color: white;"></i>
                        </div>
                    </div>
                    <h4 class="text-white font-weight-bold mb-2">{{ translate('Complete Your Purchase') }}</h4>
                    <p class="text-white mb-0" style="opacity: 0.9; font-size: 15px;">{{ translate('Choose how you want to proceed:') }}</p>
                </div>

                {{-- Content Area --}}
                <div class="px-4 pt-4 pb-3">
                    <div class="row">
                        {{-- Continue as Guest Option --}}
                        <div class="col-md-6 mb-3">
                            <div class="modern-card guest-card h-100" style="border: 2px solid #e0e7ff; border-radius: 16px; background: #f8faff; transition: all 0.3s ease; cursor: pointer; position: relative; overflow: hidden;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="bi bi-arrow-right" style="font-size: 24px; color: white;"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h6 class="font-weight-bold mb-0" style="color: #1e293b; font-size: 16px;">{{ translate('Continue as Guest') }}</h6>
                                        </div>
                                    </div>
                                    <p class="mb-3" style="color: #64748b; font-size: 14px; line-height: 1.6;">
                                        {{ translate('Proceed to checkout without registration. No special benefits included.') }}
                                    </p>
                                    <button type="button" id="continue-as-guest-btn" class="btn btn-block" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; font-size: 14px; transition: all 0.3s ease;">
                                        {{ translate('Continue to Checkout') }}
                                        <i class="bi bi-arrow-right" style="margin-left: 8px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Register & Get Benefits Option --}}
                        <div class="col-md-6 mb-3">
                            <div class="modern-card benefits-card h-100" id="referral-benefits-card" style="border: 2px solid #10b981; border-radius: 16px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); transition: all 0.3s ease; position: relative; overflow: hidden;">
                                {{-- Recommended Badge --}}
                                <div class="position-absolute" style="top: 12px; right: 12px;">
                                    <span class="badge" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                        ✨ {{ translate('Recommended') }}
                                    </span>
                                </div>
                                
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="bi bi-gift-fill" style="font-size: 24px; color: white;"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h6 class="font-weight-bold mb-0" style="color: #065f46; font-size: 16px;">{{ translate('Register & Get Benefits') }}</h6>
                                        </div>
                                    </div>
                                    <p class="mb-3" style="color: #047857; font-size: 13px; font-weight: 500;">
                                        {{ translate('Register now and get amazing rewards:') }}
                                    </p>
                                    <div class="benefits-list mb-3">
                                        <div class="d-flex align-items-start mb-2">
                                            <div style="width: 24px; height: 24px; background: rgba(16, 185, 129, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 10px;">
                                                <span style="font-size: 14px;">💰</span>
                                            </div>
                                            <span style="color: #065f46; font-size: 13px; line-height: 24px;">{{ translate('Cashback on purchases') }}</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-2">
                                            <div style="width: 24px; height: 24px; background: rgba(16, 185, 129, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 10px;">
                                                <span style="font-size: 14px;">🎁</span>
                                            </div>
                                            <span style="color: #065f46; font-size: 13px; line-height: 24px;">{{ translate('Exclusive rewards & bonuses') }}</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-2">
                                            <div style="width: 24px; height: 24px; background: rgba(16, 185, 129, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 10px;">
                                                <span style="font-size: 14px;">💸</span>
                                            </div>
                                            <span style="color: #065f46; font-size: 13px; line-height: 24px;">{{ translate('Special discounts') }}</span>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div style="width: 24px; height: 24px; background: rgba(16, 185, 129, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 10px;">
                                                <span style="font-size: 14px;">✨</span>
                                            </div>
                                            <span style="color: #065f46; font-size: 13px; line-height: 24px;">{{ translate('Much more surprises!') }}</span>
                                        </div>
                                    </div>

                                    <div id="referral-form-section">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-600 mb-2" style="color: #065f46; font-size: 13px;" for="referral-username">
                                                {{ translate('Referral Username') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="referral-username" 
                                                   placeholder="{{ translate('Enter referral username') }}" 
                                                   required
                                                   style="border: 2px solid #a7f3d0; border-radius: 10px; padding: 12px 16px; font-size: 14px; background: white; transition: all 0.3s ease;">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="font-weight-600 mb-2" style="color: #065f46; font-size: 13px;" for="referral-position">
                                                {{ translate('Position') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" id="referral-position" required
                                                    style="border: 2px solid #a7f3d0; border-radius: 10px; padding: 12px 16px; font-size: 14px; background: white; transition: all 0.3s ease;">
                                                <option value="">{{ translate('Select Position') }}</option>
                                                <option value="left">{{ translate('Left') }}</option>
                                                <option value="right">{{ translate('Right') }}</option>
                                            </select>
                                        </div>

                                        <a href="{{ str_replace('://', '://www.', url('/account/user/register')) }}" class="btn btn-block" id="join-with-benefits-link" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; font-size: 14px; transition: all 0.3s ease;">
                                            {{ translate('Register & Get Benefits') }}
                                            <i class="bi bi-arrow-right" style="margin-left: 8px;"></i>
                                        </a>
                                    </div>

                                    <div id="autofilled-section" style="display: none;">
                                        <div class="alert mb-3" style="background: rgba(16, 185, 129, 0.1); border: 2px solid #a7f3d0; border-radius: 10px; color: #065f46; padding: 12px 16px;">
                                            <i class="bi bi-check-circle-fill" style="margin-right: 8px; vertical-align: middle; font-size: 18px;"></i>
                                            <span style="font-size: 13px; font-weight: 500;">{{ translate('Your referral information is pre-filled. Click below to continue.') }}</span>
                                        </div>
                                        <a href="{{ str_replace('://', '://www.', url('/account/user/register')) }}" class="btn btn-block" id="join-autofilled-link" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; font-size: 14px; transition: all 0.3s ease;">
                                            {{ translate('Register & Get Benefits') }}
                                            <i class="bi bi-arrow-right" style="margin-left: 8px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                {{-- Footer with Login Link --}}
                <div class="text-center py-4 px-4" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <p class="mb-0" style="color: #64748b; font-size: 14px;">
                        {{ translate('Already have an account?') }}
                        <a href="javascript:" class="customer_login_register_modal" style="color: #6366f1; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">
                            {{ translate('Login Here') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Modal Animations */
    #buyNowReferralModal .modal-dialog {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Card Hover Effects */
    #buyNowReferralModal .modern-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
    }
    
    #buyNowReferralModal .guest-card:hover {
        border-color: #6366f1;
        background: #f0f1ff;
    }
    
    #buyNowReferralModal .benefits-card:hover {
        border-color: #059669;
    }
    
    /* Button Hover Effects */
    #buyNowReferralModal button:hover,
    #buyNowReferralModal a.btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Form Field Focus */
    #buyNowReferralModal #referral-username:focus,
    #buyNowReferralModal #referral-position:focus {
        border-color: #10b981 !important;
        outline: none;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    
    /* Login Link Hover */
    #buyNowReferralModal .customer_login_register_modal:hover {
        color: #4f46e5 !important;
        text-decoration: underline !important;
    }
    
    /* Close Button Hover */
    #buyNowReferralModal .close:hover {
        opacity: 1 !important;
        transform: rotate(90deg);
        transition: all 0.3s ease;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        #buyNowReferralModal .modal-dialog {
            margin: 10px;
        }
        
        #buyNowReferralModal .col-md-6 {
            margin-bottom: 1rem;
        }
    }
</style>

<script>
    // Store the original buy now action globally
    let originalBuyNowAction = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Continue as Guest button - execute original buy now functionality
        document.getElementById('continue-as-guest-btn')?.addEventListener('click', function() {
            // Close modal
            $('#buyNowReferralModal').modal('hide');
            
            // Execute the original buy now action if it exists
            if (typeof originalBuyNowAction === 'function') {
                originalBuyNowAction();
            }
        });

        // Manual entry - validate before allowing click
        document.getElementById('join-with-benefits-link')?.addEventListener('click', function(e) {
            const username = document.getElementById('referral-username').value.trim();
            const position = document.getElementById('referral-position').value;

            if (!username || !position) {
                e.preventDefault();
                alert('{{ translate("Please fill in all required fields") }}');
                return false;
            }
            
            // Update href with values
            this.href = '{{ str_replace("://", "://www.", url("/account/user/register")) }}' + 
                       '?ref=' + encodeURIComponent(username) + 
                       '&position=' + encodeURIComponent(position);
        });

        // Close buy now modal when login link is clicked
        $(document).on('click', '.customer_login_register_modal', function() {
            // Check if buy now modal is open
            if ($('#buyNowReferralModal').hasClass('show')) {
                $('#buyNowReferralModal').modal('hide');
            }
        });
    });
</script>
