<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->index();
            $table->string('challan_number')->index();
            $table->foreignId('customer_id')->index();
            $table->foreignId('product_id')->index();
            $table->string('broker')->nullable();
            $table->date('date');
            $table->enum('status', ['At Mill', 'Process House', 'In Stock', 'Billed'])->default('At Mill');
            $table->integer('total_pieces')->default(0);
            $table->decimal('total_meters', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['owner_id', 'challan_number'], 'challans_owner_challan_unique');
            $table->foreign('owner_id', 'challans_owner_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id', 'challans_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('product_id', 'challans_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challans');
    }
};
