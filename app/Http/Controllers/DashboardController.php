<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\LaundryOrder;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // 1. KPI Cards
        $totalLaundryToday = LaundryOrder::whereDate('created_at', $today)->count();
        $laundryProcesses = LaundryOrder::where('status', 'proses')->count();
        $laundryCompleted = LaundryOrder::where('status', 'selesai')->count();
        
        // Revenue calculations
        $revenueLaundry = FinanceTransaction::where('type', 'income')
            ->where('category', 'laundry')
            ->sum('amount');
            
        $revenueKost = FinanceTransaction::where('type', 'income')
            ->where('category', 'kost')
            ->sum('amount');
            
        $totalRevenue = FinanceTransaction::where('type', 'income')->sum('amount');
        
        $roomsOccupied = Room::where('status', 'terisi')->count();
        $roomsEmpty = Room::where('status', 'kosong')->count();

        // 2. Today's Tasks & Alerts
        // Laundry: Siap Diantar (status: selesai, delivery_type: delivery or pickup_delivery, not delivered yet)
        $readyToDeliver = LaundryOrder::with('customer')
            ->where('status', 'selesai')
            ->whereIn('delivery_type', ['delivery', 'pickup_delivery'])
            ->get();
            
        // Laundry: Belum Diambil (status: selesai, delivery_type: none or pickup)
        $unclaimedLaundry = LaundryOrder::with('customer')
            ->where('status', 'selesai')
            ->whereIn('delivery_type', ['none', 'pickup'])
            ->get();

        // Kost: Tagihan Jatuh Tempo (days remaining <= 7 and status aktif)
        $dueTenants = Tenant::with('room')
            ->where('status', 'aktif')
            ->get()
            ->filter(fn($t) => $t->days_remaining <= 7);

        // Laundry: Pembayaran Belum Lunas
        $unpaidLaundry = LaundryOrder::with('customer')
            ->whereIn('payment_status', ['belum_bayar', 'dp'])
            ->get();

        // 3. Charts Data (Daily, Monthly, Weekly Orders)
        // Daily Income in current month (last 15 days)
        $dailyIncomeData = FinanceTransaction::where('type', 'income')
            ->where('date', '>=', Carbon::now()->subDays(14))
            ->select('date', DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyIncomeLabels = $dailyIncomeData->map(fn($d) => Carbon::parse($d->date)->format('d M'))->toArray();
        $dailyIncomeValues = $dailyIncomeData->map(fn($d) => (float)$d->total)->toArray();

        // Monthly Income in current year
        $monthlyIncomeData = FinanceTransaction::where('type', 'income')
            ->whereYear('date', Carbon::now()->year)
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyIncomeLabels = [];
        $monthlyIncomeValues = array_fill(0, 12, 0);

        foreach ($monthlyIncomeData as $d) {
            $monthlyIncomeValues[$d->month - 1] = (float)$d->total;
        }
        $monthlyIncomeLabels = $months;

        // Weekly Laundry Orders
        $weeklyOrdersData = LaundryOrder::where('created_at', '>=', Carbon::now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $weeklyOrdersLabels = [];
        $weeklyOrdersValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelStr = Carbon::now()->subDays($i)->format('D');
            $weeklyOrdersLabels[] = $labelStr;
            
            $found = $weeklyOrdersData->firstWhere('date', $dateStr);
            $weeklyOrdersValues[] = $found ? $found->total : 0;
        }

        $newOrders = collect();
        $processingOrders = collect();
        $completedOrders = collect();

        if (auth()->user()->isStaff()) {
            $newOrders = LaundryOrder::with(['customer', 'service'])
                ->where('status', 'baru')
                ->latest()
                ->get();
            $processingOrders = LaundryOrder::with(['customer', 'service'])
                ->where('status', 'proses')
                ->latest()
                ->get();
            $completedOrders = LaundryOrder::with(['customer', 'service'])
                ->where('status', 'selesai')
                ->latest()
                ->get();
        }

        return view('dashboard', compact(
            'totalLaundryToday',
            'laundryProcesses',
            'laundryCompleted',
            'revenueLaundry',
            'revenueKost',
            'totalRevenue',
            'roomsOccupied',
            'roomsEmpty',
            'readyToDeliver',
            'unclaimedLaundry',
            'dueTenants',
            'unpaidLaundry',
            'dailyIncomeLabels',
            'dailyIncomeValues',
            'monthlyIncomeLabels',
            'monthlyIncomeValues',
            'weeklyOrdersLabels',
            'weeklyOrdersValues',
            'newOrders',
            'processingOrders',
            'completedOrders'
        ));
    }
}
