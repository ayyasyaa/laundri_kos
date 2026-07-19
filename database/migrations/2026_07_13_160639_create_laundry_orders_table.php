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
        Schema::create('laundry_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('service_id')->constrained('laundry_services')->onDelete('restrict');
            $table->decimal('weight', 8, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('additional_fees', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->string('payment_status')->default('belum_bayar'); // belum_bayar, dp, lunas
            $table->string('payment_method')->nullable(); // cash, transfer, ewallet
            $table->string('status')->default('baru'); // baru, proses, selesai, diambil_diantar
            $table->string('delivery_type')->default('none'); // none, pickup, delivery, pickup_delivery
            $table->timestamp('estimation_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundry_orders');
    }
};
