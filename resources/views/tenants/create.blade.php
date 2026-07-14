@extends('layouts.app')

@section('title', 'Check-in Penghuni Kost Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('tenants.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors text-gray-500 dark:text-gray-400">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Check-in Penghuni Kost</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Registrasikan data penyewa kamar baru beserta sewa awal.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('tenants.store') }}" class="space-y-6" x-data="tenantCheckinForm()">
            @csrf

            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100 dark:border-gray-700">Biodata Penghuni</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Contoh: Andi Wijaya"
                           class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nomor HP (WhatsApp) <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone') }}" 
                           required 
                           placeholder="Contoh: 085678901234"
                           class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                </div>
            </div>

            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pt-4 pb-2 border-b border-gray-100 dark:border-gray-700">Informasi Kamar & Biaya</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Room Selection -->
                <div>
                    <label for="room_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Kamar Kost <span class="text-red-500">*</span></label>
                    <select name="room_id" 
                            id="room_id" 
                            required 
                            x-model="selectedRoomId"
                            class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                        <option value="">-- Pilih Kamar Kosong --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ (request('room_id') == $room->id || old('room_id') == $room->id) ? 'selected' : '' }}>
                                Kamar {{ $room->room_number }} (Rp {{ number_format($room->price, 0, ',', '.') }}/bln)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Monthly Rent -->
                <div>
                    <label for="monthly_fee" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Biaya Sewa Bulanan (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative mt-1.5 rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                            Rp
                        </div>
                        <input type="number" 
                               name="monthly_fee" 
                               id="monthly_fee" 
                               required 
                               x-model.number="monthlyFee"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Masuk (Check-in) <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="start_date" 
                           id="start_date" 
                           value="{{ old('start_date', date('Y-m-d')) }}" 
                           required 
                           class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Selesai Kontrak <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="end_date" 
                           id="end_date" 
                           value="{{ old('end_date') }}" 
                           required 
                           class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Deposit -->
                <div>
                    <label for="deposit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Uang Deposit Awal (Rp)</label>
                    <div class="relative mt-1.5 rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 text-sm">
                            Rp
                        </div>
                        <input type="number" 
                               name="deposit" 
                               id="deposit" 
                               value="{{ old('deposit', 200000) }}" 
                               min="0"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-550 text-gray-900 dark:text-white">
                    </div>
                    <span class="block text-xs text-gray-500 mt-1">Uang jaminan, dicatat sebagai pendapatan & kas kost sementara.</span>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Catatan Sewa</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="2" 
                              placeholder="Keterangan tambahan sewa kamar..."
                              class="mt-1.5 block w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-550 focus:border-blue-550 text-gray-900 dark:text-white">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-150 dark:border-gray-750 flex items-center justify-end gap-3">
                <a href="{{ route('tenants.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 font-medium rounded-xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                    Check-in Penghuni
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tenantCheckinForm', () => ({
            rooms: @json($rooms),
            selectedRoomId: '{{ request('room_id', '') }}',
            monthlyFee: 0,
            
            init() {
                this.$watch('selectedRoomId', value => {
                    const room = this.rooms.find(r => r.id == value);
                    if (room) {
                        this.monthlyFee = parseFloat(room.price);
                    } else {
                        this.monthlyFee = 0;
                    }
                });
                
                // Trigger initial value
                if (this.selectedRoomId) {
                    const room = this.rooms.find(r => r.id == this.selectedRoomId);
                    if (room) this.monthlyFee = parseFloat(room.price);
                }
            }
        }));
    });
</script>
@endsection
