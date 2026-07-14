<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\LaundryService;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\LaundryOrder;
use App\Models\Delivery;
use App\Models\FinanceTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users & Roles
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@laundrykost.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $owner = User::create([
            'name' => 'Owner Usaha',
            'username' => 'owner',
            'email' => 'owner@laundrykost.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $staff = User::create([
            'name' => 'Staff Laundry',
            'username' => 'staff',
            'email' => 'staff@laundrykost.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 2. Settings (Key-Value)
        Setting::set('business_name', 'Lestari Laundry & Kost');
        Setting::set('business_address', 'Jl. Merdeka No. 45, Jakarta Selatan');
        Setting::set('business_phone', '081234567890');
        Setting::set('fee_express', '5000');
        Setting::set('fee_pickup', '3000');
        Setting::set('fee_delivery', '3000');

        // 3. Laundry Services
        $s1 = LaundryService::create([
            'name' => 'Laundry 1 Hari',
            'price' => 8000.00,
            'duration_days' => 1,
            'is_active' => true,
        ]);

        $s2 = LaundryService::create([
            'name' => 'Laundry 2 Hari',
            'price' => 6000.00,
            'duration_days' => 2,
            'is_active' => true,
        ]);

        $s3 = LaundryService::create([
            'name' => 'Cuci Sepatu',
            'price' => 25000.00,
            'duration_days' => 3,
            'is_active' => true,
        ]);

        // 4. Customers
        $c1 = Customer::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Mawar No. 12, Jakarta',
            'notes' => 'Pelanggan setia laundry kiloan.',
        ]);

        $c2 = Customer::create([
            'name' => 'Siti Rahma',
            'phone' => '081987654321',
            'address' => 'Kost Lestari, Kamar 101',
            'notes' => 'Penghuni Kost Kamar 101.',
        ]);

        $c3 = Customer::create([
            'name' => 'Andi Wijaya',
            'phone' => '085678901234',
            'address' => 'Kost Lestari, Kamar 102',
            'notes' => 'Penghuni Kost Kamar 102.',
        ]);

        // 5. Rooms
        $r101 = Room::create([
            'room_number' => '101',
            'price' => 1500000.00,
            'status' => 'terisi',
        ]);

        $r102 = Room::create([
            'room_number' => '102',
            'price' => 1500000.00,
            'status' => 'terisi',
        ]);

        $r103 = Room::create([
            'room_number' => '103',
            'price' => 1500000.00,
            'status' => 'kosong',
        ]);

        $r104 = Room::create([
            'room_number' => '104',
            'price' => 1800000.00,
            'status' => 'maintenance',
        ]);

        $r105 = Room::create([
            'room_number' => '105',
            'price' => 1800000.00,
            'status' => 'terisi',
        ]);

        $r106 = Room::create([
            'room_number' => '106',
            'price' => 1200000.00,
            'status' => 'kosong',
        ]);

        // 6. Tenants
        // Expiring in 7 days (today is 2026-07-13, so end_date: 2026-07-20)
        $t1 = Tenant::create([
            'name' => 'Siti Rahma',
            'phone' => '081987654321',
            'room_id' => $r101->id,
            'start_date' => Carbon::create(2026, 6, 20),
            'end_date' => Carbon::create(2026, 7, 20),
            'monthly_fee' => 1500000.00,
            'deposit' => 200000.00,
            'notes' => 'Bayar tepat waktu.',
            'status' => 'aktif',
        ]);

        // Expiring in 3 days (end_date: 2026-07-16)
        $t2 = Tenant::create([
            'name' => 'Andi Wijaya',
            'phone' => '085678901234',
            'room_id' => $r102->id,
            'start_date' => Carbon::create(2026, 6, 16),
            'end_date' => Carbon::create(2026, 7, 16),
            'monthly_fee' => 1500000.00,
            'deposit' => 200000.00,
            'notes' => 'Kunci duplikat diberikan.',
            'status' => 'aktif',
        ]);

        // Expiring today (end_date: 2026-07-13)
        $t3 = Tenant::create([
            'name' => 'Eko Prasetyo',
            'phone' => '087712345678',
            'room_id' => $r105->id,
            'start_date' => Carbon::create(2026, 6, 13),
            'end_date' => Carbon::create(2026, 7, 13),
            'monthly_fee' => 1800000.00,
            'deposit' => 200000.00,
            'notes' => 'Rencana perpanjang.',
            'status' => 'aktif',
        ]);

        // Finished tenant
        $t4 = Tenant::create([
            'name' => 'Dewi Sartika',
            'phone' => '089988887777',
            'room_id' => $r103->id,
            'start_date' => Carbon::create(2026, 1, 1),
            'end_date' => Carbon::create(2026, 6, 1),
            'monthly_fee' => 1500000.00,
            'deposit' => 200000.00,
            'notes' => 'Sudah keluar dan deposit dikembalikan.',
            'status' => 'selesai',
        ]);

        // 7. Laundry Orders
        // Order 1: Completed & Delivered
        $o1 = LaundryOrder::create([
            'order_number' => 'ORD-20260710-001',
            'customer_id' => $c1->id,
            'service_id' => $s1->id,
            'weight' => 3.5,
            'price' => 28000.00, // 3.5 * 8000
            'additional_fees' => 0.00,
            'total_price' => 28000.00,
            'paid_amount' => 28000.00,
            'payment_status' => 'lunas',
            'payment_method' => 'cash',
            'status' => 'diambil_diantar',
            'delivery_type' => 'none',
            'estimation_date' => Carbon::create(2026, 7, 11),
            'notes' => 'Selesai tepat waktu.',
            'created_by' => $staff->id,
            'created_at' => Carbon::create(2026, 7, 10, 10, 0, 0),
        ]);

        // Order 2: In Process with Pickup
        $o2 = LaundryOrder::create([
            'order_number' => 'ORD-20260712-002',
            'customer_id' => $c2->id,
            'service_id' => $s2->id,
            'weight' => 5.0,
            'price' => 30000.00, // 5.0 * 6000
            'additional_fees' => 3000.00, // pickup
            'total_price' => 33000.00,
            'paid_amount' => 33000.00,
            'payment_status' => 'lunas',
            'payment_method' => 'transfer',
            'status' => 'proses',
            'delivery_type' => 'pickup',
            'estimation_date' => Carbon::create(2026, 7, 14),
            'notes' => 'Ambil di kamar.',
            'created_by' => $staff->id,
            'created_at' => Carbon::create(2026, 7, 12, 11, 30, 0),
        ]);

        // Order 3: New Order with DP
        $o3 = LaundryOrder::create([
            'order_number' => 'ORD-20260713-003',
            'customer_id' => $c3->id,
            'service_id' => $s3->id,
            'weight' => 1.0,
            'price' => 25000.00, // Cuci sepatu
            'additional_fees' => 5000.00, // express
            'total_price' => 30000.00,
            'paid_amount' => 15000.00,
            'payment_status' => 'dp',
            'payment_method' => 'ewallet',
            'status' => 'baru',
            'delivery_type' => 'none',
            'estimation_date' => Carbon::create(2026, 7, 16),
            'notes' => 'Sepatu putih kanvas.',
            'created_by' => $staff->id,
            'created_at' => Carbon::create(2026, 7, 13, 9, 15, 0),
        ]);

        // Order 4: Done, ready for delivery, unpaid
        $o4 = LaundryOrder::create([
            'order_number' => 'ORD-20260712-004',
            'customer_id' => $c1->id,
            'service_id' => $s1->id,
            'weight' => 4.0,
            'price' => 32000.00, // 4 * 8000
            'additional_fees' => 3000.00, // delivery fee
            'total_price' => 35000.00,
            'paid_amount' => 0.00,
            'payment_status' => 'belum_bayar',
            'payment_method' => null,
            'status' => 'selesai',
            'delivery_type' => 'delivery',
            'estimation_date' => Carbon::create(2026, 7, 13),
            'notes' => 'Siap diantar sore hari.',
            'created_by' => $staff->id,
            'created_at' => Carbon::create(2026, 7, 12, 14, 0, 0),
        ]);

        // 8. Deliveries
        Delivery::create([
            'laundry_order_id' => $o2->id,
            'type' => 'pickup',
            'status' => 'completed',
            'delivery_date' => Carbon::create(2026, 7, 12),
            'delivery_time' => '12:00:00',
            'address' => 'Kost Lestari, Kamar 101',
            'notes' => 'Siti Rahma laundry bag hijau.',
        ]);

        Delivery::create([
            'laundry_order_id' => $o4->id,
            'type' => 'delivery',
            'status' => 'pending',
            'delivery_date' => Carbon::create(2026, 7, 13),
            'delivery_time' => '17:00:00',
            'address' => 'Jl. Mawar No. 12, Jakarta',
            'notes' => 'Budi Santoso - harap telepon dulu.',
        ]);

        // 9. Finance Transactions
        // Inflows from Laundry
        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'laundry',
            'date' => Carbon::create(2026, 7, 10),
            'amount' => 28000.00,
            'payment_method' => 'cash',
            'notes' => 'Pembayaran ORD-20260710-001 (Lunas)',
            'sourceable_type' => LaundryOrder::class,
            'sourceable_id' => $o1->id,
        ]);

        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'laundry',
            'date' => Carbon::create(2026, 7, 12),
            'amount' => 33000.00,
            'payment_method' => 'transfer',
            'notes' => 'Pembayaran ORD-20260712-002 (Lunas + Pickup)',
            'sourceable_type' => LaundryOrder::class,
            'sourceable_id' => $o2->id,
        ]);

        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'laundry',
            'date' => Carbon::create(2026, 7, 13),
            'amount' => 15000.00,
            'payment_method' => 'ewallet',
            'notes' => 'Pembayaran DP ORD-20260713-003',
            'sourceable_type' => LaundryOrder::class,
            'sourceable_id' => $o3->id,
        ]);

        // Inflows from Kost Rent
        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'kost',
            'date' => Carbon::create(2026, 6, 20),
            'amount' => 1500000.00,
            'payment_method' => 'transfer',
            'notes' => 'Sewa Bulanan Kamar 101 - Siti Rahma',
            'sourceable_type' => Tenant::class,
            'sourceable_id' => $t1->id,
        ]);

        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'kost',
            'date' => Carbon::create(2026, 6, 16),
            'amount' => 1500000.00,
            'payment_method' => 'transfer',
            'notes' => 'Sewa Bulanan Kamar 102 - Andi Wijaya',
            'sourceable_type' => Tenant::class,
            'sourceable_id' => $t2->id,
        ]);

        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'kost',
            'date' => Carbon::create(2026, 6, 13),
            'amount' => 1800000.00,
            'payment_method' => 'cash',
            'notes' => 'Sewa Bulanan Kamar 105 - Eko Prasetyo',
            'sourceable_type' => Tenant::class,
            'sourceable_id' => $t3->id,
        ]);

        // Expenses
        FinanceTransaction::create([
            'type' => 'expense',
            'category' => 'listrik',
            'date' => Carbon::create(2026, 7, 5),
            'amount' => 600000.00,
            'payment_method' => 'transfer',
            'notes' => 'Token Listrik Token Utama Kost & Laundry',
        ]);

        FinanceTransaction::create([
            'type' => 'expense',
            'category' => 'detergen',
            'date' => Carbon::create(2026, 7, 7),
            'amount' => 180000.00,
            'payment_method' => 'cash',
            'notes' => 'Pembelian Detergen Kiloan 20kg & Softener Lavender',
        ]);

        FinanceTransaction::create([
            'type' => 'expense',
            'category' => 'air',
            'date' => Carbon::create(2026, 7, 6),
            'amount' => 250000.00,
            'payment_method' => 'transfer',
            'notes' => 'Tagihan Air PDAM Bulan Juni',
        ]);

        FinanceTransaction::create([
            'type' => 'expense',
            'category' => 'peralatan',
            'date' => Carbon::create(2026, 7, 8),
            'amount' => 95000.00,
            'payment_method' => 'cash',
            'notes' => 'Beli hanger plastik 5 lusin',
        ]);
    }
}
