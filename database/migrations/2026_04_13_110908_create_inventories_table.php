<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('current_stock_meters', 12, 2)->default(0);
            $table->decimal('low_stock_threshold', 12, 2)->default(0);
            $table->decimal('rate', 12, 2)->default(0);
            $table->string('unit', 30)->default('meters');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['shop_id', 'name']);
            $table->index(['shop_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
