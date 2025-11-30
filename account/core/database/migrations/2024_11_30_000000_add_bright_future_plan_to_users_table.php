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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('bright_future_plan')->default(0)->after('is_share');
            $table->decimal('bright_future_balance', 28, 8)->default(0)->after('bright_future_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bright_future_plan');
            $table->dropColumn('bright_future_balance');
        });
    }
};
