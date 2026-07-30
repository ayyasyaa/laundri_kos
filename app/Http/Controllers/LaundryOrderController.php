<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryService;
use App\Models\Delivery;
use App\Models\FinanceTransaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaundryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LaundryOrder::with(['customer', 'service']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('laundry.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = LaundryService::where('is_active', true)->get();
        
        // Fetch tariffs for frontend calculation
        $tariffExpress = (float)Setting::get('fee_express', 5000);
        $tariffPickup = (float)Setting::get('fee_pickup', 3000);
        $tariffDelivery = (float)Setting::get('fee_delivery', 3000);

        return view('laundry.orders.create', compact('customers', 'services', 'tariffExpress', 'tariffPickup', 'tariffDelivery'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:laundry_services,id',
            'weight' => 'required|numeric|min:0.1',
            'is_express' => 'nullable|boolean',
            'delivery_type' => 'required|string', // none, pickup, delivery, pickup_delivery
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            
            // Optional delivery address details
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable',
            'address' => 'nullable|string',
        ]);

        $service = LaundryService::findOrFail($validated['service_id']);
        
        // 1. Calculate prices on the backend for safety
        $price = (float)$service->price * (float)$validated['weight'];
        
        $feeExpress = $request->has('is_express') ? (float)Setting::get('fee_express', 5000) : 0.00;
        
        $feePickup = in_array($validated['delivery_type'], ['pickup', 'pickup_delivery']) ? (float)Setting::get('fee_pickup', 3000) : 0.00;
        $feeDelivery = in_array($validated['delivery_type'], ['delivery', 'pickup_delivery']) ? (float)Setting::get('fee_delivery', 3000) : 0.00;
        
        $additionalFees = $feeExpress + $feePickup + $feeDelivery;
        $totalPrice = $price + $additionalFees;
        
        $paidAmount = (float)$validated['paid_amount'];
        
        // Determine payment status
        if ($paidAmount >= $totalPrice) {
            $paymentStatus = 'lunas';
            $paidAmount = $totalPrice; // Cap paid amount to total
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'dp';
        } else {
            $paymentStatus = 'belum_bayar';
        }

        // Generate unique order number: ORD-YYYYMMDD-XXX
        $todayStr = Carbon::today()->format('Ymd');
        $todayCount = LaundryOrder::whereDate('created_at', Carbon::today())->count();
        $orderNumber = 'ORD-' . $todayStr . '-' . str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);

        // Estimation completion date
        $estimationDate = Carbon::now()->addDays($service->duration_days);

        // Create laundry order
        $order = LaundryOrder::create([
            'order_number' => $orderNumber,
            'customer_id' => $validated['customer_id'],
            'service_id' => $validated['service_id'],
            'weight' => $validated['weight'],
            'price' => $price,
            'additional_fees' => $additionalFees,
            'total_price' => $totalPrice,
            'paid_amount' => $paidAmount,
            'payment_status' => $paymentStatus,
            'payment_method' => $validated['payment_method'] ?: null,
            'status' => in_array($validated['delivery_type'], ['pickup', 'pickup_delivery']) ? 'pending' : 'baru',
            'delivery_type' => $validated['delivery_type'],
            'estimation_date' => $estimationDate,
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
        ]);

        // 2. Create Delivery schedule details if applicable
        $address = $validated['address'] ?: Customer::find($validated['customer_id'])->address;
        
        if (in_array($validated['delivery_type'], ['pickup', 'pickup_delivery'])) {
            Delivery::create([
                'laundry_order_id' => $order->id,
                'type' => 'pickup',
                'status' => 'pending',
                'delivery_date' => $validated['pickup_date'] ?: Carbon::today(),
                'delivery_time' => $validated['pickup_time'] ?: Carbon::now()->format('H:i:s'),
                'address' => $address,
                'notes' => 'Jadwal penjemputan awal.',
            ]);
        }

        if (in_array($validated['delivery_type'], ['delivery', 'pickup_delivery'])) {
            Delivery::create([
                'laundry_order_id' => $order->id,
                'type' => 'delivery',
                'status' => 'pending',
                'delivery_date' => $validated['delivery_date'] ?: $estimationDate->toDateString(),
                'delivery_time' => $validated['delivery_time'] ?: '16:00:00',
                'address' => $address,
                'notes' => 'Jadwal pengantaran estimasi selesai.',
            ]);
        }

        // 3. Register financial inflow transaction if paid amount > 0
        if ($paidAmount > 0) {
            FinanceTransaction::create([
                'type' => 'income',
                'category' => 'laundry',
                'date' => Carbon::today(),
                'amount' => $paidAmount,
                'payment_method' => $validated['payment_method'] ?: 'cash',
                'notes' => "Pembayaran awal order {$orderNumber} ({$paymentStatus})",
                'sourceable_type' => LaundryOrder::class,
                'sourceable_id' => $order->id,
            ]);
        }

        return redirect()->route('laundry.orders.index')->with('success', "Order {$orderNumber} berhasil dibuat.");
    }

    /**
     * Display the specified resource.
     */
    public function show(LaundryOrder $order)
    {
        return view('laundry.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaundryOrder $order)
    {
        return redirect()->route('laundry.orders.show', $order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaundryOrder $order)
    {
        return redirect()->route('laundry.orders.show', $order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaundryOrder $order)
    {
        // Deleting order: staff cannot delete
        if (auth()->user()->isStaff()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Staff tidak diperbolehkan menghapus data order laundry.');
        }

        if ($order->financeTransactions()->exists()) {
            return redirect()->route('laundry.orders.index')->with('error', 'Gagal Hapus: Order laundry memiliki riwayat transaksi keuangan.');
        }

        $order->delete();

        return redirect()->route('laundry.orders.index')->with('success', 'Order laundry berhasil dihapus.');
    }

    /**
     * Update Order Laundry Status.
     */
    public function updateStatus(Request $request, LaundryOrder $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,baru,sedang_diambil,proses,selesai,sedang_dikirim,diambil_diantar',
        ]);

        $newStatus = $request->input('status');
        
        $order->update(['status' => $newStatus]);

        // Two-way synchronization: If order status changes, update the delivery tasks accordingly
        if ($newStatus === 'pending') {
            // Set associated pickup task back to pending if needed
            $order->deliveries()->where('type', 'pickup')->update(['status' => 'pending']);
        } elseif ($newStatus === 'sedang_diambil') {
            // Update associated pickup task to processing
            $order->deliveries()->where('type', 'pickup')->where('status', '!=', 'completed')->update(['status' => 'processing']);
        } elseif (in_array($newStatus, ['baru', 'proses', 'selesai'])) {
            // If order has progressed to baru (arrived at store), proses, or selesai, the pickup must be completed!
            $order->deliveries()->where('type', 'pickup')->where('status', '!=', 'completed')->update(['status' => 'completed']);
        }
        
        if ($newStatus === 'sedang_dikirim') {
            // Update associated delivery task to processing
            $order->deliveries()->where('type', 'delivery')->where('status', '!=', 'completed')->update(['status' => 'processing']);
        } elseif ($newStatus === 'diambil_diantar') {
            // If order is completed/delivered, both pickup and delivery should be completed
            $order->deliveries()->where('status', '!=', 'completed')->update(['status' => 'completed']);
        }

        return redirect()->back()->with('success', "Status order {$order->order_number} berhasil diperbarui menjadi " . strtoupper($newStatus));
    }

    /**
     * Update Payment details (Record down-payment or full settlement).
     */
    public function updatePayment(Request $request, LaundryOrder $order)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $newPaidAmountInput = (float)$request->input('paid_amount');
        $oldPaidAmount = (float)$order->paid_amount;
        $totalPrice = (float)$order->total_price;

        // The additional amount paid in this transaction
        $additionalPaid = $newPaidAmountInput - $oldPaidAmount;

        if ($additionalPaid <= 0) {
            return redirect()->back()->with('error', 'Nominal bayar harus lebih besar dari pembayaran sebelumnya.');
        }

        $totalPaid = $oldPaidAmount + $additionalPaid;
        
        if ($totalPaid >= $totalPrice) {
            $paymentStatus = 'lunas';
            $totalPaid = $totalPrice;
            $additionalPaid = $totalPrice - $oldPaidAmount; // Recalculate exact remaining to prevent overpaying
        } else {
            $paymentStatus = 'dp';
        }

        // Update laundry order payment status
        $order->update([
            'paid_amount' => $totalPaid,
            'payment_status' => $paymentStatus,
            'payment_method' => $request->input('payment_method'),
        ]);

        // Register new inflow transaction
        FinanceTransaction::create([
            'type' => 'income',
            'category' => 'laundry',
            'date' => Carbon::today(),
            'amount' => $additionalPaid,
            'payment_method' => $request->input('payment_method'),
            'notes' => "Pelunasan/Cicilan tambahan order {$order->order_number} ({$paymentStatus})",
            'sourceable_type' => LaundryOrder::class,
            'sourceable_id' => $order->id,
        ]);

        return redirect()->back()->with('success', "Pembayaran order {$order->order_number} berhasil dicatat.");
    }
}
