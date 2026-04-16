<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->index();
            $table->foreign('owner_id', 'products_owner_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->decimal('default_rate', 10, 2)->default(0);
            $table->decimal('last_used_rate', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
