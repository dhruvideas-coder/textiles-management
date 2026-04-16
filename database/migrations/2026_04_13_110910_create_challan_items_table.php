<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('challan_id')->constrained('challans')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->unsignedInteger('pieces')->default(0);
            $table->decimal('meters', 12, 2)->default(0);
            $table->decimal('weight', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['shop_id', 'challan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challan_items');
    }
};
