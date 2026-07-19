@extends('layouts.app')

@section('title', 'Perpanjang Kontrak Kost')

@section('content')
<div class="max-w-xl mx-auto space-y-6" x-data="{ duration: 1, paymentType: 'dimuka', monthlyFee: {{ (int)$tenant->monthly_fee }} }">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('tenants.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Perpanjang Kontrak Kost</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui jangka waktu sewa kamar untuk penghuni kost.</p>
        </div>
    </div>

    <!-- Tenant Profile Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm flex items-start gap-4">
        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
            <i data-lucide="user" class="h-6 w-6"></i>
        </div>
        <div class="space-y-2 flex-1">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ $tenant->name }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1.5 text-xs text-gray-500 dark:text-gray-400">
                <div>Kamar: <span class="font-semibold text-gray-700 dark:text-gray-300">Kamar {{ $tenant->room->room_number }}</span></div>
                <div>Tarif Sewa: <span class="font-semibold text-gray-700 dark:text-gray-300">Rp {{ number_format($tenant->monthly_fee, 0, ',', '.') }}/bln</span></div>
                <div>Status Kontrak: 
                    @if($tenant->status === 'aktif')
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-150 text-gray-650 dark:bg-gray-700 dark:text-gray-300 border border-gray-250 dark:border-gray-600">Selesai</span>
                    @endif
                </div>
                <div>Sisa Kontrak: 
                    @php $days = $tenant->days_remaining; @endphp
                    @if($days < 0)
                        <span class="text-red-500 font-bold">Lewat {{ abs($days) }} hari</span>
                    @elseif($days == 0)
                        <span class="text-yellow-600 font-bold">Hari ini</span>
                    @else
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ $days }} hari</span>
                    @endif
                </div>
                <div class="sm:col-span-2 mt-1">
                    Kontrak Saat Ini: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $tenant->start_date->format('d M Y') }} s/d {{ $tenant->end_date->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('tenants.renew', $tenant) }}" class="space-y-5">
            @csrf

            <!-- Duration Selection -->
            <div>
                <label for="duration_months" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Durasi Perpanjangan <span class="text-red-500">*</span></label>
                <select name="duration_months" 
                        id="duration_months" 
                        required 
                        x-model.number="duration" 
                        class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    <option value="1">1 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">12 Bulan (1 Tahun)</option>
                </select>
            </div>

            <!-- Payment Type -->
            <div>
                <label for="payment_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Jenis Pembayaran <span class="text-red-500">*</span></label>
                <select name="payment_type" 
                        id="payment_type" 
                        required 
                        x-model="paymentType" 
                        class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    <option value="dimuka">Bayar Di Muka (Langsung Masuk Kas)</option>
                    <option value="dibelakang">Bayar Di Belakang (Tempo Akhir)</option>
                </select>
            </div>

            <!-- Live Calculation & Summary -->
            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 space-y-2">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rincian Perpanjangan</h4>
                
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tarif Kamar Bulanan</span>
                    <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($tenant->monthly_fee, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between text-sm border-t border-gray-150 dark:border-gray-750 pt-2">
                    <span class="text-gray-500">Estimasi Total Tagihan</span>
                    <span class="font-bold text-blue-600 dark:text-blue-400 text-base">Rp <span x-text="(duration * monthlyFee).toLocaleString('id-ID')"></span></span>
                </div>

                <div class="text-[11px] text-gray-400 dark:text-gray-500 pt-1 leading-relaxed">
                    <template x-if="paymentType === 'dimuka'">
                        <span class="text-green-600 dark:text-green-400 font-medium">✓ Pembayaran di muka akan otomatis dicatat sebagai pemasukan baru di Kas Keuangan.</span>
                    </template>
                    <template x-if="paymentType === 'dibelakang'">
                        <span>ⓘ Pembayaran di belakang tidak langsung tercatat di Kas, tagihan ditangguhkan sampai masa sewa selesai.</span>
                    </template>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-150 dark:border-gray-750 flex items-center justify-end gap-3">
                <a href="{{ route('tenants.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i data-lucide="check" class="h-4 w-4"></i>
                    Simpan Perpanjangan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
