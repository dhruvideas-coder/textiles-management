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
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade')->index();
            $table->foreignId('challan_id')->constrained('challans')->onDelete('cascade');
            $table->string('bill_number');
            $table->decimal('total_meters', 10, 2)->default(0);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('cgst_amount', 10, 2)->default(0);
            $table->decimal('sgst_amount', 10, 2)->default(0);
            $table->decimal('final_total', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['owner_id', 'bill_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
