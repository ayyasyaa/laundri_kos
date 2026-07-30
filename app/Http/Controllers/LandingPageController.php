<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\LaundryService;
use App\Models\Room;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Display the public landing page.
     */
    public function index()
    {
        // Business settings
        $businessName = Setting::get('business_name', 'Lestari Laundry & Kost');
        $businessAddress = Setting::get('business_address', 'Jl. Merdeka No. 45, Jakarta Selatan');
        $businessPhone = Setting::get('business_phone', '081234567890');

        // Landing page copy settings with defaults
        $landingHeroTitle = Setting::get('landing_hero_title', 'Hunian Nyaman & Laundry Terpercaya');
        $landingHeroSubtitle = Setting::get('landing_hero_subtitle', 'Lestari Kost menyediakan hunian eksklusif yang nyaman, sedangkan Lestari Laundry siap merawat pakaian Anda dengan bersih, rapi, dan cepat.');
        
        $landingLaundryTitle = Setting::get('landing_laundry_title', 'Lestari Laundry');
        $landingLaundryDesc = Setting::get('landing_laundry_desc', 'Kami menawarkan berbagai paket laundry premium mulai dari kiloan, satuan, hingga layanan express. Pakaian Anda diproses secara higienis, disetrika rapi, dan diberi pewangi segar.');
        $landingLaundryFeatures = Setting::get('landing_laundry_features', 'Cuci Setrika Kiloan,Cuci Satuan Premium,Layanan Express 24 Jam,Gratis Antar Jemput');
        
        $landingKostTitle = Setting::get('landing_kost_title', 'Lestari Kost');
        $landingKostDesc = Setting::get('landing_kost_desc', 'Lestari Kost menawarkan kamar sewa bulanan dengan fasilitas lengkap yang menunjang kenyamanan istirahat Anda setelah seharian beraktivitas.');
        $landingKostFeatures = Setting::get('landing_kost_features', 'Kamar Ber-AC,Kamar Mandi Dalam,Free High-Speed Wi-Fi,Akses Gerbang 24 Jam,Keamanan CCTV');
        
        $landingWhatsapp = Setting::get('landing_whatsapp', '');
        if (empty($landingWhatsapp)) {
            $landingWhatsapp = $businessPhone;
        }
        $landingInstagram = Setting::get('landing_instagram', 'lestari.laundry.kost');

        // Parse features into arrays
        $laundryFeaturesArray = array_map('trim', explode(',', $landingLaundryFeatures));
        $kostFeaturesArray = array_map('trim', explode(',', $landingKostFeatures));

        // Get operational data
        $laundryServices = LaundryService::where('is_active', true)->get();
        $rooms = Room::orderBy('room_number')->get();

        // Calculate statistics
        $availableRoomsCount = $rooms->where('status', 'kosong')->count();
        $totalRoomsCount = $rooms->count();

        return view('landing', compact(
            'businessName',
            'businessAddress',
            'businessPhone',
            'landingHeroTitle',
            'landingHeroSubtitle',
            'landingLaundryTitle',
            'landingLaundryDesc',
            'laundryFeaturesArray',
            'landingKostTitle',
            'landingKostDesc',
            'kostFeaturesArray',
            'landingWhatsapp',
            'landingInstagram',
            'laundryServices',
            'rooms',
            'availableRoomsCount',
            'totalRoomsCount'
        ));
    }
}
