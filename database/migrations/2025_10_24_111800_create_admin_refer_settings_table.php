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
        Schema::create('admin_refer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Insert default values
        DB::table('admin_refer_settings')->insert([
            [
                'key' => 'default_referral_username',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'default_referral_position',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'enable_default_referral',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_refer_settings');
    }
};
