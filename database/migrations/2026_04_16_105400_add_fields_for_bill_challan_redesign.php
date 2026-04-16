<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('name');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('hsn_code')->nullable()->after('design_number');
        });

        Schema::table('challans', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('challan_number');
        });

        Schema::table('challan_items', function (Blueprint $table) {
            $table->json('measurements')->nullable()->after('remarks');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('bill_number');
            $table->string('challan_number')->nullable()->after('order_number');
            $table->string('broker_name')->nullable()->after('challan_number');
            $table->string('delivered_to')->nullable()->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['order_number', 'challan_number', 'broker_name', 'delivered_to']);
        });

        Schema::table('challan_items', function (Blueprint $table) {
            $table->dropColumn('measurements');
        });

        Schema::table('challans', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('hsn_code');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'bank_name', 'account_number', 'ifsc_code']);
        });
    }
};
