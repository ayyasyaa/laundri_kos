<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Public portal index.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $laundryOrder = null;
        $tenant = null;
        $searched = false;

        if ($search) {
            $searched = true;
            // Search for laundry order by number
            $laundryOrder = LaundryOrder::with(['customer', 'service', 'deliveries'])
                ->where('order_number', 'like', "%{$search}%")
                ->first();

            // Search for tenant by exact phone number or name
            if (!$laundryOrder) {
                $tenant = Tenant::with(['room', 'customer'])
                    ->whereHas('customer', function($q) use ($search) {
                        $q->where('phone', $search)
                          ->orWhere('name', 'like', "%{$search}%");
                    })
                    ->where('status', 'aktif')
                    ->first();
            }
        }

        return view('portal.index', compact('search', 'laundryOrder', 'tenant', 'searched'));
    }
}
