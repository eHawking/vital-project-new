<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert AI model settings
        $settings = [
            [
                'type' => 'ai_model_selected',
                'value' => 'gemini-2.5-flash',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'ai_use_all_models',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            \DB::table('business_settings')->updateOrInsert(
                ['type' => $setting['type']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            //
        });
    }
};
