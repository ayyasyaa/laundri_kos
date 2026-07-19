<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TenantRenewalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_renew_form()
    {
        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now(),
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        $response = $this->get(route('tenants.renew.form', $tenant));
        $response->assertRedirect('/login');
    }

    public function test_staff_cannot_access_renew_form()
    {
        $staff = User::create([
            'name' => 'Staff User',
            'username' => 'staff_user',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now(),
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($staff)->get(route('tenants.renew.form', $tenant));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_renew_form()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now(),
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('tenants.renew.form', $tenant));
        $response->assertOk();
        $response->assertViewIs('tenants.renew');
        $response->assertViewHas('tenant');
    }

    public function test_admin_can_renew_tenant_prepaid_payment()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        
        $oldEndDate = Carbon::now()->addDays(5);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => $oldEndDate,
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->post(route('tenants.renew', $tenant), [
            'duration_months' => 3,
            'payment_type' => 'dimuka',
        ]);

        $response->assertRedirect(route('tenants.index'));
        $response->assertSessionHas('success');

        $tenant->refresh();
        $expectedEndDate = $oldEndDate->copy()->addMonths(3);
        $this->assertEquals($expectedEndDate->format('Y-m-d'), $tenant->end_date->format('Y-m-d'));
        $this->assertEquals('dimuka', $tenant->payment_type);
        $this->assertEquals('aktif', $tenant->status);

        $this->assertCount(1, $tenant->financeTransactions);
        $transaction = $tenant->financeTransactions->first();
        $this->assertEquals('income', $transaction->type);
        $this->assertEquals('kost', $transaction->category);
        $this->assertEquals(3000000, $transaction->amount);
    }

    public function test_guests_cannot_access_tenant_show()
    {
        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now(),
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        $response = $this->get(route('tenants.show', $tenant));
        $response->assertRedirect('/login');
    }

    public function test_staff_cannot_access_tenant_show()
    {
        $staff = User::create([
            'name' => 'Staff User',
            'username' => 'staff_user',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now(),
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($staff)->get(route('tenants.show', $tenant));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_tenant_show_and_see_payment_history()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $room = Room::create(['room_number' => '101', 'price' => 1000000, 'status' => 'terisi']);
        $customer = Customer::create(['name' => 'Budi Santoso', 'phone' => '081234567890']);
        $tenant = Tenant::create([
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now(),
            'monthly_fee' => 1000000,
            'deposit' => 500000,
            'status' => 'aktif',
        ]);

        // Create a transaction
        $tenant->financeTransactions()->create([
            'type' => 'income',
            'category' => 'kost',
            'date' => Carbon::now(),
            'amount' => 1500000,
            'payment_method' => 'cash',
            'notes' => 'Pembayaran awal sewa sebulan',
        ]);

        $response = $this->actingAs($admin)->get(route('tenants.show', $tenant));
        $response->assertOk();
        $response->assertViewIs('tenants.show');
        $response->assertViewHas('tenant');
        $response->assertViewHas('transactions');
        $response->assertViewHas('totalPaid', 1500000);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Pembayaran awal sewa sebulan');
    }
}
