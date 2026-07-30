@extends('layouts.app')

@section('title', 'Detail Order ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('laundry.orders.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Order {{ $order->order_number }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Diproses oleh: {{ $order->creator->name ?? 'System' }}</p>
        </div>
    </div>

    <!-- Status Tracking Bar -->
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">Progress Alur Laundry</h3>
            @php
                $steps = [];
                if (in_array($order->delivery_type, ['pickup', 'pickup_delivery'])) {
                    $steps[] = 'pending';
                    $steps[] = 'sedang_diambil';
                }
                $steps[] = 'baru';
                $steps[] = 'proses';
                $steps[] = 'selesai';
                if (in_array($order->delivery_type, ['delivery', 'pickup_delivery'])) {
                    $steps[] = 'sedang_dikirim';
                }
                $steps[] = 'diambil_diantar';
                
                $activeIdx = array_search($order->status, $steps);
            @endphp
        <div class="grid grid-cols-{{ count($steps) }} gap-2 text-center text-xs relative">
            @foreach($steps as $idx => $step)
                <div class="flex flex-col items-center">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs border-2 mb-2 transition-all duration-200 
                        {{ $idx <= $activeIdx 
                            ? 'bg-blue-600 border-blue-600 text-white shadow-sm shadow-blue-500/20' 
                            : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 text-gray-400 dark:text-gray-600' }}">
                        @if($step === 'pending')
                            <i data-lucide="clock" class="h-4 w-4"></i>
                        @elseif($step === 'baru')
                            <i data-lucide="inbox" class="h-4 w-4"></i>
                        @elseif($step === 'sedang_diambil')
                            <i data-lucide="truck" class="h-4 w-4"></i>
                        @elseif($step === 'proses')
                            <i data-lucide="cog" class="h-4 w-4 animate-spin"></i>
                        @elseif($step === 'selesai')
                            <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                        @elseif($step === 'sedang_dikirim')
                            <i data-lucide="truck" class="h-4 w-4"></i>
                        @else
                            <i data-lucide="package-check" class="h-4 w-4"></i>
                        @endif
                    </div>
                    <span class="font-semibold capitalize {{ $idx === $activeIdx ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-400 dark:text-gray-600' }}">
                        @if($step === 'diambil_diantar') Diantar/Diambil 
                        @elseif($step === 'pending') Pending
                        @elseif($step === 'sedang_diambil') Sedang Diambil 
                        @elseif($step === 'sedang_dikirim') Sedang Dikirim 
                        @else {{ $step }} @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Details and Forms Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Order Info & Actions -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Details -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Rincian Order</h3>
                    <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-400 text-xs">Layanan</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $order->service->name }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Berat Kiloan</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $order->weight }} kg</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Tipe Pengantaran</span>
                        <span class="font-bold text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' & ', $order->delivery_type) }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Estimasi Selesai</span>
                        <span class="font-bold text-gray-900 dark:text-white text-xs">{{ $order->estimation_date ? $order->estimation_date->format('d M Y') : '-' }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-150 dark:border-gray-750 text-sm">
                    <span class="block text-gray-400 text-xs mb-1">Catatan Keterangan Cucian:</span>
                    <p class="text-gray-800 dark:text-gray-300 italic bg-gray-50 dark:bg-gray-900/50 p-3.5 rounded-xl border border-gray-100 dark:border-gray-800">
                        {{ $order->notes ?: 'Tidak ada catatan.' }}
                    </p>
                </div>
            </div>

            <!-- Manage Workflow Forms -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Kelola Status & Pembayaran</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Update Status -->
                    <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <label for="status" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Perbarui Status Laundry</label>
                        <div class="flex gap-2">
                            <select name="status" id="status" class="block w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="baru" {{ $order->status === 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="sedang_diambil" {{ $order->status === 'sedang_diambil' ? 'selected' : '' }}>Sedang Diambil</option>
                                <option value="proses" {{ $order->status === 'proses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="sedang_dikirim" {{ $order->status === 'sedang_dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                                <option value="diambil_diantar" {{ $order->status === 'diambil_diantar' ? 'selected' : '' }}>Diambil / Diantar</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-650 hover:bg-blue-700 text-white font-medium text-xs rounded-xl shadow-sm transition-colors">
                                Update
                            </button>
                        </div>
                    </form>

                    <!-- Update Payment -->
                    @if($order->payment_status !== 'lunas')
                        <form method="POST" action="{{ route('laundry.orders.updatePayment', $order) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <label for="paid_amount" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Catat Pembayaran Baru</label>
                            <div class="space-y-2">
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 text-xs">
                                        Rp
                                    </div>
                                    <input type="number" 
                                           name="paid_amount" 
                                           id="paid_amount" 
                                           min="{{ $order->paid_amount }}" 
                                           max="{{ $order->total_price }}"
                                           value="{{ $order->total_price }}"
                                           required
                                           placeholder="Jumlah bayar..." 
                                           class="block w-full pl-8 pr-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-550 focus:border-blue-550 text-gray-900 dark:text-white">
                                </div>
                                <div class="flex gap-2">
                                    <select name="payment_method" required class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white">
                                        <option value="cash">Cash / Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="ewallet">E-Wallet / QRIS</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium text-xs rounded-xl shadow-sm transition-colors flex-shrink-0">
                                        Bayar
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="p-4 bg-green-50 dark:bg-green-900/10 rounded-xl border border-green-200 dark:border-green-800 text-center flex flex-col justify-center items-center">
                            <i data-lucide="check-circle" class="h-6 w-6 text-green-600 dark:text-green-400 mb-1"></i>
                            <span class="text-xs font-bold text-green-850 dark:text-green-300 uppercase tracking-wider">Pembayaran Lunas</span>
                            <span class="text-[10px] text-green-700 dark:text-green-400 mt-0.5">Lunas via {{ strtoupper($order->payment_method ?: 'cash') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Deliveries details -->
            @if($order->deliveries->count() > 0)
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Jadwal Pengiriman / Penjemputan</h3>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($order->deliveries as $deliv)
                            <div class="py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white capitalize">{{ $deliv->type }}</span>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold capitalize
                                            {{ $deliv->status === 'completed' 
                                                ? 'bg-green-100 text-green-850 dark:bg-green-900/40 dark:text-green-300' 
                                                : ($deliv->status === 'processing' 
                                                    ? 'bg-amber-100 text-amber-850 dark:bg-amber-900/40 dark:text-amber-300' 
                                                    : 'bg-blue-100 text-blue-850 dark:bg-blue-900/40 dark:text-blue-300') }}">
                                            {{ $deliv->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Alamat: {{ $deliv->address }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $deliv->delivery_date->format('d M Y') }}</p>
                                    <p class="mt-0.5">{{ $deliv->delivery_time ? substr($deliv->delivery_time, 0, 5) : '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Col 3: Customer Card & Cost Breakdown -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Customer</h3>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                    </div>
                    <div>
                        <a href="{{ route('customers.show', $order->customer) }}" class="font-bold text-gray-900 dark:text-white hover:underline">{{ $order->customer->name }}</a>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $order->customer->phone ?: 'No HP: -' }}</span>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 space-y-2">
                    <p class="leading-relaxed"><strong>Alamat Profil:</strong><br>{{ $order->customer->address ?: '-' }}</p>
                </div>
            </div>

            <!-- Cost breakdown -->
            <div class="bg-gradient-to-b from-slate-900 to-slate-800 text-white rounded-2xl border border-slate-700 p-6 shadow-lg space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Detail Invoice</h3>
                
                <div class="space-y-3.5 text-sm">
                    <!-- Service calculations -->
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tarif Cucian</span>
                        <span>Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-[11px] text-slate-500 -mt-2.5">
                        {{ $order->weight }} kg x Rp {{ number_format($order->service->price, 0, ',', '.') }}
                    </div>

                    <!-- Additional fees -->
                    @if($order->additional_fees > 0)
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Biaya Tambahan</span>
                            <span>+ Rp {{ number_format($order->additional_fees, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="h-px bg-slate-750"></div>

                    <!-- Grand total -->
                    <div class="flex justify-between items-baseline font-bold text-base">
                        <span class="text-slate-300">Total Biaya</span>
                        <span class="text-blue-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>

                    <!-- Paid amount -->
                    <div class="flex justify-between text-slate-400 text-xs">
                        <span>Sudah Dibayar</span>
                        <span>Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                    </div>

                    <!-- Sisa -->
                    <div class="flex justify-between text-slate-300 text-xs font-semibold">
                        <span>Sisa Tagihan</span>
                        <span class="{{ $order->total_price - $order->paid_amount > 0 ? 'text-red-400' : 'text-green-400' }}">
                            Rp {{ number_format(max(0, $order->total_price - $order->paid_amount), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
