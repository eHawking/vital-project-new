<div class="form-group">
    <label class="form-label font-semibold">
        {{ translate('Email / Phone / Username') }}
        <span class="input-required-icon">*</span>
    </label>
    <input class="form-control text-align-direction auth-email-input" 
           type="text" 
           name="user_identity" 
           id="si-email"
           value="{{ old('user_identity') }}" 
           placeholder="{{ translate('Enter email, phone, or username') }}"
           required>
    <div class="invalid-feedback">{{ translate('Please provide valid email, phone, or username') }}</div>
</div>