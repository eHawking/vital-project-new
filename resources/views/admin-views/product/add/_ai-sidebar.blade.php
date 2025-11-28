<!-- Floating AI Button -->
<button type="button" class="ai-floating-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-ai-product" title="{{ translate('AI Assist') }}">
    <i class="fi fi-rr-magic-wand"></i>
    <span class="ai-btn-text">{{ translate('Use AI') }}</span>
</button>

<style>
.ai-floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    animation: ai-glow 2s ease-in-out infinite;
}

.ai-floating-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 30px rgba(102, 126, 234, 0.6);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.ai-floating-btn:active {
    transform: scale(0.95);
}

.ai-floating-btn i {
    font-size: 28px;
    animation: ai-sparkle 3s ease-in-out infinite;
}

.ai-btn-text {
    position: absolute;
    bottom: -25px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.ai-floating-btn:hover .ai-btn-text {
    opacity: 1;
}

@keyframes ai-glow {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4),
                    0 0 0 0 rgba(102, 126, 234, 0.4);
    }
    50% {
        box-shadow: 0 4px 30px rgba(102, 126, 234, 0.6),
                    0 0 0 10px rgba(102, 126, 234, 0);
    }
}

@keyframes ai-sparkle {
    0%, 100% {
        transform: rotate(0deg) scale(1);
        filter: brightness(1);
    }
    25% {
        transform: rotate(-10deg) scale(1.1);
        filter: brightness(1.2);
    }
    50% {
        transform: rotate(10deg) scale(1);
        filter: brightness(1);
    }
    75% {
        transform: rotate(-5deg) scale(1.05);
        filter: brightness(1.1);
    }
}

/* Pulse effect on page load */
@keyframes ai-pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.15);
    }
    100% {
        transform: scale(1);
    }
}

.ai-floating-btn.ai-pulse {
    animation: ai-pulse 0.6s ease-in-out 3;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .ai-floating-btn {
        width: 50px;
        height: 50px;
        bottom: 20px;
        right: 20px;
        font-size: 20px;
    }
    
    .ai-floating-btn i {
        font-size: 24px;
    }
}
</style>

<script>
// Add pulse effect on page load
document.addEventListener('DOMContentLoaded', function() {
    const aiBtn = document.querySelector('.ai-floating-btn');
    if(aiBtn) {
        setTimeout(() => {
            aiBtn.classList.add('ai-pulse');
            setTimeout(() => aiBtn.classList.remove('ai-pulse'), 2000);
        }, 1000);
    }
});
</script>

<div class="offcanvas offcanvas-end ai-sidebar-modern" tabindex="-1" id="offcanvas-ai-product" aria-labelledby="offcanvas-ai-product-label" style="width: 450px;">
    <!-- Header with Gradient -->
    <div class="offcanvas-header ai-sidebar-header">
        <div class="d-flex align-items-center gap-2">
            <div class="ai-icon-wrapper">
                <i class="fi fi-rr-magic-wand ai-icon-animated"></i>
            </div>
            <div>
                <h3 class="mb-0" id="offcanvas-ai-product-label">{{ translate('AI Generator') }}</h3>
                <small class="text-white-50">{{ translate('Powered by Gemini') }}</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-0">
        <!-- Active Model Display -->
        <div class="ai-model-display">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="ai-model-label">
                    <i class="fi fi-rr-microchip"></i> {{ translate('Active Model') }}
                </span>
                <span class="badge bg-success ai-pulse-badge">{{ translate('Online') }}</span>
            </div>
            <div id="ai-active-models" class="ai-model-tags">
                @php
                    $aiSettings = app(\App\Services\AI\GeminiSettingsService::class);
                    $activeModels = $aiSettings->getActiveModels();
                @endphp
                @foreach($activeModels as $modelId => $modelName)
                    <span class="ai-model-tag">
                        <i class="fi fi-rr-check-circle"></i> {{ $modelName }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="ai-sidebar-content">
            <!-- Model Selection Card -->
            <div class="ai-card">
                <div class="ai-card-header">
                    <i class="fi fi-rr-settings"></i> {{ translate('Model Selection') }}
                </div>
                <div class="ai-card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Choose AI Model') }}</label>
                        <select id="ai-model-selector" class="form-select ai-select">
                            @php
                                $availableModels = $aiSettings->getAvailableModels();
                                $selectedModel = $aiSettings->get()['selected_model'] ?? 'gemini-2.5-flash';
                            @endphp
                            @foreach($availableModels as $value => $label)
                                <option value="{{ $value }}" {{ $selectedModel == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" id="ai-use-all-models" class="form-check-input" 
                               {{ ($aiSettings->get()['use_all_models'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ai-use-all-models">
                            {{ translate('Use All Models') }}
                            <i class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ translate('Enable to use all 5 models for better results') }}"></i>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Upload Card -->
            <div class="ai-card">
                <div class="ai-card-header">
                    <i class="fi fi-rr-picture"></i> {{ translate('Upload Images') }}
                </div>
                <div class="ai-card-body">
                    <div class="ai-upload-zone" id="ai-upload-zone">
                        <input id="ai-images-input" type="file" multiple accept="image/*" class="d-none">
                        <div class="ai-upload-content">
                            <i class="fi fi-rr-cloud-upload ai-upload-icon"></i>
                            <p class="mb-1">{{ translate('Click to upload or drag & drop') }}</p>
                            <small class="text-muted">{{ translate('PNG, JPG up to 10MB') }}</small>
                        </div>
                        <div id="ai-upload-preview" class="ai-upload-preview"></div>
                    </div>
                </div>
            </div>

            <!-- Generate Button -->
            <button id="ai-generate-btn" type="button" class="btn ai-generate-btn w-100">
                <span class="ai-btn-content">
                    <i class="fi fi-rr-magic-wand"></i>
                    <span>{{ translate('Generate with AI') }}</span>
                </span>
                <span class="ai-btn-loader" style="display: none;">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    {{ translate('Generating...') }}
                </span>
            </button>

            <!-- Progress Card -->
            <div class="ai-card mt-3" id="ai-progress-card" style="display: none;">
                <div class="ai-card-body">
                    <div class="ai-progress-wrapper">
                        <div class="progress ai-progress-bar">
                            <div id="ai-progress" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <div id="ai-status" class="ai-status-text mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Error Alert -->
            <div class="alert alert-danger ai-alert" id="ai-errors" style="display:none;">
                <i class="fi fi-rr-cross-circle"></i>
                <span class="ai-error-text"></span>
            </div>

            <!-- Tasks List -->
            <div class="ai-card" id="ai-task-card">
                <div class="ai-card-header">
                    <i class="fi fi-rr-list-check"></i> {{ translate('AI Tasks') }}
                </div>
                <div class="ai-card-body">
                    <ul class="ai-task-list" id="ai-task-list">
                        <li id="ai-task-validate" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Validate images') }}</span>
                        </li>
                        <li id="ai-task-analyze" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Analyze with AI') }}</span>
                        </li>
                        <li id="ai-task-map" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Map AI output') }}</span>
                        </li>
                        <li id="ai-task-fill" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Fill form fields') }}</span>
                        </li>
                        <li id="ai-task-generate" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Generate images') }}</span>
                        </li>
                        <li id="ai-task-attach" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Attach to form') }}</span>
                        </li>
                        <li id="ai-task-done" class="ai-task-item">
                            <span class="ai-task-icon">
                                <i class="fi fi-rr-circle"></i>
                            </span>
                            <span class="ai-task-text">{{ translate('Complete') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="ai-card" id="ai-preview" style="display:none;">
                <div class="ai-card-header">
                    <i class="fi fi-rr-eye"></i> {{ translate('Preview') }}
                </div>
                <div class="ai-card-body">
                    <div class="ai-preview-grid">
                        <img id="ai-thumb-preview" src="" alt="thumbnail" class="ai-preview-thumb"/>
                        <div id="ai-additional-preview" class="ai-preview-additional"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Styles -->
<style>
.ai-sidebar-modern {
    box-shadow: -5px 0 30px rgba(0,0,0,0.1);
}

.ai-sidebar-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-bottom: none;
}

.ai-icon-wrapper {
    width: 45px;
    height: 45px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ai-icon-animated {
    font-size: 24px;
    animation: ai-icon-pulse 2s ease-in-out infinite;
}

@keyframes ai-icon-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.ai-model-display {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 1.25rem;
    border-bottom: 1px solid #e0e0e0;
}

.ai-model-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #4a5568;
}

.ai-pulse-badge {
    animation: ai-pulse-glow 2s ease-in-out infinite;
}

@keyframes ai-pulse-glow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
    50% { box-shadow: 0 0 0 6px rgba(40, 167, 69, 0); }
}

.ai-model-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.ai-model-tag {
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.ai-sidebar-content {
    padding: 1.5rem;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.ai-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.ai-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.ai-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem 1.25rem;
    font-weight: 600;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ai-card-body {
    padding: 1.25rem;
}

.ai-select {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s;
}

.ai-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.ai-upload-zone {
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f7fafc;
}

.ai-upload-zone:hover {
    border-color: #667eea;
    background: #edf2f7;
}

.ai-upload-zone.dragover {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
}

.ai-upload-icon {
    font-size: 48px;
    color: #a0aec0;
    margin-bottom: 1rem;
}

.ai-upload-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.ai-upload-preview img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
}

.ai-generate-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1.125rem;
    font-weight: 600;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.ai-generate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.ai-generate-btn:active {
    transform: translateY(0);
}

.ai-generate-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.ai-generate-btn:active::before {
    width: 300px;
    height: 300px;
}

.ai-btn-content, .ai-btn-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.ai-progress-bar {
    height: 8px;
    border-radius: 10px;
    background: #e0e0e0;
    overflow: hidden;
}

.ai-progress-bar .progress-bar {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
}

.ai-status-text {
    font-size: 0.875rem;
    color: #718096;
    text-align: center;
}

.ai-task-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ai-task-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: all 0.3s;
}

.ai-task-item:hover {
    background: #f7fafc;
}

.ai-task-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e0;
    font-size: 18px;
}

.ai-task-item.pending .ai-task-icon {
    color: #cbd5e0;
}

.ai-task-item.running .ai-task-icon {
    color: #667eea;
    animation: ai-spin 1s linear infinite;
}

.ai-task-item.success .ai-task-icon {
    color: #48bb78;
}

.ai-task-item.success .ai-task-icon i::before {
    content: "\ea5e"; /* tio-checkmark-circle */
}

.ai-task-item.error .ai-task-icon {
    color: #f56565;
}

@keyframes ai-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.ai-task-text {
    font-size: 0.875rem;
    color: #4a5568;
}

.ai-preview-grid {
    display: grid;
    gap: 1rem;
}

.ai-preview-thumb {
    width: 100%;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
}

.ai-preview-additional {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}

.ai-preview-additional img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
}

.ai-alert {
    border-radius: 12px;
    border: none;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Magic Effect for AI-generated fields */
@keyframes ai-magic-glow {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4);
        border-color: #667eea;
    }
    50% {
        box-shadow: 0 0 0 8px rgba(102, 126, 234, 0);
        border-color: #764ba2;
    }
}

.ai-magic-field {
    animation: ai-magic-glow 1.5s ease-in-out 3;
    position: relative;
}

.ai-magic-field::after {
    content: '✨';
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    animation: ai-sparkle-float 2s ease-in-out infinite;
}

@keyframes ai-sparkle-float {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 1; }
    50% { transform: translateY(-10px) rotate(180deg); opacity: 0.7; }
}

/* Responsive */
@media (max-width: 768px) {
    .ai-sidebar-modern {
        width: 100% !important;
    }
}
</style>

<span id="route-admin-products-ai-analyze" data-url="{{ route('admin.products.ai.analyze') }}"></span>
<span id="route-admin-products-ai-generate-images" data-url="{{ route('admin.products.ai.generate-images') }}"></span>

<!-- Localized messages for product-ai.js -->
<span id="msg-ai-please-upload" data-text="{{ translate('please_upload_images_first') }}" hidden></span>
<span id="msg-ai-analysis-completed" data-text="{{ translate('ai_analysis_completed') }}" hidden></span>
<span id="msg-ai-images-generated" data-text="{{ translate('ai_images_generated') }}" hidden></span>
<span id="msg-ai-image-generation-failed" data-text="{{ translate('image_generation_failed') }}" hidden></span>
<span id="msg-ai-processing" data-text="{{ translate('processing') }}" hidden></span>
<span id="msg-ai-something-went-wrong" data-text="{{ translate('something_went_wrong') }}" hidden></span>

<span id="msg-ai-workflow-started" data-text="{{ translate('AI workflow started') }}" hidden></span>
<span id="msg-ai-validating" data-text="{{ translate('Validating images') }}" hidden></span>
<span id="msg-ai-analyzing" data-text="{{ translate('Analyzing images') }}" hidden></span>
<span id="msg-ai-mapping" data-text="{{ translate('Mapping AI output') }}" hidden></span>
<span id="msg-ai-filling" data-text="{{ translate('Filling form fields') }}" hidden></span>
<span id="msg-ai-generating" data-text="{{ translate('Generating images') }}" hidden></span>
<span id="msg-ai-attaching" data-text="{{ translate('Attaching images') }}" hidden></span>
<span id="msg-ai-completed" data-text="{{ translate('All steps completed') }}" hidden></span>
<span id="msg-ai-failed" data-text="{{ translate('AI workflow failed') }}" hidden></span>
