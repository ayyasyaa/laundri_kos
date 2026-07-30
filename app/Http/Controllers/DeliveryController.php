<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Delivery::with(['order.customer']);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $deliveries = $query->orderBy('delivery_date')->orderBy('delivery_time')->paginate(15)->withQueryString();

        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Update Delivery Status.
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed',
        ]);

        $newStatus = $request->input('status');

        $delivery->update(['status' => $newStatus]);

        // Integrate with Laundry Order Status
        $order = $delivery->order;
        if ($newStatus === 'completed') {
            if ($delivery->type === 'delivery') {
                // If the delivery is completed, the laundry order is also handed over (diambil_diantar)
                $order->update(['status' => 'diambil_diantar']);
            } elseif ($delivery->type === 'pickup') {
                // If the pickup is completed, the laundry is waiting to be processed (baru)
                $order->update(['status' => 'baru']);
            }
        } elseif ($newStatus === 'processing') {
            if ($delivery->type === 'pickup') {
                // If pickup is in progress, the laundry order is being picked up (sedang_diambil)
                $order->update(['status' => 'sedang_diambil']);
            } elseif ($delivery->type === 'delivery') {
                // If delivery is in progress, the laundry order is being delivered (sedang_dikirim)
                $order->update(['status' => 'sedang_dikirim']);
            }
        }

        return redirect()->back()->with('success', "Status pengantaran " . strtoupper($delivery->type) . " order {$order->order_number} berhasil diperbarui menjadi " . strtoupper($newStatus));
    }

}
