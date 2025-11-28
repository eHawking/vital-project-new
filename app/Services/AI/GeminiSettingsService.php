<?php

namespace App\Services\AI;

use App\Repositories\BusinessSettingRepository;

class GeminiSettingsService
{
    public function __construct(private readonly BusinessSettingRepository $settingsRepo)
    {
    }

    public function get(): array
    {
        $config = getWebConfig(name: 'ai_settings');
        $apiKey = $config['gemini_api_key'] ?? env('GOOGLE_GEMINI_API_KEY');
        
        // Get model settings
        $selectedModel = getWebConfig(name: 'ai_model_selected') ?? 'gemini-2.5-flash';
        $useAllModels = getWebConfig(name: 'ai_use_all_models') ?? '0';
        
        return [
            'provider' => $config['provider'] ?? 'gemini',
            'gemini_api_key' => $apiKey,
            'selected_model' => $selectedModel,
            'use_all_models' => $useAllModels == '1',
        ];
    }
    
    /**
     * Get available AI models
     */
    public function getAvailableModels(): array
    {
        return [
            'gemini-2.5-flash' => 'Gemini 2.5 Flash',
            'gemini-2.5-flash-image-preview' => 'Gemini 2.5 Flash Image Preview',
            'gemini-2.5-flash-image' => 'Gemini 2.5 Flash Image',
            'gemini-2.5-flash-preview-09-2025' => 'Gemini 2.5 Flash Preview (Sep 2025)',
            'gemini-2.5-pro' => 'Gemini 2.5 Pro',
        ];
    }
    
    /**
     * Get the active model(s) for display
     */
    public function getActiveModels(): array
    {
        $config = $this->get();
        
        if ($config['use_all_models']) {
            return $this->getAvailableModels();
        }
        
        $selectedModel = $config['selected_model'];
        $availableModels = $this->getAvailableModels();
        
        return [$selectedModel => $availableModels[$selectedModel] ?? 'Unknown Model'];
    }
    
    /**
     * Get all models if "use all models" is enabled
     */
    public function getModelsToUse(): array
    {
        $config = $this->get();
        
        if ($config['use_all_models']) {
            return array_keys($this->getAvailableModels());
        }
        
        return [$config['selected_model']];
    }

    public function isConfigured(): bool
    {
        $cfg = $this->get();
        return !empty($cfg['gemini_api_key']);
    }

    public function save(array $data): void
    {
        $existing = getWebConfig(name: 'ai_settings') ?? [];
        
        // Save main AI settings
        $payload = array_merge($existing, [
            'provider' => 'gemini',
            'gemini_api_key' => $data['gemini_api_key'] ?? ($existing['gemini_api_key'] ?? ''),
        ]);
        $this->settingsRepo->updateOrInsert(type: 'ai_settings', value: json_encode($payload));
        
        // Save model selection
        if (isset($data['selected_model'])) {
            $this->settingsRepo->updateOrInsert(
                type: 'ai_model_selected',
                value: $data['selected_model']
            );
        }
        
        // Save use all models option
        if (isset($data['use_all_models'])) {
            $this->settingsRepo->updateOrInsert(
                type: 'ai_use_all_models',
                value: $data['use_all_models'] ? '1' : '0'
            );
        }
    }
}
