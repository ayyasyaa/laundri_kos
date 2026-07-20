@extends('layouts.app')

@section('title', 'Detail Customer & Riwayat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('customers.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Profil Customer</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat transaksi pelanggan laundry.</p>
        </div>
    </div>

    <!-- Customer Quick Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $tenants->isNotEmpty() ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-6">
        <!-- Unpaid Bills Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                <i data-lucide="alert-circle" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tagihan Laundry</p>
                <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mt-1">Rp {{ number_format($unpaidTotal, 0, ',', '.') }}</h3>
            </div>
        </div>

        @if($tenants->isNotEmpty())
        <!-- Unpaid Rent Card (Kost) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl">
                <i data-lucide="home" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tagihan Kost</p>
                <h3 class="text-lg font-bold text-rose-650 dark:text-rose-400 mt-1">Rp {{ number_format($unpaidRentTotal, 0, ',', '.') }}</h3>
            </div>
        </div>
        @endif

        <!-- Active Orders Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                <i data-lucide="loader-2" class="h-6 w-6 {{ $activeOrdersCount > 0 ? 'animate-spin' : '' }}"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cucian Sedang Aktif</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $activeOrdersCount }} Cucian</h3>
            </div>
        </div>

        <!-- Quick Order Shortcut Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl border border-slate-800 p-5 shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Registrasi Laundry</p>
                <h3 class="text-sm font-bold text-white mt-1">Buat Order Baru</h3>
            </div>
            <a href="{{ route('laundry.orders.create', ['customer_id' => $customer->id]) }}" class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors" title="Buat Order Baru">
                <i data-lucide="plus" class="h-5 w-5"></i>
            </a>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm self-start">
            <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-gray-700">
                <div class="h-16 w-16 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center font-bold text-2xl mb-4">
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">Customer</span>
            </div>
            
            <div class="py-6 space-y-4 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Nomor HP / WhatsApp</span>
                    <span class="block mt-1 font-medium text-gray-850 dark:text-gray-200">
                        @if($customer->phone)
                            <a href="https://wa.me/{{ $customer->phone }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline flex items-center gap-1">
                                <i data-lucide="phone" class="h-4 w-4"></i> {{ $customer->phone }}
                            </a>
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Alamat</span>
                    <span class="block mt-1 text-gray-850 dark:text-gray-200 leading-relaxed">{{ $customer->address ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Catatan</span>
                    <span class="block mt-1 text-gray-800 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-800 italic">
                        {{ $customer->notes ?? 'Tidak ada catatan khusus.' }}
                    </span>
                </div>
            </div>

            @if($activeTenant)
            <div class="mt-4 p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800/50 text-center">
                <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Penyewa Kost Aktif</p>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1">Kamar {{ $activeTenant->room->room_number }}</h4>
                <p class="text-xs text-gray-400 mt-0.5">Tarif: Rp {{ number_format($activeTenant->monthly_fee, 0, ',', '.') }}/bln</p>
                <a href="{{ route('tenants.show', $activeTenant) }}" class="mt-2.5 inline-flex items-center justify-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline w-full">
                    <i data-lucide="eye" class="h-3.5 w-3.5"></i> Lihat Detail Sewa
                </a>
            </div>
            @endif
        </div>

        <!-- History Transactions Area -->
        <div class="md:col-span-2 space-y-6" x-data="{ activeTab: 'laundry' }">
            @if($tenants->isNotEmpty())
            <!-- Tabs Header -->
            <div class="flex border-b border-gray-150 dark:border-gray-750 bg-white dark:bg-gray-800 rounded-t-2xl px-2">
                <button @click="activeTab = 'laundry'"
                        :class="activeTab === 'laundry' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                        class="py-4 px-6 text-center font-bold text-sm border-b-2 transition-all duration-200 flex items-center gap-2 focus:outline-none">
                    <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                    Riwayat Laundry
                </button>
                <button @click="activeTab = 'kost'"
                        :class="activeTab === 'kost' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                        class="py-4 px-6 text-center font-bold text-sm border-b-2 transition-all duration-200 flex items-center gap-2 focus:outline-none">
                    <i data-lucide="home" class="h-4 w-4"></i>
                    Riwayat Sewa & Tagihan Kost
                </button>
            </div>
            @endif

            <!-- Laundry Tab Content -->
            <div x-show="activeTab === 'laundry'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden" :class="{ 'rounded-t-none border-t-0': {{ $tenants->isNotEmpty() ? 'true' : 'false' }} }">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-850/50 flex justify-between items-center">
                    <h4 class="font-bold text-gray-900 dark:text-white">Riwayat Transaksi Laundry</h4>
                    <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 font-semibold px-2.5 py-0.5 rounded-full">
                        {{ $orders->total() }} Total Transaksi
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/20 dark:bg-gray-900/20">
                                <th class="px-6 py-4">No. Order</th>
                                <th class="px-6 py-4">Layanan</th>
                                <th class="px-6 py-4">Berat</th>
                                <th class="px-6 py-4">Total Harga</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-750 dark:text-gray-300">
                                        {{ $order->service->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-450">
                                        {{ $order->weight }} kg
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <!-- Laundry Status Badge -->
                                            <span>
                                                @if($order->status === 'baru')
                                                    <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 px-2 py-0.5 rounded-full font-medium">Baru</span>
                                                @elseif($order->status === 'proses')
                                                    <span class="text-xs bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 px-2 py-0.5 rounded-full font-medium">Diproses</span>
                                                @elseif($order->status === 'selesai')
                                                    <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 px-2 py-0.5 rounded-full font-medium">Selesai</span>
                                                @else
                                                    <span class="text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full font-medium">Diambil/Diantar</span>
                                                @endif
                                            </span>
                                            <!-- Payment Status Badge -->
                                            <span>
                                                @if($order->payment_status === 'lunas')
                                                    <span class="text-[10px] text-green-700 dark:text-green-400 font-bold">Lunas</span>
                                                @elseif($order->payment_status === 'dp')
                                                    <span class="text-[10px] text-amber-700 dark:text-amber-400 font-bold">DP</span>
                                                @else
                                                    <span class="text-[10px] text-red-700 dark:text-red-400 font-bold">Belum Bayar</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                        Customer belum melakukan transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

            @if($tenants->isNotEmpty())
            <!-- Kost Tab Content -->
            <div x-show="activeTab === 'kost'" class="space-y-6" style="display: none;">
                <!-- Rent Invoices / Bills -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-850/50 flex justify-between items-center">
                        <h4 class="font-bold text-gray-900 dark:text-white">Daftar Tagihan Kost</h4>
                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $tenantPayments->count() }} Tagihan
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/20 dark:bg-gray-900/20">
                                    <th class="px-6 py-4">Keterangan</th>
                                    <th class="px-6 py-4">Tipe Pembayaran</th>
                                    <th class="px-6 py-4">Jumlah Tagihan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                                @forelse($tenantPayments as $payment)
                                    <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            <div class="font-bold">{{ $payment->notes }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Kamar {{ $payment->tenant->room->room_number }}</div>
                                            <div class="text-[11px] text-gray-450 mt-1">Dibuat: {{ $payment->created_at->format('d M Y, H:i') }}</div>
                                            @if($payment->paid_at)
                                                <div class="text-[11px] text-green-600 dark:text-green-400">Lunas pada: {{ $payment->paid_at->format('d M Y, H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold uppercase">
                                            <span class="px-2 py-0.5 rounded-md {{ $payment->payment_type === 'dimuka' ? 'bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400' : 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' }}">
                                                {{ $payment->payment_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($payment->payment_status === 'lunas')
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                    Lunas
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 animate-pulse">
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($payment->payment_status === 'belum_bayar')
                                                <button onclick="openPayModal('{{ $payment->id }}', '{{ number_format($payment->amount, 0, ',', '.') }}')" 
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                                                    <i class="h-3.5 w-3.5" data-lucide="check-circle"></i> Lunasi
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-450 dark:text-gray-500 italic">Melalui {{ strtoupper($payment->payment_method ?? '-') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                            Belum ada tagihan sewa kost.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rent Contracts History -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-850/50 flex justify-between items-center">
                        <h4 class="font-bold text-gray-900 dark:text-white">Riwayat Kontrak Kost</h4>
                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $tenants->count() }} Kontrak
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/20 dark:bg-gray-900/20">
                                    <th class="px-6 py-4">Kamar</th>
                                    <th class="px-6 py-4">Periode Kontrak</th>
                                    <th class="px-6 py-4">Tarif Bulanan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                                @foreach($tenants as $t)
                                    <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            Kamar {{ $t->room->room_number }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-750 dark:text-gray-300 font-medium">
                                            {{ $t->start_date->format('d M Y') }} s/d {{ $t->end_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-950 dark:text-white">
                                            Rp {{ number_format($t->monthly_fee, 0, ',', '.') }}/bln
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($t->status === 'aktif')
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-gray-150 text-gray-655 dark:bg-gray-700 dark:text-gray-300 border border-gray-250 dark:border-gray-600">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('tenants.show', $t) }}" class="text-blue-600 hover:underline dark:text-blue-400 font-bold inline-flex items-center gap-1 text-xs">
                                                <i data-lucide="eye" class="h-3.5 w-3.5"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Pelunasan Tagihan Kost -->
<div id="payModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePayModal()"></div>

        <!-- Centering trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
            <form id="payForm" method="POST" action="">
                @csrf
                <div class="p-6">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">Konfirmasi Pelunasan Tagihan</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closePayModal()">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <div class="mt-4 space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Anda akan melunasi tagihan sewa kost sebesar <strong class="text-gray-900 dark:text-white" id="modalAmount"></strong>.</p>
                        
                        <div>
                            <label for="payment_method" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method" required class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                                <option value="cash">Cash / Tunai</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closePayModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                            Lunasi & Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPayModal(paymentId, amountStr) {
        document.getElementById('modalAmount').innerText = 'Rp ' + amountStr;
        document.getElementById('payForm').action = "/tenants/payments/" + paymentId + "/pay";
        document.getElementById('payModal').classList.remove('hidden');
        setTimeout(() => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }, 100);
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function closePayModal() {
        document.getElementById('payModal').classList.add('hidden');
    }
</script>
@endsection
