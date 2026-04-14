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
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('challan_number');
            $table->date('challan_date');
            $table->string('party_name');
            $table->string('broker_name')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['draft', 'final', 'cancelled'])->default('final');
            $table->timestamps();

            $table->unique(['shop_id', 'challan_number']);
            $table->index(['shop_id', 'challan_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challans');
    }
};
