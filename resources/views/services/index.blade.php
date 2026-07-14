@extends('layouts.app')

@section('title', 'Layanan Master Laundry')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Master Layanan Laundry</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Atur jenis layanan, tarif per kg, dan durasi pengerjaan laundry.</p>
        </div>
        <div>
            <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                <i data-lucide="plus" class="h-4 w-4"></i> Tambah Layanan
            </a>
        </div>
    </div>

    <!-- Services Grid / Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border {{ $service->is_active ? 'border-gray-100 dark:border-gray-700' : 'border-gray-200 dark:border-gray-850 opacity-60' }} p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-200">
                <div>
                    <div class="flex justify-between items-start gap-4">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $service->name }}</h3>
                        <span>
                            @if($service->is_active)
                                <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 px-2 py-0.5 rounded-full font-medium">Aktif</span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full font-medium">Nonaktif</span>
                            @endif
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Tarif Layanan:</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($service->price, 0, ',', '.') }} @if($service->name !== 'Cuci Sepatu' && strpos(strtolower($service->name), 'sepatu') === false) / kg @endif</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Estimasi Waktu:</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $service->duration_days }} Hari</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2">
                    <a href="{{ route('services.edit', $service) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-semibold rounded-lg transition-colors">
                        <i data-lucide="edit-3" class="h-3.5 w-3.5"></i> Edit
                    </a>
                    
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        @if($service->is_active)
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 text-xs font-semibold rounded-lg transition-colors">
                                <i data-lucide="power" class="h-3.5 w-3.5"></i> Nonaktifkan
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400 text-xs font-semibold rounded-lg transition-colors">
                                <i data-lucide="power" class="h-3.5 w-3.5"></i> Aktifkan
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-10 text-center text-gray-500 dark:text-gray-400 italic">
                Belum ada data layanan laundry. Silakan buat layanan baru.
            </div>
        @endforelse
    </div>
</div>
@endsection
