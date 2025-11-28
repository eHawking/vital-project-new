@extends('layouts.admin.app')

@section('title', translate('Edit Bonus'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-capitalize">{{ translate('Edit Bonus') }}</h1>
        <a href="{{ route('admin.bonus-management.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
            <i class="tio-arrow-back"></i> {{ translate('Back to List') }}
        </a>
    </div>

    <!-- Edit Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ translate('Update Bonus Details') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bonus-management.update', $bonusManagement->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Bonus Type -->
                <div class="mb-3">
                    <label for="bonus_type" class="form-label">{{ translate('Bonus Type') }}</label>
                    <input type="text" name="bonus_type" id="bonus_type" class="form-control" 
                           value="{{ $bonusManagement->bonus_type }}" placeholder="{{ translate('Enter bonus type') }}" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">{{ translate('Description') }}</label>
                    <textarea name="description" id="description" class="form-control" rows="4" 
                              placeholder="{{ translate('Enter bonus description') }}" required>{{ $bonusManagement->description }}</textarea>
                </div>

                <!-- Available Tags Section -->
                <div class="mb-4 border p-3 rounded">
                    <h6 class="mb-3">{{ translate('Available Tags') }}</h6>
                    
                    <div class="row g-2">
                        <!-- User-related Tags -->
                        <div class="col-md-4">
                            <div class="border p-2 rounded mb-3">
                                <small class="text-muted d-block mb-2">{{ translate('User Related') }}</small>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{username}">
                                    {username}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{customer_name}">
                                    {customer_name}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{user_id}">
                                    {user_id}
                                </button>
                            </div>
                        </div>

                        <!-- Vendor-related Tags -->
                        <div class="col-md-4">
                            <div class="border p-2 rounded mb-3">
                                <small class="text-muted d-block mb-2">{{ translate('Vendor Related') }}</small>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{vendor_id}">
                                    {vendor_id}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{vendor_username}">
                                    {vendor_username}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{vendor_name}">
                                    {vendor_name}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{shop_name}">
                                    {shop_name}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{city}">
                                    {city}
                                </button>
                            </div>
                        </div>

                        <!-- Product-related Tags -->
                        <div class="col-md-4">
                            <div class="border p-2 rounded mb-3">
                                <small class="text-muted d-block mb-2">{{ translate('Product Related') }}</small>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{products}">
                                    {products}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-1 tag-btn" data-tag="{qty}">
                                    {qty}
                                </button>
                            </div>
                        </div>

                        <!-- Bonus Type Tags -->
                        <div class="col-12">
                            <div class="border p-2 rounded">
                                <small class="text-muted d-block mb-2">{{ translate('Bonus Types') }}</small>
                                @foreach([
                                    'bv', 'pv', 'dds_ref_bonus', 'shop_bonus', 'shop_reference',
                                    'franchise_bonus', 'franchise_ref_bonus', 'city_ref_bonus',
                                    'leadership_bonus', 'promo', 'user_promo', 'seller_promo',
                                    'shipping_expense', 'bilty_expense', 'office_expense',
                                    'event_expense', 'fuel_expense', 'visit_expense',
                                    'company_partner_bonus', 'product_partner_bonus',
                                    'budget_promo', 'product_partner_ref_bonus',
                                    'vendor_ref_bonus', 'royalty_bonus'
                                ] as $tag)
                                    <button type="button" class="btn btn-xs btn-outline-primary mb-1 tag-btn" data-tag="{!! '{'.$tag.'}' !!}">
                                        {{ '{'.$tag.'}' }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- System Tags -->
                        <div class="col-md-12 mt-3">
                            <div class="border p-2 rounded">
                                <small class="text-muted d-block mb-2">{{ translate('System Tags') }}</small>
                                <button type="button" class="btn btn-xs btn-outline-info mb-1 tag-btn" data-tag="{amount}">
                                    {amount}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-info mb-1 tag-btn" data-tag="{month_name}">
                                    {month_name}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-info mb-1 tag-btn" data-tag="{year}">
                                    {year}
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-info mb-1 tag-btn" data-tag="{date}">
                                    {date}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SQL Statement -->
                <div class="mb-3">
                    <label for="statement" class="form-label">{{ translate('Bonus Statement') }}</label>
                    <textarea name="statement" id="statement" class="form-control" rows="4" 
                              placeholder="{{ translate('Enter bonus statement') }}" required>{{ $bonusManagement->statement }}</textarea>
                    <small class="form-text text-muted">
                        {{ translate('Available tags will be replaced with actual values. Click tags to copy.') }}
                    </small>
                </div>

                <!-- Save Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="tio-save"></i> {{ translate('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tag copy functionality
        document.querySelectorAll('.tag-btn').forEach(button => {
            button.addEventListener('click', function() {
                const tag = this.dataset.tag;
                const tempInput = document.createElement('input');
                tempInput.value = tag;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                
                // Visual feedback
                const originalText = this.innerText;
                this.innerText = '{{ translate("Copied!") }}';
                setTimeout(() => {
                    this.innerText = originalText;
                }, 1500);
            });
        });

        // Auto-insert tag into textarea on click
        document.querySelectorAll('.tag-btn').forEach(button => {
            button.addEventListener('click', function() {
                const tag = this.dataset.tag;
                const textarea = document.getElementById('statement');
                const startPos = textarea.selectionStart;
                const endPos = textarea.selectionEnd;
                textarea.value = textarea.value.substring(0, startPos) + tag + textarea.value.substring(endPos);
                textarea.focus();
                textarea.selectionStart = startPos + tag.length;
                textarea.selectionEnd = startPos + tag.length;
            });
        });
    });
</script>
@endpush

@endsection