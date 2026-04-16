<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->index();
            $table->foreignId('challan_id')->index();
            $table->string('bill_number');
            $table->decimal('total_meters', 10, 2)->default(0);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('cgst_amount', 10, 2)->default(0);
            $table->decimal('sgst_amount', 10, 2)->default(0);
            $table->decimal('final_total', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['owner_id', 'bill_number'], 'bills_owner_bill_unique');
            $table->foreign('owner_id', 'bills_owner_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('challan_id', 'bills_challan_id_foreign')->references('id')->on('challans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
