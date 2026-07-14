@extends('layouts.app')

@section('title', 'Jadwal Antar Jemput Laundry')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Jadwal Pengiriman & Penjemputan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau jadwal harian pickup laundry kotor dan delivery laundry bersih.</p>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('deliveries.index') }}" class="flex flex-wrap gap-4 items-center">
            <!-- Filter Type -->
            <div class="w-full sm:w-auto">
                <label for="type" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tipe Pengiriman</label>
                <select name="type" id="type" onchange="this.form.submit()" class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="pickup" {{ request('type') === 'pickup' ? 'selected' : '' }}>Pickup (Penjemputan)</option>
                    <option value="delivery" {{ request('type') === 'delivery' ? 'selected' : '' }}>Delivery (Pengantaran)</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div class="w-full sm:w-auto">
                <label for="status" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status</label>
                <select name="status" id="status" onchange="this.form.submit()" class="block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            @if(request()->filled('type') || request()->filled('status'))
                <div class="self-end pb-0.5">
                    <a href="{{ route('deliveries.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                        Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Deliveries List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">No. Order</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Tanggal & Jam</th>
                        <th class="px-6 py-4">Alamat Pengantaran</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    @forelse($deliveries as $deliv)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                @if($deliv->type === 'pickup')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-xl">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Pickup
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-xl">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Delivery
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('laundry.orders.show', $deliv->order) }}" class="font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $deliv->order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                <span class="font-semibold block">{{ $deliv->order->customer->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $deliv->order->customer->phone }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold block text-gray-800 dark:text-gray-200">{{ $deliv->delivery_date->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $deliv->delivery_time ? substr($deliv->delivery_time, 0, 5) : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-xs truncate" title="{{ $deliv->address }}">
                                {{ $deliv->address }}
                            </td>
                            <td class="px-6 py-4">
                                @if($deliv->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                        Selesai
                                    </span>
                                @elseif($deliv->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-1.5">
                                    @if($deliv->status !== 'completed')
                                        <!-- Actions -->
                                        @if($deliv->status === 'pending')
                                            <form action="{{ route('deliveries.updateStatus', $deliv) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-medium text-xs rounded-lg transition-colors">
                                                    Proses
                                                </button>
                                            </form>
                                        @elseif($deliv->status === 'processing')
                                            <form action="{{ route('deliveries.updateStatus', $deliv) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white font-medium text-xs rounded-lg transition-colors">
                                                    Selesai
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Selesai dikirim</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                Tidak ada jadwal antar jemput saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
