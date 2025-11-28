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
        Schema::create('ai_generated_images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('url');
            $table->string('type')->default('product'); // product, placeholder, etc.
            $table->string('angle')->nullable(); // front, back, left, right, etc.
            $table->integer('width')->default(500);
            $table->integer('height')->default(500);
            $table->integer('size')->nullable(); // file size in bytes
            $table->string('prompt')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('generated_by')->nullable()->comment('Admin/Seller user ID');
            $table->timestamps();
            
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_generated_images');
    }
};
