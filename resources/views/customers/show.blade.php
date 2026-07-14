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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Unpaid Bills Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                <i data-lucide="alert-circle" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tagihan Belum Lunas</p>
                <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mt-1">Rp {{ number_format($unpaidTotal, 0, ',', '.') }}</h3>
            </div>
        </div>

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
        </div>

        <!-- History Transactions Area -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-850/50 flex justify-between items-center">
                    <h4 class="font-bold text-gray-900 dark:text-white">Riwayat Transaksi</h4>
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
        </div>
    </div>
</div>
@endsection
