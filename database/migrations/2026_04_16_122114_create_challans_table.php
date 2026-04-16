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
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade')->index();
            $table->string('challan_number')->index();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('broker')->nullable();
            $table->date('date');
            $table->enum('status', ['At Mill', 'Process House', 'In Stock', 'Billed'])->default('At Mill');
            $table->integer('total_pieces')->default(0);
            $table->decimal('total_meters', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['owner_id', 'challan_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challans');
    }
};
