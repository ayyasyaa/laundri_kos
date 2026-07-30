@extends('layouts.app')

@section('title', 'Pengaturan Usaha')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ activeTab: 'general' }">
    <!-- Header with Tabs -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pengaturan Usaha & Landing Page</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi bisnis, tarif operasional, dan konten halaman depan publik.</p>
        </div>
        
        <div class="flex space-x-2 mt-4 md:mt-0 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl">
            <button type="button" 
                    @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'bg-white dark:bg-gray-800 text-blue-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" 
                    class="px-4 py-2 text-xs font-semibold rounded-lg transition-all focus:outline-none">
                Profil & Tarif
            </button>
            <button type="button" 
                    @click="activeTab = 'landing'" 
                    :class="activeTab === 'landing' ? 'bg-white dark:bg-gray-800 text-blue-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" 
                    class="px-4 py-2 text-xs font-semibold rounded-lg transition-all focus:outline-none">
                Konten Landing Page
            </button>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf

            <!-- TAB 1: Profil & Tarif Usaha -->
            <div x-show="activeTab === 'general'" class="space-y-6">
                <!-- Section: Business Profile -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">Profil Usaha</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Usaha <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="business_name" 
                                   id="business_name" 
                                   value="{{ old('business_name', $businessName) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        </div>

                        <!-- Business Phone -->
                        <div>
                            <label for="business_phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nomor Telepon Toko <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="business_phone" 
                                   id="business_phone" 
                                   value="{{ old('business_phone', $businessPhone) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Business Address -->
                    <div class="mt-4">
                        <label for="business_address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Alamat Usaha <span class="text-red-500">*</span></label>
                        <textarea name="business_address" 
                                  id="business_address" 
                                  rows="3" 
                                  required
                                  class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">{{ old('business_address', $businessAddress) }}</textarea>
                    </div>
                </div>

                <!-- Section: Service Surcharges -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">Biaya Tambahan Laundry</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <!-- Express Fee -->
                        <div>
                            <label for="fee_express" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tarif Layanan Express (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1.5 rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                                    Rp
                                </div>
                                <input type="number" 
                                       name="fee_express" 
                                       id="fee_express" 
                                       value="{{ old('fee_express', (int)$feeExpress) }}" 
                                       required 
                                       min="0"
                                       class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <!-- Pickup Fee -->
                        <div>
                            <label for="fee_pickup" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tarif Penjemputan / Pickup (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1.5 rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                                    Rp
                                </div>
                                <input type="number" 
                                       name="fee_pickup" 
                                       id="fee_pickup" 
                                       value="{{ old('fee_pickup', (int)$feePickup) }}" 
                                       required 
                                       min="0"
                                       class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <!-- Delivery Fee -->
                        <div>
                            <label for="fee_delivery" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tarif Pengantaran / Delivery (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1.5 rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                                    Rp
                                </div>
                                <input type="number" 
                                       name="fee_delivery" 
                                       id="fee_delivery" 
                                       value="{{ old('fee_delivery', (int)$feeDelivery) }}" 
                                       required 
                                       min="0"
                                       class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Pengaturan Landing Page -->
            <div x-show="activeTab === 'landing'" class="space-y-6" x-cloak>
                <!-- Section: Hero Header -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">Hero & Header Slogan</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Hero Title -->
                        <div>
                            <label for="landing_hero_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Judul Utama Hero <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="landing_hero_title" 
                                   id="landing_hero_title" 
                                   value="{{ old('landing_hero_title', $landingHeroTitle) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        </div>

                        <!-- Hero Subtitle -->
                        <div>
                            <label for="landing_hero_subtitle" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Sub-Judul Hero <span class="text-red-500">*</span></label>
                            <textarea name="landing_hero_subtitle" 
                                      id="landing_hero_subtitle" 
                                      rows="3" 
                                      required
                                      class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">{{ old('landing_hero_subtitle', $landingHeroSubtitle) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section: Laundry Section Config -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">Pengaturan Seksi Laundry</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="landing_laundry_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Layanan Laundry <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="landing_laundry_title" 
                                   id="landing_laundry_title" 
                                   value="{{ old('landing_laundry_title', $landingLaundryTitle) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        </div>

                        <div>
                            <label for="landing_laundry_desc" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Deskripsi Laundry <span class="text-red-500">*</span></label>
                            <textarea name="landing_laundry_desc" 
                                      id="landing_laundry_desc" 
                                      rows="3" 
                                      required
                                      class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">{{ old('landing_laundry_desc', $landingLaundryDesc) }}</textarea>
                        </div>

                        <div>
                            <label for="landing_laundry_features" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Keunggulan Laundry (Pisahkan dengan koma) <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="landing_laundry_features" 
                                   id="landing_laundry_features" 
                                   value="{{ old('landing_laundry_features', $landingLaundryFeatures) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contoh: Cuci Kilat, Antar Jemput Gratis, Detergen Ramah Lingkungan</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Kost Section Config -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">Pengaturan Seksi Kost</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="landing_kost_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Layanan Kost <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="landing_kost_title" 
                                   id="landing_kost_title" 
                                   value="{{ old('landing_kost_title', $landingKostTitle) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        </div>

                        <div>
                            <label for="landing_kost_desc" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Deskripsi Kost <span class="text-red-500">*</span></label>
                            <textarea name="landing_kost_desc" 
                                      id="landing_kost_desc" 
                                      rows="3" 
                                      required
                                      class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">{{ old('landing_kost_desc', $landingKostDesc) }}</textarea>
                        </div>

                        <div>
                            <label for="landing_kost_features" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fasilitas Kost (Pisahkan dengan koma) <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="landing_kost_features" 
                                   id="landing_kost_features" 
                                   value="{{ old('landing_kost_features', $landingKostFeatures) }}" 
                                   required 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contoh: Kamar Mandi Dalam, Kasur Springbed, Wifi Cepat, Keamanan CCTV</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Media & Sosmed -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">Kontak & Media Sosial</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- WhatsApp Link -->
                        <div>
                            <label for="landing_whatsapp" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nomor WhatsApp Hubungi Kami</label>
                            <input type="text" 
                                   name="landing_whatsapp" 
                                   id="landing_whatsapp" 
                                   placeholder="Contoh: 6281234567890"
                                   value="{{ old('landing_whatsapp', $landingWhatsapp) }}" 
                                   class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika ingin menyamakan dengan nomor telepon toko.</p>
                        </div>

                        <!-- Instagram Link -->
                        <div>
                            <label for="landing_instagram" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Username Instagram</label>
                            <div class="mt-1.5 relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                                    @
                                </div>
                                <input type="text" 
                                       name="landing_instagram" 
                                       id="landing_instagram" 
                                       placeholder="Username saja"
                                       value="{{ old('landing_instagram', $landingInstagram) }}" 
                                       class="block w-full pl-8 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-gray-150 dark:border-gray-750 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-sm transition-colors focus:outline-none">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
