<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\BaseController;
use App\Services\AI\GeminiSettingsService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AISettingsController extends BaseController
{
    public function __construct(private readonly GeminiSettingsService $settings)
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|callable|RedirectResponse|JsonResponse|null
    {
        $cfg = $this->settings->get();
        $availableModels = $this->settings->getAvailableModels();
        return view('admin-views.business-settings.ai-settings', compact('cfg', 'availableModels'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'gemini_api_key' => 'required|string',
            'selected_model' => 'required|string',
            'use_all_models' => 'nullable|boolean',
        ]);

        $this->settings->save($request->only([
            'gemini_api_key',
            'selected_model',
            'use_all_models',
        ]));
        
        ToastMagic::success(translate('AI settings updated successfully'));
        return back();
    }
}
