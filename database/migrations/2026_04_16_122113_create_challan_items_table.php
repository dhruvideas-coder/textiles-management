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
            $table->foreignId('challan_id')->constrained('challans')->onDelete('cascade')->index();
            $table->integer('column_no');
            $table->integer('row_no');
            $table->decimal('meters', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['challan_id', 'column_no', 'row_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challan_items');
    }
};
