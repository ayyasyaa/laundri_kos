<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_status');
            $table->index('delivery_type');
            $table->index('created_at');
            $table->index(['status', 'delivery_type']);
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->index('type');
            $table->index('category');
            $table->index('date');
            $table->index('payment_method');
            $table->index(['type', 'category', 'date']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->index('status');
            $table->index('end_date');
            $table->index('start_date');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->index('type');
            $table->index('status');
            $table->index('delivery_date');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['delivery_type']);
            $table->dropIndex(['status', 'delivery_type']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['category']);
            $table->dropIndex(['date']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['type', 'category', 'date']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['end_date']);
            $table->dropIndex(['start_date']);
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['delivery_date']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
