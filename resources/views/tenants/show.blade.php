@extends('layouts.app')

@section('title', 'Detail Penghuni & Riwayat Kas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('tenants.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Penghuni</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Informasi lengkap kontrak sewa dan riwayat transaksi kas kost.</p>
        </div>
    </div>

    <!-- Tenant Quick Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Total Inflows Paid Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl">
                <i data-lucide="wallet" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Kas Masuk</p>
                <h3 class="text-lg font-bold text-green-650 dark:text-green-400 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Room Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                <i data-lucide="home" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kamar & Tarif</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">Kamar {{ $tenant->room->room_number }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">Rp {{ number_format($tenant->monthly_fee, 0, ',', '.') }}/bulan</p>
            </div>
        </div>

        <!-- Remaining Contract Days Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                <i data-lucide="clock" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Masa Sewa</p>
                @php $days = $tenant->days_remaining; @endphp
                @if($tenant->status !== 'aktif')
                    <h3 class="text-lg font-bold text-gray-400 mt-1">Selesai (Out)</h3>
                @elseif($days < 0)
                    <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mt-1">Lewat {{ abs($days) }} Hari</h3>
                @elseif($days == 0)
                    <h3 class="text-lg font-bold text-yellow-600 mt-1">Hari Ini</h3>
                @else
                    <h3 class="text-lg font-bold text-purple-650 dark:text-purple-400 mt-1">{{ $days }} Hari Lagi</h3>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tenant Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm self-start space-y-6">
            <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-gray-700">
                <div class="h-16 w-16 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center font-bold text-2xl mb-4">
                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $tenant->name }}</h3>
                <div class="mt-1">
                    @if($tenant->status === 'aktif')
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Kontrak Aktif</span>
                    @else
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-gray-150 text-gray-650 dark:bg-gray-700 dark:text-gray-300 border border-gray-250 dark:border-gray-600">Selesai / Check Out</span>
                    @endif
                </div>
            </div>
            
            <div class="space-y-4 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Nomor HP / WhatsApp</span>
                    <span class="block mt-1 font-medium">
                        <a href="https://wa.me/{{ $tenant->phone }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline flex items-center gap-1.5 font-bold">
                            <i data-lucide="message-square" class="h-4 w-4"></i> {{ $tenant->phone }}
                        </a>
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Masuk (Check-in)</span>
                    <span class="block mt-1 text-gray-850 dark:text-gray-200 font-medium">{{ $tenant->start_date->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Batas Masa Sewa</span>
                    <span class="block mt-1 text-gray-850 dark:text-gray-200 font-medium">{{ $tenant->end_date->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Uang Jaminan (Deposit)</span>
                    <span class="block mt-1 text-gray-850 dark:text-gray-200 font-bold">Rp {{ number_format($tenant->deposit, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Catatan Kost</span>
                    <span class="block mt-1 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800 italic">
                        {{ $tenant->notes ?? 'Tidak ada catatan khusus.' }}
                    </span>
                </div>
            </div>

            @if($tenant->status === 'aktif' && !auth()->user()->isStaff())
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('tenants.renew.form', $tenant) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i> Perpanjang Kontrak Kost
                    </a>
                </div>
            @endif
        </div>

        <!-- History Transactions Area (Right) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-850/50 flex justify-between items-center">
                    <h4 class="font-bold text-gray-900 dark:text-white">Riwayat Pembayaran Kas Kost</h4>
                    <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 font-semibold px-2.5 py-0.5 rounded-full">
                        {{ $transactions->total() }} Transaksi
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/20 dark:bg-gray-900/20">
                                <th class="px-6 py-4">Tanggal Transaksi</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Metode Bayar</th>
                                <th class="px-6 py-4">Jumlah Pemasukan</th>
                                <th class="px-6 py-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-950 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
                                            {{ ucfirst($tx->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-350">
                                            {{ strtoupper($tx->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $tx->notes }}">
                                        {{ $tx->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                        Belum ada riwayat transaksi pembayaran kost untuk penghuni ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
