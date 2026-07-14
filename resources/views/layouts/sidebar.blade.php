<!-- Sidebar for Mobile Overlay -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true" style="display: none;">
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" 
         aria-hidden="true" 
         @click="sidebarOpen = false"></div>

    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 text-white pt-5 pb-4">
        
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button type="button" @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <i data-lucide="x" class="h-6 w-6 text-white"></i>
            </button>
        </div>

        <div class="flex-shrink-0 flex items-center px-4">
            <span class="text-xl font-bold tracking-wider text-blue-400">{{ \App\Models\Setting::get('business_name', 'Laundry & Kost') }}</span>
        </div>
        
        <div class="mt-5 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">
                @include('layouts.sidebar-links')
            </nav>
        </div>
    </div>
</div>

<!-- Static Sidebar for Desktop -->
<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex-1 flex flex-col min-h-0 bg-slate-900 text-white border-r border-slate-800">
            <div class="flex-shrink-0 flex items-center h-16 px-6 bg-slate-950">
                <span class="text-lg font-bold tracking-wider text-blue-400 flex items-center gap-2">
                    <i data-lucide="combine" class="h-6 w-6"></i>
                    {{ \App\Models\Setting::get('business_name', 'Laundry & Kost') }}
                </span>
            </div>
            
            <div class="flex-1 flex flex-col overflow-y-auto">
                <nav class="flex-1 px-4 py-6 space-y-1">
                    @include('layouts.sidebar-links')
                </nav>
            </div>

            <!-- User Info and Sign Out -->
            <div class="flex-shrink-0 flex border-t border-slate-800 p-4 bg-slate-950/50">
                <div class="flex items-center gap-3 w-full">
                    <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize truncate">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="Log Out">
                            <i data-lucide="log-out" class="h-5 w-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
