<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminReferSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Get a specific setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            // Table doesn't exist yet - return default
            return $default;
        }
    }

    /**
     * Set a specific setting value
     */
    public static function setValue(string $key, $value): bool
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        ) !== null;
    }

    /**
     * Check if default referral is enabled
     */
    public static function isDefaultReferralEnabled(): bool
    {
        try {
            return self::getValue('enable_default_referral', '0') === '1';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the default referral configuration
     */
    public static function getDefaultReferral(): array
    {
        try {
            return [
                'enabled' => self::isDefaultReferralEnabled(),
                'username' => self::getValue('default_referral_username'),
                'position' => self::getValue('default_referral_position')
            ];
        } catch (\Exception $e) {
            return [
                'enabled' => false,
                'username' => null,
                'position' => null
            ];
        }
    }
}
