<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('challans', function (Blueprint $table) {
            $table->foreignId('business_detail_id')
                ->nullable()
                ->after('owner_id')
                ->constrained('business_details')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('challans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('business_detail_id');
        });
    }
};
