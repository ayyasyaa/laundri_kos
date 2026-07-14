<a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="layout-dashboard" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Dashboard
</a>

<!-- Laundry Section Heading -->
<div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
    Operasional Laundry
</div>

<a href="{{ route('customers.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('customers*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="users" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('customers*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Customer
</a>

<a href="{{ route('laundry.orders.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('laundry.orders*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="shopping-cart" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('laundry.orders*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Order Laundry
</a>

<a href="{{ route('deliveries.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('deliveries*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="truck" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('deliveries*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Antar Jemput (Delivery)
</a>

@if (auth()->user()->isAdmin() || auth()->user()->isOwner())
<a href="{{ route('services.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('services*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="package" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('services*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Layanan Master
</a>
@endif

@if (auth()->user()->isAdmin() || auth()->user()->isOwner())
<!-- Boarding House Heading -->
<div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
    Manajemen Kost
</div>

<a href="{{ route('rooms.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('rooms*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="home" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('rooms*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Kamar Kost
</a>

<a href="{{ route('tenants.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('tenants*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="user-check" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('tenants*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Penghuni Kost
</a>
@endif

@if (auth()->user()->isAdmin() || auth()->user()->isOwner())
<!-- Finance & Reports Heading -->
<div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
    Keuangan & Laporan
</div>

<a href="{{ route('finance.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('finance*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="wallet" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('finance*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Keuangan
</a>

<a href="{{ route('reports.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('reports*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="file-text" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('reports*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Laporan
</a>
@endif

@if (auth()->user()->isAdmin())
<!-- Settings Heading -->
<div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
    Sistem
</div>

<a href="{{ route('settings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('settings*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="settings" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('settings*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
    Pengaturan Usaha
</a>
@endif
