@extends('layouts.app')

@section('title', 'Manajemen Kamar Kost')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Rooms Grid (Col-Span 2) -->
    <div class="lg:col-span-2 space-y-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Kamar Kost</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total kamar kost tersedia. Klik tombol edit pada kartu untuk mengubah data.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-200
                    {{ $room->status === 'kosong' ? 'border-green-100 dark:border-green-900/30' : ($room->status === 'terisi' ? 'border-blue-100 dark:border-blue-900/30' : 'border-amber-100 dark:border-amber-900/30') }}">
                    
                    <div>
                        <!-- Card Header -->
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="text-xs uppercase font-bold text-gray-400">No. Kamar</span>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Kamar {{ $room->room_number }}</h3>
                            </div>
                            <span>
                                @if($room->status === 'kosong')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                        Kosong
                                    </span>
                                @elseif($room->status === 'terisi')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        Terisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-850 dark:bg-amber-900/40 dark:text-amber-300">
                                        Perbaikan
                                    </span>
                                @endif
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="mt-6 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                <span>Harga Sewa:</span>
                                <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($room->price, 0, ',', '.') }} / bln</span>
                            </div>
                            
                            <!-- Tenant info if occupied -->
                            @if($room->status === 'terisi' && $room->activeTenant)
                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <span class="block text-xs uppercase font-semibold text-gray-400">Penyewa Aktif</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 mt-1 block">{{ $room->activeTenant->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-405 block">Hingga: {{ $room->activeTenant->end_date->format('d M Y') }}</span>
                                </div>
                            @elseif($room->status === 'kosong')
                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('tenants.create', ['room_id' => $room->id]) }}" class="inline-flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                        <i data-lucide="user-check" class="h-3.5 w-3.5"></i> Check-in Penghuni
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2">
                        <!-- Edit inline triggers form fill on the right -->
                        <button type="button" 
                                @click="$dispatch('edit-room', { id: {{ $room->id }}, room_number: '{{ $room->room_number }}', price: {{ (int)$room->price }}, status: '{{ $room->status }}' })" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-semibold rounded-lg transition-colors">
                            <i data-lucide="edit-3" class="h-3.5 w-3.5"></i> Edit
                        </button>

                        @if(!auth()->user()->isStaff() && $room->status !== 'terisi')
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-650 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 text-xs font-semibold rounded-lg transition-colors">
                                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-10 text-center text-gray-500 dark:text-gray-400 italic">
                    Belum ada data kamar kost.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Room Manager Form (Col-Span 1) -->
    <div class="space-y-6" 
         x-data="{
            isEdit: false,
            roomId: '',
            roomNumber: '',
            price: '',
            status: 'kosong',
            formAction: '{{ route('rooms.store') }}'
         }"
         @edit-room.window="
            isEdit = true;
            roomId = $event.detail.id;
            roomNumber = $event.detail.room_number;
            price = parseInt($event.detail.price);
            status = $event.detail.status;
            formAction = '{{ url('rooms') }}/' + roomId;
         ">
         
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-6 sticky top-6">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white" x-text="isEdit ? 'Ubah Data Kamar' : 'Tambah Kamar Baru'">Tambah Kamar Baru</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan data detail kamar kost.</p>
            </div>

            <form method="POST" :action="formAction" class="space-y-5">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <!-- Room Number -->
                <div>
                    <label for="room_number" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Nomor Kamar <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="room_number" 
                           id="room_number" 
                           required 
                           x-model="roomNumber"
                           placeholder="Contoh: 101, A-02"
                           class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Harga Bulanan (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative mt-1.5 rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                            Rp
                        </div>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               required 
                               min="0" 
                               x-model="price"
                               placeholder="Contoh: 1500000"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold text-gray-650 dark:text-gray-400 uppercase tracking-wider">Status Kamar</label>
                    <select name="status" 
                            id="status" 
                            x-model="status"
                            class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        <option value="kosong">Kosong (Tersedia)</option>
                        <option value="terisi">Terisi (Penyewa Aktif)</option>
                        <option value="maintenance">Maintenance (Perbaikan)</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-gray-150 dark:border-gray-750 flex items-center justify-end gap-2">
                    <template x-if="isEdit">
                        <button type="button" 
                                @click="isEdit = false; roomId = ''; roomNumber = ''; price = ''; status = 'kosong'; formAction = '{{ route('rooms.store') }}'"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                    </template>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors"
                            x-text="isEdit ? 'Update Kamar' : 'Simpan Kamar'">
                        Simpan Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
