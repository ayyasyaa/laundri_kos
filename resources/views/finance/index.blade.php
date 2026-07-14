@extends('layouts.app')

@section('title', 'Manajemen Keuangan')

@section('content')
<div class="space-y-6">
    <!-- P&L Summary Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Inflow -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm flex items-center gap-4">
            <div class="p-3.5 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl">
                <i data-lucide="arrow-down-left" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Total Outflow -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm flex items-center gap-4">
            <div class="p-3.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                <i data-lucide="arrow-up-right" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pengeluaran</p>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white border border-slate-700 rounded-2xl p-6 shadow-md flex items-center gap-4">
            <div class="p-3.5 bg-blue-500/20 text-blue-400 rounded-xl">
                <i data-lucide="coins" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Laba Bersih (Profit)</p>
                <h3 class="text-xl font-bold text-blue-400 mt-1">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Inner Grid: Split Ledger and Entry Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Ledger Log and Filters (Col-span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Filters Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
                <form method="GET" action="{{ route('finance.index') }}" class="grid grid-cols-2 sm:grid-cols-4 gap-4 items-end">
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tipe</label>
                        <select name="type" id="type" class="block w-full px-2.5 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white">
                            <option value="">Semua Tipe</option>
                            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kategori</label>
                        <select name="category" id="category" class="block w-full px-2.5 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white">
                            <option value="">Semua Kategori</option>
                            <option value="laundry" {{ request('category') === 'laundry' ? 'selected' : '' }}>Laundry</option>
                            <option value="kost" {{ request('category') === 'kost' ? 'selected' : '' }}>Kost</option>
                            <option value="listrik" {{ request('category') === 'listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="air" {{ request('category') === 'air' ? 'selected' : '' }}>Air</option>
                            <option value="detergen" {{ request('category') === 'detergen' ? 'selected' : '' }}>Detergen</option>
                            <option value="peralatan" {{ request('category') === 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="operasional" {{ request('category') === 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white">
                    </div>

                    <!-- Actions -->
                    <div class="col-span-2 sm:col-span-4 flex justify-end gap-2 mt-2">
                        <button type="submit" class="px-4 py-2 bg-gray-950 hover:bg-gray-900 text-white font-semibold text-xs rounded-xl transition-colors">
                            Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['type', 'category', 'start_date', 'end_date']))
                            <a href="{{ route('finance.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-semibold rounded-xl transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Ledger Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4">Metode</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-450 text-xs font-medium">
                                        {{ $tx->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold capitalize
                                            {{ in_array($tx->category, ['laundry', 'kost']) 
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' 
                                                : 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300' }}">
                                            {{ $tx->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white max-w-xs truncate" title="{{ $tx->notes }}">
                                        {{ $tx->notes ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 capitalize text-xs">
                                        {{ $tx->payment_method }}
                                    </td>
                                    <td class="px-6 py-4 font-bold">
                                        @if($tx->type === 'income')
                                            <span class="text-green-600 dark:text-green-400">+ Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400">- Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(!auth()->user()->isStaff())
                                            <form action="{{ route('finance.destroy', $tx) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan transaksi ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-650 dark:hover:text-red-400 rounded-lg transition-colors" title="Hapus Transaksi">
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                        Belum ada catatan transaksi keuangan.
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

        <!-- Right: Add Transaction Form -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-6 sticky top-6">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Input Transaksi Manual</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Catat pemasukan kost tambahan atau pengeluaran operasional.</p>
                </div>

                <form method="POST" action="{{ route('finance.store') }}" class="space-y-5" x-data="{ type: 'expense' }">
                    @csrf

                    <!-- Type Selection (Income vs Expense) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider mb-2">Tipe Transaksi</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center justify-center gap-2 py-2 px-3 border rounded-xl cursor-pointer transition-all duration-200" 
                                   :class="type === 'expense' ? 'bg-red-50 border-red-250 dark:bg-red-900/10 dark:border-red-800 text-red-700 dark:text-red-400 font-bold' : 'border-gray-200 dark:border-gray-700 text-gray-500'">
                                <input type="radio" name="type" value="expense" x-model="type" class="sr-only">
                                <i data-lucide="trending-down" class="h-4 w-4"></i> Pengeluaran
                            </label>
                            <label class="flex items-center justify-center gap-2 py-2 px-3 border rounded-xl cursor-pointer transition-all duration-200" 
                                   :class="type === 'income' ? 'bg-green-50 border-green-250 dark:bg-green-900/10 dark:border-green-800 text-green-700 dark:text-green-400 font-bold' : 'border-gray-200 dark:border-gray-700 text-gray-500'">
                                <input type="radio" name="type" value="income" x-model="type" class="sr-only">
                                <i data-lucide="trending-up" class="h-4 w-4"></i> Pemasukan
                            </label>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_form" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Kategori</label>
                        <select name="category" id="category_form" required class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                            <!-- Expense Categories -->
                            <template x-if="type === 'expense'">
                                <optgroup label="Pengeluaran Operasional">
                                    <option value="listrik">Listrik</option>
                                    <option value="air">Air (PDAM)</option>
                                    <option value="detergen">Detergen & Pewangi</option>
                                    <option value="peralatan">Peralatan Laundry/Kost</option>
                                    <option value="operasional">Operasional Lain</option>
                                    <option value="lainnya">Lain-lain</option>
                                </optgroup>
                            </template>
                            
                            <!-- Income Categories -->
                            <template x-if="type === 'income'">
                                <optgroup label="Pemasukan Lain">
                                    <option value="laundry">Laundry</option>
                                    <option value="kost">Kost</option>
                                    <option value="lainnya">Pemasukan Lainnya</option>
                                </optgroup>
                            </template>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Nominal Transaksi (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative mt-1.5 rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                                Rp
                            </div>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   required 
                                   min="1" 
                                   placeholder="Contoh: 150000"
                                   class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Date & Payment Method -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="date" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Tanggal</label>
                            <input type="date" 
                                   name="date" 
                                   id="date" 
                                   value="{{ date('Y-m-d') }}"
                                   required 
                                   class="mt-1.5 block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="payment_method_form" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Metode</label>
                            <select name="payment_method" 
                                    id="payment_method_form" 
                                    required 
                                    class="mt-1.5 block w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl text-gray-900 dark:text-white">
                                <option value="cash">Cash / Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="ewallet">E-Wallet / QR</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes_form" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Keterangan / Notes</label>
                        <textarea name="notes" 
                                  id="notes_form" 
                                  rows="2" 
                                  placeholder="Contoh: Bayar tagihan air bulan juni..."
                                  class="mt-1.5 block w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white"></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 border-t border-gray-150 dark:border-gray-750">
                        <button type="submit" 
                                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
