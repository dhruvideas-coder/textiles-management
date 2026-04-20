<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_details', function (Blueprint $table) {
            $table->string('manufacturers_dealers_in')->nullable()->after('gstin');
        });
    }

    public function down(): void
    {
        Schema::table('business_details', function (Blueprint $table) {
            $table->dropColumn('manufacturers_dealers_in');
        });
    }
};
