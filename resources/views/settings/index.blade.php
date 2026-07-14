@extends('layouts.app')

@section('title', 'Pengaturan Usaha')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pengaturan Usaha & Tarif</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Atur profil bisnis laundry/kost serta tarif biaya tambahan penjemputan, express, dan pengantaran.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf

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
            <div class="pt-6">
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

            <!-- Submit -->
            <div class="pt-6 border-t border-gray-150 dark:border-gray-750 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-sm transition-colors">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
