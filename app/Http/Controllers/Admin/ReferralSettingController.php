<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReferSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ReferralSettingController extends Controller
{
    /**
     * Display the referral settings page
     */
    public function index(): View
    {
        $defaultReferral = AdminReferSetting::getDefaultReferral();
        
        return view('admin-views.referral-setting.index', compact('defaultReferral'));
    }

    /**
     * Update the default referral settings
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'default_referral_username' => 'nullable|string|max:255',
            'default_referral_position' => 'nullable|in:left,right,1,2',
            'enable_default_referral' => 'nullable|in:0,1'
        ]);

        // Check if username exists in account database if provided
        if ($request->filled('default_referral_username')) {
            try {
                $userExists = \DB::connection('mysql2')->table('users')
                    ->where('username', $request->default_referral_username)
                    ->exists();

                if (!$userExists) {
                    ToastMagic::error(translate('Username not found in system. Please enter a valid username.'));
                    return back();
                }
            } catch (\Exception $e) {
                ToastMagic::error(translate('Could not verify username. Please check database connection.'));
                return back();
            }
        }

        // Normalize position
        $position = $request->default_referral_position;
        if ($position) {
            $position = ($position === '1' || $position === 'left') ? 'left' : 'right';
        }

        // Update settings
        AdminReferSetting::setValue('default_referral_username', $request->default_referral_username);
        AdminReferSetting::setValue('default_referral_position', $position);
        AdminReferSetting::setValue('enable_default_referral', $request->has('enable_default_referral') ? '1' : '0');

        ToastMagic::success(translate('Default referral settings updated successfully!'));
        return back();
    }
}
