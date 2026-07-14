@extends('layouts.app')

@section('title', 'Buat Order Laundry Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="laundryOrderForm()">
     
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('laundry.orders.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Buat Order Laundry</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buat transaksi laundry kiloan atau satuan dengan hitungan otomatis.</p>
        </div>
    </div>

    <!-- Form layout -->
    <form method="POST" action="{{ route('laundry.orders.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Left Column: inputs -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer & Service Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Informasi Customer & Layanan</h3>
                
                <!-- Customer selection -->
                <div>
                    <label for="customer_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Customer <span class="text-red-500">*</span></label>
                    <select name="customer_id" id="customer_id" required class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ (old('customer_id') == $customer->id || request('customer_id') == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone ?? 'Tidak ada No HP' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                        Customer belum terdaftar? 
                        <a href="{{ route('customers.create') }}" target="_blank" class="text-blue-600 hover:underline font-semibold">Tambah cepat disini</a>
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Service Type selection -->
                    <div>
                        <label for="service_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Jenis Layanan <span class="text-red-500">*</span></label>
                        <select name="service_id" 
                                id="service_id" 
                                required 
                                x-model="selectedServiceId"
                                class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            <option value="">-- Pilih Layanan --</option>
                            <template x-for="service in services" :key="service.id">
                                <option :value="service.id" x-text="service.name + ' - Rp' + parseFloat(service.price).toLocaleString('id-ID')"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Weight / Quantity -->
                    <div>
                        <label for="weight" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Jumlah (<span x-text="selectedServiceUnit">kg</span>) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="weight" 
                               id="weight" 
                               step="0.01" 
                               min="0.1" 
                               required 
                               x-model.number="weight"
                               class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Express Option -->
                <div class="flex items-center gap-3 bg-blue-50/50 dark:bg-blue-900/10 p-3.5 rounded-xl border border-blue-100 dark:border-blue-800">
                    <input type="checkbox" 
                           name="is_express" 
                           id="is_express" 
                           value="1" 
                           x-model="isExpress"
                           class="h-4.5 w-4.5 text-blue-600 focus:ring-blue-500 border-gray-350 rounded dark:bg-gray-950">
                    <div class="text-sm">
                        <label for="is_express" class="font-bold text-blue-900 dark:text-blue-300 flex items-center gap-1.5">
                            <i data-lucide="zap" class="h-4 w-4 text-amber-500"></i> Express Service (+ Rp {{ number_format($tariffExpress, 0, ',', '.') }})
                        </label>
                        <span class="block text-xs text-blue-700 dark:text-blue-400 mt-0.5">Selesai lebih cepat. Tarif tambahan flat berlaku.</span>
                    </div>
                </div>
            </div>

            <!-- Delivery & Address Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Layanan Antar Jemput (Delivery)</h3>
                
                <div>
                    <label for="delivery_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Jenis Layanan Pengiriman</label>
                    <select name="delivery_type" 
                            id="delivery_type" 
                            x-model="deliveryType"
                            class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        <option value="none">Bawa Sendiri / None</option>
                        <option value="pickup">Jemput Saja (+ Rp {{ number_format($tariffPickup, 0, ',', '.') }})</option>
                        <option value="delivery">Antar Saja (+ Rp {{ number_format($tariffDelivery, 0, ',', '.') }})</option>
                        <option value="pickup_delivery">Antar & Jemput (+ Rp {{ number_format($tariffPickup + $tariffDelivery, 0, ',', '.') }})</option>
                    </select>
                </div>

                <!-- Address Input (Visible if delivery is selected) -->
                <div x-show="deliveryType !== 'none'" x-transition class="space-y-4">
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Alamat Pengiriman (Jika berbeda dari profil)</label>
                        <textarea name="address" 
                                  id="address" 
                                  rows="2" 
                                  placeholder="Masukkan alamat pengiriman..."
                                  class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Pickup Schedule Details (visible if pickup selected) -->
                        <div x-show="deliveryType === 'pickup' || deliveryType === 'pickup_delivery'">
                            <label class="block text-sm font-semibold text-gray-750 dark:text-gray-300">Jadwal Penjemputan</label>
                            <div class="grid grid-cols-2 gap-2 mt-1.5">
                                <input type="date" name="pickup_date" class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                                <input type="time" name="pickup_time" class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <!-- Delivery Schedule Details (visible if delivery selected) -->
                        <div x-show="deliveryType === 'delivery' || deliveryType === 'pickup_delivery'">
                            <label class="block text-sm font-semibold text-gray-750 dark:text-gray-300">Jadwal Pengantaran</label>
                            <div class="grid grid-cols-2 gap-2 mt-1.5">
                                <input type="date" name="delivery_date" class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                                <input type="time" name="delivery_time" class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes & Payment Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Detail Pembayaran & Catatan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Amount Paid -->
                    <div>
                        <label for="paid_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah Dibayar (Rupiah)</label>
                        <div class="relative mt-1.5 rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                                Rp
                            </div>
                            <input type="number" 
                                   name="paid_amount" 
                                   id="paid_amount" 
                                   min="0" 
                                   x-model.number="paidAmount"
                                   placeholder="Contoh: 10000"
                                   class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                        <select name="payment_method" 
                                id="payment_method"
                                class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white">
                            <option value="cash">Cash / Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet / QRIS</option>
                        </select>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Catatan Tambahan</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="2" 
                              placeholder="Keterangan cucian..."
                              class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white"></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Cost Calculator Summary Card -->
        <div class="space-y-6">
            <div class="bg-gradient-to-b from-slate-900 to-slate-800 text-white rounded-2xl border border-slate-700 p-6 shadow-lg space-y-6 sticky top-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Biaya</h3>
                
                <div class="space-y-4 text-sm">
                    <!-- Service Price details -->
                    <div class="flex justify-between">
                        <span class="text-slate-400">Biaya Layanan</span>
                        <span>
                            Rp <span x-text="serviceTotal.toLocaleString('id-ID')">0</span>
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-500 -mt-2">
                        <span x-text="weight">1.0</span> <span x-text="selectedServiceUnit">kg</span> x Rp <span x-text="selectedServicePrice.toLocaleString('id-ID')">0</span>
                    </div>

                    <!-- Express Fee -->
                    <template x-if="isExpress">
                        <div class="flex justify-between text-xs text-amber-400">
                            <span>Layanan Express</span>
                            <span>+ Rp <span x-text="tariffExpress.toLocaleString('id-ID')">0</span></span>
                        </div>
                    </template>

                    <!-- Pickup / Delivery Fees -->
                    <template x-if="pickupFee > 0">
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Biaya Jemput</span>
                            <span>+ Rp <span x-text="tariffPickup.toLocaleString('id-ID')">0</span></span>
                        </div>
                    </template>
                    <template x-if="deliveryFee > 0">
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Biaya Antar</span>
                            <span>+ Rp <span x-text="tariffDelivery.toLocaleString('id-ID')">0</span></span>
                        </div>
                    </template>

                    <div class="h-px bg-slate-700"></div>

                    <!-- Grand Total -->
                    <div class="flex justify-between items-baseline">
                        <span class="font-bold text-slate-300">Total Tarif</span>
                        <span class="text-xl font-bold text-blue-400">
                            Rp <span x-text="grandTotal.toLocaleString('id-ID')">0</span>
                        </span>
                    </div>

                    <!-- Amount Paid -->
                    <div class="flex justify-between text-slate-300 text-xs">
                        <span>Dibayar</span>
                        <span>
                            Rp <span x-text="paidAmount ? paidAmount.toLocaleString('id-ID') : '0'">0</span>
                        </span>
                    </div>

                    <!-- Sisa Tagihan -->
                    <div class="flex justify-between text-slate-300 text-xs font-semibold">
                        <span>Sisa Tagihan</span>
                        <span :class="remaining > 0 ? 'text-red-400' : 'text-green-400'">
                            Rp <span x-text="remaining.toLocaleString('id-ID')">0</span>
                        </span>
                    </div>

                    <!-- Payment Status Badge -->
                    <div class="pt-2 text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              :class="{
                                'bg-green-500/20 text-green-400 border border-green-500/30': paymentStatus === 'Lunas',
                                'bg-amber-500/20 text-amber-400 border border-amber-500/30': paymentStatus === 'DP (Uang Muka)',
                                'bg-red-500/20 text-red-400 border border-red-500/30': paymentStatus === 'Belum Bayar'
                              }"
                              x-text="paymentStatus">
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-700 space-y-2">
                    <button type="submit" 
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors text-center shadow-md shadow-blue-900/30">
                        Buat Order
                    </button>
                    <a href="{{ route('laundry.orders.index') }}" 
                       class="block w-full py-2.5 bg-slate-750 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-colors text-center">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('laundryOrderForm', () => ({
            services: @json($services),
            selectedServiceId: '',
            selectedServicePrice: 0,
            selectedServiceUnit: 'kg',
            weight: 1.0,
            isExpress: false,
            deliveryType: 'none',
            paidAmount: 0,
            tariffExpress: {{ $tariffExpress }},
            tariffPickup: {{ $tariffPickup }},
            tariffDelivery: {{ $tariffDelivery }},
            
            init() {
                this.$watch('selectedServiceId', value => {
                    const service = this.services.find(s => s.id == value);
                    if (service) {
                        this.selectedServicePrice = parseFloat(service.price);
                        if (service.name.toLowerCase().includes('sepatu')) {
                            this.selectedServiceUnit = 'pasang';
                        } else {
                            this.selectedServiceUnit = 'kg';
                        }
                    } else {
                        this.selectedServicePrice = 0;
                        this.selectedServiceUnit = 'kg';
                    }
                });
            },
            
            get serviceTotal() {
                return this.selectedServicePrice * this.weight;
            },
            get expressFee() {
                return this.isExpress ? this.tariffExpress : 0;
            },
            get pickupFee() {
                return (this.deliveryType === 'pickup' || this.deliveryType === 'pickup_delivery') ? this.tariffPickup : 0;
            },
            get deliveryFee() {
                return (this.deliveryType === 'delivery' || this.deliveryType === 'pickup_delivery') ? this.tariffDelivery : 0;
            },
            get totalAdditional() {
                return this.expressFee + this.pickupFee + this.deliveryFee;
            },
            get grandTotal() {
                return this.serviceTotal + this.totalAdditional;
            },
            get remaining() {
                return Math.max(0, this.grandTotal - this.paidAmount);
            },
            get paymentStatus() {
                if (this.paidAmount >= this.grandTotal && this.grandTotal > 0) {
                    return 'Lunas';
                } else if (this.paidAmount > 0) {
                    return 'DP (Uang Muka)';
                }
                return 'Belum Bayar';
            }
        }));
    });
</script>
@endsection
