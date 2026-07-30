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

        // Landing page configurations
        $landingHeroTitle = Setting::get('landing_hero_title', 'Hunian Nyaman & Laundry Terpercaya');
        $landingHeroSubtitle = Setting::get('landing_hero_subtitle', 'Lestari Kost menyediakan hunian eksklusif yang nyaman, sedangkan Lestari Laundry siap merawat pakaian Anda dengan bersih, rapi, dan cepat.');
        
        $landingLaundryTitle = Setting::get('landing_laundry_title', 'Lestari Laundry');
        $landingLaundryDesc = Setting::get('landing_laundry_desc', 'Kami menawarkan berbagai paket laundry premium mulai dari kiloan, satuan, hingga layanan express. Pakaian Anda diproses secara higienis, disetrika rapi, dan diberi pewangi segar.');
        $landingLaundryFeatures = Setting::get('landing_laundry_features', 'Cuci Setrika Kiloan,Cuci Satuan Premium,Layanan Express 24 Jam,Gratis Antar Jemput');
        
        $landingKostTitle = Setting::get('landing_kost_title', 'Lestari Kost');
        $landingKostDesc = Setting::get('landing_kost_desc', 'Lestari Kost menawarkan kamar sewa bulanan dengan fasilitas lengkap yang menunjang kenyamanan istirahat Anda setelah seharian beraktivitas.');
        $landingKostFeatures = Setting::get('landing_kost_features', 'Kamar Ber-AC,Kamar Mandi Dalam,Free High-Speed Wi-Fi,Akses Gerbang 24 Jam,Keamanan CCTV');
        
        $landingWhatsapp = Setting::get('landing_whatsapp', '');
        $landingInstagram = Setting::get('landing_instagram', 'lestari.laundry.kost');

        return view('settings.index', compact(
            'businessName',
            'businessAddress',
            'businessPhone',
            'feeExpress',
            'feePickup',
            'feeDelivery',
            'landingHeroTitle',
            'landingHeroSubtitle',
            'landingLaundryTitle',
            'landingLaundryDesc',
            'landingLaundryFeatures',
            'landingKostTitle',
            'landingKostDesc',
            'landingKostFeatures',
            'landingWhatsapp',
            'landingInstagram'
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
            
            // Landing page configurations
            'landing_hero_title' => 'required|string|max:255',
            'landing_hero_subtitle' => 'required|string',
            'landing_laundry_title' => 'required|string|max:255',
            'landing_laundry_desc' => 'required|string',
            'landing_laundry_features' => 'required|string',
            'landing_kost_title' => 'required|string|max:255',
            'landing_kost_desc' => 'required|string',
            'landing_kost_features' => 'required|string',
            'landing_whatsapp' => 'nullable|string|max:20',
            'landing_instagram' => 'nullable|string|max:100',
        ]);

        Setting::set('business_name', $validated['business_name']);
        Setting::set('business_address', $validated['business_address']);
        Setting::set('business_phone', $validated['business_phone']);
        Setting::set('fee_express', $validated['fee_express']);
        Setting::set('fee_pickup', $validated['fee_pickup']);
        Setting::set('fee_delivery', $validated['fee_delivery']);
        
        Setting::set('landing_hero_title', $validated['landing_hero_title']);
        Setting::set('landing_hero_subtitle', $validated['landing_hero_subtitle']);
        Setting::set('landing_laundry_title', $validated['landing_laundry_title']);
        Setting::set('landing_laundry_desc', $validated['landing_laundry_desc']);
        Setting::set('landing_laundry_features', $validated['landing_laundry_features']);
        Setting::set('landing_kost_title', $validated['landing_kost_title']);
        Setting::set('landing_kost_desc', $validated['landing_kost_desc']);
        Setting::set('landing_kost_features', $validated['landing_kost_features']);
        Setting::set('landing_whatsapp', $validated['landing_whatsapp'] ?? '');
        Setting::set('landing_instagram', $validated['landing_instagram'] ?? '');

        return redirect()->route('settings.index')->with('success', 'Pengaturan usaha berhasil diperbarui.');
    }
}
