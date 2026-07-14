@extends('layouts.app')

@section('title', 'Manajemen Penghuni Kost')

@section('content')
<div class="space-y-6" x-data="{ renewModalOpen: false, renewTenantName: '', renewActionUrl: '', renewMonthlyFee: 0, duration: 1, paymentType: 'dimuka' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Penghuni Kost</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Atur kontrak masuk, jatuh tempo pembayaran bulanan, dan check-out kamar.</p>
        </div>
        <div>
            <a href="{{ route('tenants.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                <i data-lucide="user-plus" class="h-4 w-4"></i> Check-in Penghuni Baru
            </a>
        </div>
    </div>

    <!-- Active vs Selesai Tabs -->
    <div class="flex gap-2">
        <a href="{{ route('tenants.index', ['status' => 'aktif']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition-all duration-200 {{ request('status', 'aktif') === 'aktif' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-650 hover:bg-gray-50' }}">
            Penghuni Aktif
        </a>
        <a href="{{ route('tenants.index', ['status' => 'selesai']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition-all duration-200 {{ request('status') === 'selesai' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-650 hover:bg-gray-50' }}">
            Riwayat Check-out (Selesai)
        </a>
    </div>

    <!-- Tenants Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Penghuni</th>
                        <th class="px-6 py-4">Kamar</th>
                        <th class="px-6 py-4">Mulai Kontrak</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4">Tarif Bulanan</th>
                        <th class="px-6 py-4">Deposit</th>
                        <th class="px-6 py-4">Countdown Tempo</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition-colors">
                            <!-- Name -->
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 dark:text-white block">{{ $tenant->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tenant->phone }}</span>
                            </td>
                            
                            <!-- Room -->
                            <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400">
                                Kamar {{ $tenant->room->room_number }}
                            </td>
                            
                            <!-- Start Date -->
                            <td class="px-6 py-4 text-gray-650 dark:text-gray-300">
                                {{ $tenant->start_date->format('d M Y') }}
                            </td>

                            <!-- End Date -->
                            <td class="px-6 py-4 text-gray-650 dark:text-gray-300">
                                {{ $tenant->end_date->format('d M Y') }}
                            </td>

                            <!-- Fee -->
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($tenant->monthly_fee, 0, ',', '.') }}
                            </td>

                            <!-- Deposit -->
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                Rp {{ number_format($tenant->deposit, 0, ',', '.') }}
                            </td>

                            <!-- Countdown / Reminder badge -->
                            <td class="px-6 py-4">
                                @if($tenant->status === 'aktif')
                                    @php
                                        $rem = $tenant->reminder_status;
                                        $days = $tenant->days_remaining;
                                    @endphp
                                    
                                    @if($rem === 'overdue')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Lewat {{ abs($days) }} Hari!
                                        </span>
                                    @elseif($rem === 'today')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400 animate-pulse">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Jatuh Tempo Hari Ini!
                                        </span>
                                    @elseif($rem === 'danger')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span> Sisa {{ $days }} Hari
                                        </span>
                                    @elseif($rem === 'warning')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span> Sisa {{ $days }} Hari
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span> Sisa {{ $days }} Hari
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 italic">Selesai (Check-out)</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if($tenant->status === 'aktif' && !auth()->user()->isStaff())
                                        <button type="button" 
                                                @click="renewModalOpen = true; renewTenantName = '{{ $tenant->name }}'; renewActionUrl = '{{ route('tenants.renew', $tenant) }}'; renewMonthlyFee = {{ (int)$tenant->monthly_fee }}" 
                                                class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 rounded-lg transition-colors" 
                                                title="Perpanjang Kontrak">
                                            <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                                        </button>
                                    @endif

                                    <a href="https://wa.me/{{ $tenant->phone }}" target="_blank" class="p-2 bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400 rounded-lg transition-colors" title="Hubungi WhatsApp">
                                        <i data-lucide="message-square" class="h-4 w-4"></i>
                                    </a>
                                    
                                    <a href="{{ route('tenants.edit', $tenant) }}" class="p-2 bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-lg transition-colors" title="Ubah Kontrak">
                                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                                    </a>

                                    @if (!auth()->user()->isStaff())
                                        <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melakukan Check Out pada penghuni ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            @if ($tenant->status === 'aktif')
                                                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-650 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 rounded-lg transition-colors" title="Check Out (Keluarkan)">
                                                    <i data-lucide="log-out" class="h-4 w-4"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-650 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 rounded-lg transition-colors" title="Hapus Permanen Catatan">
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                Tidak ada data penghuni kost.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Renewal Modal -->
    <div x-show="renewModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden"
             @click.away="renewModalOpen = false">
            
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Perpanjang Kontrak Kost</h3>
                <button type="button" @click="renewModalOpen = false" class="text-gray-400 hover:text-gray-650 dark:hover:text-gray-350">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form :action="renewActionUrl" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <span class="text-xs text-gray-400 block uppercase font-bold tracking-wider">Nama Penghuni</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white block mt-1" x-text="renewTenantName"></span>
                </div>

                <!-- Duration Selection -->
                <div>
                    <label for="duration_months" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Durasi Perpanjangan</label>
                    <select name="duration_months" id="duration_months" required x-model.number="duration" class="mt-1.5 block w-full px-3.5 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                        <option value="1">1 Bulan</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan (1 Tahun)</option>
                    </select>
                </div>

                <!-- Payment Type -->
                <div>
                    <label for="payment_type" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Jenis Pembayaran</label>
                    <select name="payment_type" id="payment_type" required x-model="paymentType" class="mt-1.5 block w-full px-3.5 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                        <option value="dimuka">Bayar Di Muka (Langsung Masuk Kas)</option>
                        <option value="dibelakang">Bayar Di Belakang (Tempo Akhir)</option>
                    </select>
                </div>

                <!-- Live Tariff Summary -->
                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tarif Bulanan:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">Rp <span x-text="renewMonthlyFee.toLocaleString('id-ID')"></span></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-150 dark:border-gray-750 pt-1 font-bold text-gray-900 dark:text-white">
                        <span>Estimasi Tagihan:</span>
                        <span>Rp <span x-text="(duration * renewMonthlyFee).toLocaleString('id-ID')"></span></span>
                    </div>
                    <template x-if="paymentType === 'dimuka'">
                        <span class="block text-[10px] text-green-600 font-semibold mt-2">* Pembayaran dimuka akan otomatis tercatat sebagai Pemasukan di Kas Keuangan.</span>
                    </template>
                </div>

                <div class="pt-4 border-t border-gray-150 dark:border-gray-750 flex justify-end gap-2">
                    <button type="button" @click="renewModalOpen = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                        Perpanjang Kontrak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
