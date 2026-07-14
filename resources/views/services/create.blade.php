@extends('layouts.app')

@section('title', 'Tambah Layanan Laundry')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('services.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Layanan Laundry Baru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan nama layanan, tarif, dan estimasi waktu selesai.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('services.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Layanan <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="Contoh: Laundry 3 Hari, Cuci Karpet..."
                       class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tarif Layanan (Rupiah) <span class="text-red-500">*</span></label>
                <div class="relative mt-1.5 rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                        Rp
                    </div>
                    <input type="number" 
                           name="price" 
                           id="price" 
                           value="{{ old('price') }}" 
                           required 
                           min="0" 
                           placeholder="Contoh: 6000"
                           class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white @error('price') border-red-500 @enderror">
                </div>
                @error('price')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duration -->
            <div>
                <label for="duration_days" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Estimasi Waktu Selesai (Hari) <span class="text-red-500">*</span></label>
                <div class="relative mt-1.5 rounded-xl shadow-sm">
                    <input type="number" 
                           name="duration_days" 
                           id="duration_days" 
                           value="{{ old('duration_days', 1) }}" 
                           required 
                           min="0" 
                           placeholder="Contoh: 2"
                           class="block w-full pr-12 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white @error('duration_days') border-red-500 @enderror">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500 text-sm">
                        Hari
                    </div>
                </div>
                @error('duration_days')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active Toggle -->
            <div class="flex items-center gap-3">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active" 
                       value="1" 
                       checked
                       class="h-4.5 w-4.5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-950">
                <label for="is_active" class="text-sm font-semibold text-gray-750 dark:text-gray-300">Aktifkan Layanan Saat Ini</label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-150 dark:border-gray-750 flex items-center justify-end gap-3">
                <a href="{{ route('services.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 font-medium rounded-xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
