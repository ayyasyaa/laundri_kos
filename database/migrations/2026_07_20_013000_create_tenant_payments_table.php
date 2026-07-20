<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_type')->default('dimuka'); // dimuka, dibelakang
            $table->string('payment_status')->default('belum_bayar'); // belum_bayar, lunas
            $table->string('payment_method')->nullable(); // cash, transfer, ewallet
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Backfill existing tenants
        $tenants = DB::table('tenants')->get();
        foreach ($tenants as $tenant) {
            // Find any transaction linked to this tenant
            $transaction = DB::table('finance_transactions')
                ->where('sourceable_type', 'App\Models\Tenant')
                ->where('sourceable_id', $tenant->id)
                ->first();

            $amount = $tenant->monthly_fee + $tenant->deposit;
            
            DB::table('tenant_payments')->insert([
                'tenant_id' => $tenant->id,
                'amount' => $amount,
                'payment_type' => $tenant->payment_type ?? 'dimuka',
                'payment_status' => 'lunas',
                'payment_method' => $transaction ? $transaction->payment_method : 'transfer',
                'paid_at' => $transaction ? $transaction->date : $tenant->start_date,
                'notes' => 'Pemasukan awal migrasi (Check-in awal)',
                'created_at' => $tenant->created_at,
                'updated_at' => $tenant->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_payments');
    }
};
