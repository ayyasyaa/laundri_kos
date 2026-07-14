<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $businessName = Setting::get('business_name', 'Lestari Laundry & Kost');
        $businessAddress = Setting::get('business_address', 'Jl. Merdeka No. 45, Jakarta');
        $businessPhone = Setting::get('business_phone', '081234567890');
        
        $feeExpress = Setting::get('fee_express', 5000);
        $feePickup = Setting::get('fee_pickup', 3000);
        $feeDelivery = Setting::get('fee_delivery', 3000);

        return view('settings.index', compact(
            'businessName',
            'businessAddress',
            'businessPhone',
            'feeExpress',
            'feePickup',
            'feeDelivery'
        ));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string',
            'business_phone' => 'required|string|max:20',
            'fee_express' => 'required|numeric|min:0',
            'fee_pickup' => 'required|numeric|min:0',
            'fee_delivery' => 'required|numeric|min:0',
        ]);

        Setting::set('business_name', $validated['business_name']);
        Setting::set('business_address', $validated['business_address']);
        Setting::set('business_phone', $validated['business_phone']);
        Setting::set('fee_express', $validated['fee_express']);
        Setting::set('fee_pickup', $validated['fee_pickup']);
        Setting::set('fee_delivery', $validated['fee_delivery']);

        return redirect()->route('settings.index')->with('success', 'Pengaturan usaha berhasil diperbarui.');
    }
}
