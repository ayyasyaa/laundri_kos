@extends('layouts.app')

@section('title', 'Daftar Order Laundry')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Order Laundry</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola dan pantau seluruh transaksi laundry kiloan dan satuan.</p>
        </div>
        <div>
            <a href="{{ route('laundry.orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                <i data-lucide="plus" class="h-4 w-4"></i> Buat Order Baru
            </a>
        </div>
    </div>

    <!-- Status Filter Buttons Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-8 gap-2">
        <a href="{{ route('laundry.orders.index') }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ !request('status') ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Semua Order
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'pending']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'pending' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Pending
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'sedang_diambil']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'sedang_diambil' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Sedang Diambil
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'baru']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'baru' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Baru
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'proses']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'proses' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Diproses
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'selesai']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'selesai' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Selesai
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'sedang_dikirim']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'sedang_dikirim' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50' }}">
            Sedang Dikirim
        </a>
        <a href="{{ route('laundry.orders.index', ['status' => 'diambil_diantar']) }}" class="px-2 py-2.5 text-center text-xs font-semibold rounded-xl border transition-all duration-200 {{ request('status') === 'diambil_diantar' ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-slate-650 hover:bg-gray-50' }}">
            Diambil / Diantar
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('laundry.orders.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative w-full sm:flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="h-5 w-5"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan nomor order atau nama customer..." 
                       class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
            </div>
            <div class="flex w-full sm:w-auto gap-2">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-gray-950 hover:bg-gray-900 text-white font-medium rounded-xl text-sm transition-colors">
                    Cari
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('laundry.orders.index', request()->only('status')) }}" class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 font-medium rounded-xl text-sm transition-colors text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No. Order</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Layanan</th>
                        <th class="px-6 py-4">Berat</th>
                        <th class="px-6 py-4">Total Tarif</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4">Status Order</th>
                        <th class="px-6 py-4">Estimasi Selesai</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->customer->name }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $order->customer->phone ?? 'No HP: -' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-650 dark:text-gray-300">
                                {{ $order->service->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $order->weight }} kg
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    @if($order->payment_status === 'lunas')
                                        <span class="text-xs text-green-700 dark:text-green-400 font-bold flex items-center gap-1">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Lunas
                                        </span>
                                    @elseif($order->payment_status === 'dp')
                                        <span class="text-xs text-amber-700 dark:text-amber-400 font-bold flex items-center gap-1">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> DP
                                        </span>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Sisa: Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-xs text-red-700 dark:text-red-400 font-bold flex items-center gap-1">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Belum Bayar
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-850 dark:bg-gray-700 dark:text-gray-300">
                                        Pending
                                    </span>
                                @elseif($order->status === 'baru')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-850 dark:bg-blue-900/40 dark:text-blue-300">
                                        Baru
                                    </span>
                                @elseif($order->status === 'sedang_diambil')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-850 dark:bg-yellow-900/40 dark:text-yellow-300">
                                        Sedang Diambil
                                    </span>
                                @elseif($order->status === 'proses')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-850 dark:bg-amber-900/40 dark:text-amber-300">
                                        Proses
                                    </span>
                                @elseif($order->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-850 dark:bg-green-900/40 dark:text-green-300">
                                        Selesai
                                    </span>
                                @elseif($order->status === 'sedang_dikirim')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-850 dark:bg-indigo-900/40 dark:text-indigo-300">
                                        Sedang Dikirim
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-850 dark:bg-gray-700 dark:text-gray-300">
                                        Selesai Diambil
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400">
                                {{ $order->estimation_date ? $order->estimation_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Quick Status Update Actions -->
                                    @if($order->status === 'pending')
                                        <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="sedang_diambil">
                                            <button type="submit" class="p-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 dark:text-yellow-400 rounded-lg transition-colors" title="Mulai Penjemputan (Pickup)">
                                                <i data-lucide="truck" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'baru')
                                        <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="proses">
                                            <button type="submit" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 dark:text-amber-400 rounded-lg transition-colors" title="Mulai Proses Cuci">
                                                <i data-lucide="play" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'sedang_diambil')
                                        <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="baru">
                                            <button type="submit" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 rounded-lg transition-colors" title="Selesai Dijemput (Tiba di Toko)">
                                                <i data-lucide="archive-restore" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'proses')
                                        <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="p-2 bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400 rounded-lg transition-colors" title="Selesaikan Order">
                                                <i data-lucide="check" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'selesai')
                                        @if(in_array($order->delivery_type, ['delivery', 'pickup_delivery']))
                                            <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="sedang_dikirim">
                                                <button type="submit" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 rounded-lg transition-colors" title="Mulai Pengantaran (Delivery)">
                                                    <i data-lucide="truck" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="diambil_diantar">
                                            <button type="submit" class="p-2 bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400 rounded-lg transition-colors" title="Tandai Sudah Diambil / Diantar">
                                                <i data-lucide="package-check" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'sedang_dikirim')
                                        <form action="{{ route('laundry.orders.updateStatus', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="diambil_diantar">
                                            <button type="submit" class="p-2 bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400 rounded-lg transition-colors" title="Selesai Diantar / Diterima">
                                                <i data-lucide="package-check" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('laundry.orders.show', $order) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 rounded-lg transition-colors" title="Lihat Detail & Kelola Status">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>
                                    @if(!auth()->user()->isStaff())
                                        <form action="{{ route('laundry.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus order ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 rounded-lg transition-colors" title="Hapus Order">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                Tidak ada data order laundry.
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
@endsection
