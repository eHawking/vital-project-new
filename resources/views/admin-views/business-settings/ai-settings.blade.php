@extends('layouts.admin.app')

@section('title', translate('AI Settings'))

@section('content')
<div class="content container-fluid">
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 d-flex align-items-center gap-2">
            <i class="fi fi-rr-magic-wand"></i>
            {{ translate('AI Settings') }}
        </h2>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#general-settings">
                <i class="fi fi-rr-settings"></i> {{ translate('General Settings') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.ai-settings.images') }}">
                <i class="fi fi-rr-picture"></i> {{ translate('AI Images Gallery') }}
            </a>
        </li>
    </ul>

    <form action="{{ route('admin.ai-settings.update') }}" method="POST" class="card">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <!-- API Key -->
                <div class="col-12">
                    <label class="form-label">
                        {{ translate('Gemini API Key') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="gemini_api_key" class="form-control" value="{{ $cfg['gemini_api_key'] ?? '' }}" placeholder="{{ translate('Enter API key or use env') }}" required>
                    <small class="text-muted">{{ translate('Get your API key from') }} <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a></small>
                </div>

                <!-- Model Selection -->
                <div class="col-md-6">
                    <label class="form-label">
                        {{ translate('Select AI Model') }}
                        <span class="text-danger">*</span>
                    </label>
                    <select name="selected_model" id="selected_model" class="form-control" required>
                        @foreach($availableModels as $value => $label)
                            <option value="{{ $value }}" {{ ($cfg['selected_model'] ?? 'gemini-2.5-flash') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">{{ translate('Choose the AI model for content and image generation') }}</small>
                </div>

                <!-- Use All Models Option -->
                <div class="col-md-6">
                    <label class="form-label">{{ translate('Model Usage') }}</label>
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" name="use_all_models" id="use_all_models" class="form-check-input" value="1" {{ ($cfg['use_all_models'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="use_all_models">
                            {{ translate('Use All Models') }}
                        </label>
                    </div>
                    <small class="text-muted">{{ translate('Enable to use all available models for better results') }}</small>
                </div>

            </div>
        </div>
        
        <div class="card-footer d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-secondary">
                <i class="fi fi-rr-cross-circle"></i> {{ translate('Reset') }}
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fi fi-rr-disk"></i> {{ translate('Save Settings') }}
            </button>
        </div>
    </form>

    <!-- Model Information Card -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fi fi-rr-info"></i> {{ translate('Model Information') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ translate('Model') }}</th>
                            <th>{{ translate('Description') }}</th>
                            <th>{{ translate('Best For') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Gemini 2.5 Flash</strong></td>
                            <td>{{ translate('Fast and efficient model for general tasks') }}</td>
                            <td>{{ translate('Product analysis, text generation') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gemini 2.5 Flash Image Preview</strong></td>
                            <td>{{ translate('Preview version with image capabilities') }}</td>
                            <td>{{ translate('Image analysis and generation') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gemini 2.5 Flash Image</strong></td>
                            <td>{{ translate('Optimized for image processing') }}</td>
                            <td>{{ translate('Product image generation') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gemini 2.5 Flash Preview (Sep 2025)</strong></td>
                            <td>{{ translate('Latest preview with new features') }}</td>
                            <td>{{ translate('Testing new capabilities') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gemini 2.5 Pro</strong></td>
                            <td>{{ translate('Most powerful model with advanced capabilities') }}</td>
                            <td>{{ translate('Complex analysis, high-quality generation') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.getElementById('use_all_models').addEventListener('change', function() {
    const selectedModelSelect = document.getElementById('selected_model');
    if (this.checked) {
        selectedModelSelect.disabled = true;
        selectedModelSelect.style.opacity = '0.5';
    } else {
        selectedModelSelect.disabled = false;
        selectedModelSelect.style.opacity = '1';
    }
});

// Initialize on page load
if (document.getElementById('use_all_models').checked) {
    document.getElementById('selected_model').disabled = true;
    document.getElementById('selected_model').style.opacity = '0.5';
}
</script>
</div>
@endsection
