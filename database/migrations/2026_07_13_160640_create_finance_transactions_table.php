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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // income, expense
            $table->string('category'); // laundry, kost, operasional, listrik, air, detergen, peralatan, lainnya
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('cash'); // cash, transfer, ewallet
            $table->text('notes')->nullable();
            $table->string('sourceable_type')->nullable();
            $table->unsignedBigInteger('sourceable_id')->nullable();
            $table->index(['sourceable_type', 'sourceable_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
