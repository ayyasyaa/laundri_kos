<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use App\Models\Customer;
use App\Models\FinanceTransaction;
use App\Models\TenantPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::with('room');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        } else {
            // Default show active tenants
            $query->where('status', 'aktif');
        }

        $tenants = $query->latest()->paginate(10)->withQueryString();

        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only vacant rooms can be assigned
        $rooms = Room::where('status', 'kosong')->orderBy('room_number')->get();
        return view('tenants.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_fee' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'payment_type' => 'required|string|in:dimuka,dibelakang',
            'notes' => 'nullable|string',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($room->status !== 'kosong') {
            return redirect()->back()->withInput()->with('error', 'Kamar ini sedang tidak tersedia (terisi or maintenance).');
        }

        // Find or create customer by phone
        $customer = Customer::where('phone', $validated['phone'])->first();
        if ($customer) {
            $customer->update(['name' => $validated['name']]);
        } else {
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);
        }

        // Prepare tenant data
        $tenantData = array_merge($validated, [
            'customer_id' => $customer->id,
            'status' => 'aktif'
        ]);
        unset($tenantData['name']);
        unset($tenantData['phone']);

        // 1. Create tenant
        $tenant = Tenant::create($tenantData);

        // 2. Set room to terisi
        $room->update(['status' => 'terisi']);

        // 3. Create Tenant Payment
        $firstMonthRent = (float)$validated['monthly_fee'];
        $deposit = (float)$validated['deposit'];
        $totalInitial = $firstMonthRent + $deposit;

        $paymentStatus = $validated['payment_type'] === 'dimuka' ? 'lunas' : 'belum_bayar';
        $paidAt = $validated['payment_type'] === 'dimuka' ? Carbon::parse($validated['start_date']) : null;
        $paymentMethod = $validated['payment_type'] === 'dimuka' ? 'transfer' : null;

        if ($totalInitial > 0) {
            $payment = TenantPayment::create([
                'tenant_id' => $tenant->id,
                'amount' => $totalInitial,
                'payment_type' => $validated['payment_type'],
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'paid_at' => $paidAt,
                'notes' => "Pembayaran awal sewa & deposit Kamar {$room->room_number} - Penghuni: {$validated['name']}",
            ]);

            // 4. Log financial inflow if paid in advance
            if ($validated['payment_type'] === 'dimuka') {
                FinanceTransaction::create([
                    'type' => 'income',
                    'category' => 'kost',
                    'date' => Carbon::parse($validated['start_date']),
                    'amount' => $totalInitial,
                    'payment_method' => 'transfer',
                    'notes' => "Pembayaran awal sewa & deposit Kamar {$room->room_number} - Penghuni: {$validated['name']} (Tagihan ID: {$payment->id})",
                    'sourceable_type' => Tenant::class,
                    'sourceable_id' => $tenant->id,
                ]);
            }
        }

        return redirect()->route('tenants.index')->with('success', "Penghuni {$validated['name']} berhasil check-in di Kamar {$room->room_number}.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenantPayments = $tenant->tenantPayments;

        $transactions = $tenant->financeTransactions()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPaid = $tenant->financeTransactions()
            ->where('type', 'income')
            ->sum('amount');

        return view('tenants.show', compact('tenant', 'transactions', 'totalPaid', 'tenantPayments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        // Load vacant rooms OR the tenant's current room
        $rooms = Room::where('status', 'kosong')
            ->orWhere('id', $tenant->room_id)
            ->orderBy('room_number')
            ->get();
            
        return view('tenants.edit', compact('tenant', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_fee' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'payment_type' => 'required|string|in:dimuka,dibelakang',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:aktif,selesai',
        ]);

        $oldRoomId = $tenant->room_id;
        $newRoomId = $validated['room_id'];
        $oldStatus = $tenant->status;
        $newStatus = $validated['status'];

        // Room changes handling
        if ($oldRoomId != $newRoomId) {
            $newRoom = Room::findOrFail($newRoomId);
            if ($newRoom->status !== 'kosong') {
                return redirect()->back()->withInput()->with('error', 'Kamar baru pilihan Anda sedang terisi.');
            }
            
            // Release old room
            Room::findOrFail($oldRoomId)->update(['status' => 'kosong']);
            // Occupy new room
            $newRoom->update(['status' => 'terisi']);
        }

        // Status change handling (check out)
        if ($oldStatus === 'aktif' && $newStatus === 'selesai') {
            // Tenant leaves, room becomes vacant
            Room::findOrFail($newRoomId)->update(['status' => 'kosong']);
        } elseif ($oldStatus === 'selesai' && $newStatus === 'aktif') {
            // Re-activate tenant, room becomes occupied
            $room = Room::findOrFail($newRoomId);
            if ($room->status !== 'kosong') {
                return redirect()->back()->withInput()->with('error', 'Kamar tidak dapat diaktifkan kembali karena sudah ditempati.');
            }
            $room->update(['status' => 'terisi']);
        }

        // Update linked customer details
        if ($tenant->customer) {
            $tenant->customer->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);
        }

        $tenantData = $validated;
        unset($tenantData['name']);
        unset($tenantData['phone']);

        $tenant->update($tenantData);

        return redirect()->route('tenants.index')->with('success', "Data penghuni {$tenant->name} berhasil diperbarui.");
    }

    /**
     * Remove (Check-out) the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        if (auth()->user()->isStaff()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Staff tidak diperbolehkan menghapus data penghuni kost.');
        }

        // Instead of hard deleting, we check out the tenant
        if ($tenant->status === 'aktif') {
            $tenant->update(['status' => 'selesai']);
            $tenant->room()->update(['status' => 'kosong']);
            return redirect()->route('tenants.index')->with('success', "Penghuni {$tenant->name} berhasil di-Check Out. Kamar {$tenant->room->room_number} kini kosong.");
        }

        // Hard delete completed records only (if they don't have finance transactions)
        if ($tenant->financeTransactions()->exists()) {
            return redirect()->route('tenants.index', ['status' => 'selesai'])->with('error', "Gagal Hapus: Penghuni {$tenant->name} memiliki riwayat transaksi keuangan.");
        }

        $tenant->delete();
        return redirect()->route('tenants.index', ['status' => 'selesai'])->with('success', "Rekaman lama penghuni {$tenant->name} dihapus.");
    }

    /**
     * Show the form to renew / extend Tenant Contract.
     */
    public function showRenewForm(Tenant $tenant)
    {
        if (auth()->user()->isStaff()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Staff tidak diperbolehkan memperpanjang kontrak.');
        }

        return view('tenants.renew', compact('tenant'));
    }

    /**
     * Renew / Extend Tenant Contract (Quick action for admin/owner).
     */
    public function renew(Request $request, Tenant $tenant)
    {
        if (auth()->user()->isStaff()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Staff tidak diperbolehkan memperpanjang kontrak.');
        }

        $validated = $request->validate([
            'duration_months' => 'required|integer|min:1|max:60',
            'payment_type' => 'required|string|in:dimuka,dibelakang',
        ]);

        $months = (int)$validated['duration_months'];
        $paymentType = $validated['payment_type'];

        // If the contract is already expired, starting date is today. Otherwise, add to current end date.
        $baseDate = $tenant->end_date->isPast() ? \Carbon\Carbon::now() : $tenant->end_date;
        $newEndDate = $baseDate->copy()->addMonths($months);

        // Update tenant
        $tenant->update([
            'end_date' => $newEndDate,
            'payment_type' => $paymentType,
            'status' => 'aktif',
        ]);

        // Keep room marked as occupied (terisi)
        $tenant->room()->update(['status' => 'terisi']);

        // Calculate amount to be billed
        $rentAmount = $tenant->monthly_fee * $months;

        $paymentStatus = $paymentType === 'dimuka' ? 'lunas' : 'belum_bayar';
        $paidAt = $paymentType === 'dimuka' ? \Carbon\Carbon::now() : null;
        $paymentMethod = $paymentType === 'dimuka' ? 'cash' : null;

        $payment = TenantPayment::create([
            'tenant_id' => $tenant->id,
            'amount' => $rentAmount,
            'payment_type' => $paymentType,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
            'paid_at' => $paidAt,
            'notes' => "Perpanjangan sewa Kost Kamar {$tenant->room->room_number} - {$tenant->name} ({$months} bulan)",
        ]);

        // If payment type is 'dimuka' (paid in advance), log a Finance Transaction!
        if ($paymentType === 'dimuka') {
            $transaction = \App\Models\FinanceTransaction::create([
                'type' => 'income',
                'category' => 'kost',
                'date' => \Carbon\Carbon::now(),
                'amount' => $rentAmount,
                'payment_method' => 'cash',
                'notes' => "Perpanjangan sewa Kost Kamar {$tenant->room->room_number} - {$tenant->name} ({$months} bulan) (Tagihan ID: {$payment->id})",
            ]);
            // Polymorphic link
            $tenant->financeTransactions()->save($transaction);
        }

        return redirect()->route('tenants.index')->with('success', "Kontrak sewa {$tenant->name} berhasil diperpanjang {$months} bulan s/d " . $newEndDate->format('d M Y'));
    }

    /**
     * Pay / settle a specific tenant payment.
     */
    public function payPayment(Request $request, TenantPayment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,transfer,ewallet',
        ]);

        if ($payment->payment_status === 'lunas') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $tenant = $payment->tenant;

        // Update payment status
        $payment->update([
            'payment_status' => 'lunas',
            'payment_method' => $validated['payment_method'],
            'paid_at' => \Carbon\Carbon::now(),
        ]);

        // Log financial transaction
        $transaction = \App\Models\FinanceTransaction::create([
            'type' => 'income',
            'category' => 'kost',
            'date' => \Carbon\Carbon::now(),
            'amount' => $payment->amount,
            'payment_method' => $validated['payment_method'],
            'notes' => "Pelunasan: {$payment->notes} (Tagihan ID: {$payment->id})",
        ]);
        // Polymorphic link to tenant
        $tenant->financeTransactions()->save($transaction);

        return redirect()->back()->with('success', 'Tagihan kost berhasil dilunasi.');
    }
}
