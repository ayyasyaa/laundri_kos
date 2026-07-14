@extends('layouts.app')

@section('title', 'Manajemen Customer')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Customer</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi pelanggan laundry Anda di sini.</p>
        </div>
        <div>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                <i data-lucide="user-plus" class="h-4 w-4"></i> Tambah Customer
            </a>
        </div>
    </div>

    <!-- Search and Actions Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="relative w-full sm:flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="h-5 w-5"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan nama atau nomor HP..." 
                       class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
            </div>
            <div class="flex w-full sm:w-auto gap-2">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-gray-950 hover:bg-gray-900 text-white font-medium rounded-xl text-sm transition-colors">
                    Cari
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('customers.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 font-medium rounded-xl text-sm transition-colors text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Customers Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Nomor HP</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $customer->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $customer->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                {{ $customer->address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $customer->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 rounded-lg transition-colors" title="Riwayat Transaksi">
                                        <i data-lucide="history" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="p-2 bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-lg transition-colors" title="Edit Customer">
                                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                                    </a>
                                    @if (!auth()->user()->isStaff())
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus customer ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 rounded-lg transition-colors" title="Hapus Customer">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                Belum ada data customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if ($customers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
