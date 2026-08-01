<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $orders = $customer->orders()->latest()->paginate(10);
        $unpaidTotal = (float)$customer->orders()->whereIn('payment_status', ['belum_bayar', 'dp'])->sum(\Illuminate\Support\Facades\DB::raw('total_price - paid_amount'));
        $activeOrdersCount = $customer->orders()->whereIn('status', ['baru', 'proses', 'selesai'])->count();
        
        // Kost Integration
        $activeTenant = $customer->tenants()->where('status', 'aktif')->with('room')->first();
        $tenants = $customer->tenants()->with('room')->latest()->get();
        
        $unpaidRentTotal = 0;
        $tenantPayments = collect();
        if ($customer->tenants()->exists()) {
            $tenantIds = $customer->tenants()->pluck('id');
            $unpaidRentTotal = \App\Models\TenantPayment::whereIn('tenant_id', $tenantIds)
                ->where('payment_status', 'belum_bayar')
                ->sum('amount');
                
            $tenantPayments = \App\Models\TenantPayment::whereIn('tenant_id', $tenantIds)
                ->with('tenant.room')
                ->latest()
                ->get();
        }
        
        return view('customers.show', compact(
            'customer', 
            'orders', 
            'unpaidTotal', 
            'activeOrdersCount',
            'activeTenant',
            'tenants',
            'unpaidRentTotal',
            'tenantPayments'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Staff cannot delete master data (Customer is master data)
        if (auth()->user()->isStaff()) {
            return redirect()->route('customers.index')->with('error', 'Akses Ditolak: Staff tidak diperbolehkan menghapus data master.');
        }

        if ($customer->orders()->exists() || $customer->tenants()->exists()) {
            return redirect()->route('customers.index')->with('error', 'Gagal Hapus: Customer memiliki riwayat laundry atau sewa kamar.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
