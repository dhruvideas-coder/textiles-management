<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->string('metric');
            $table->string('period', 7);
            $table->unsignedInteger('used')->default(0);
            $table->unsignedInteger('limit_value')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['shop_id', 'metric', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
