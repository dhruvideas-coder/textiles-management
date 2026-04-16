<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->index();
            $table->foreign('owner_id', 'customers_owner_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('GSTIN')->nullable();
            $table->string('mobile_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
