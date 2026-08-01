<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\LaundryOrder;
use App\Models\Customer;
use App\Models\FinanceTransaction;
use App\Models\TenantPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        // Parse month and year, default to current month and year
        $month = (int)$request->input('month', date('n'));
        $year = (int)$request->input('year', date('Y'));

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // 1. Laundry Report Summary for selected month
        $laundryOrdersCount = LaundryOrder::whereBetween('created_at', [$startDate, $endDate])->count();
        $laundryTotalWeight = LaundryOrder::whereBetween('created_at', [$startDate, $endDate])->sum('weight');
        $laundryTotalRevenue = FinanceTransaction::where('type', 'income')
            ->where('category', 'laundry')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // 2. Boarding House Report Summary (Occupancy is current status, but revenue is monthly)
        $activeTenantsCount = Tenant::where('status', 'aktif')->count();
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'terisi')->count();
        $kostTotalRevenue = FinanceTransaction::where('type', 'income')
            ->where('category', 'kost')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // 3. Financial Statements: P&L (Laba Rugi)
        $laundryRevenue = $laundryTotalRevenue;
        $kostRevenue = $kostTotalRevenue;
        $otherRevenue = FinanceTransaction::where('type', 'income')
            ->where('category', 'lainnya')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        
        $totalIncome = $laundryRevenue + $kostRevenue + $otherRevenue;

        // Expenses detailed (Optimized: 1 query using GROUP BY instead of 6 loop queries)
        $expenseCategories = ['listrik', 'air', 'detergen', 'peralatan', 'operasional', 'lainnya'];
        $expenseTotals = FinanceTransaction::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $expenses = [];
        foreach ($expenseCategories as $cat) {
            $expenses[$cat] = (float)($expenseTotals[$cat] ?? 0);
        }
        $totalExpense = array_sum($expenses);
        $profit = $totalIncome - $totalExpense;

        // 4. Financial Statements: Cash Flow (Arus Kas - Optimized: 2 grouped queries instead of 6 separate queries)
        $inflows = FinanceTransaction::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $cashInflow = (float)($inflows['cash'] ?? 0);
        $transferInflow = (float)($inflows['transfer'] ?? 0);
        $ewalletInflow = (float)($inflows['ewallet'] ?? 0);

        $outflows = FinanceTransaction::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $cashOutflow = (float)($outflows['cash'] ?? 0);
        $transferOutflow = (float)($outflows['transfer'] ?? 0);
        $ewalletOutflow = (float)($outflows['ewallet'] ?? 0);

        // 5. Receivables (Piutang)
        $unpaidLaundryOrders = LaundryOrder::with('customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['belum_bayar', 'dp'])
            ->get();
        $laundryReceivables = $unpaidLaundryOrders->sum(fn($o) => $o->total_price - $o->paid_amount);

        $unpaidTenantPayments = TenantPayment::with('tenant.room')
            ->where('payment_status', 'belum_bayar')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $kostReceivables = $unpaidTenantPayments->sum('amount');
        
        $totalReceivables = $laundryReceivables + $kostReceivables;

        // Helpers for UI
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Dynamic year list from first transaction date to current year + 1
        $firstTx = FinanceTransaction::orderBy('date', 'asc')->first();
        $startYear = $firstTx ? Carbon::parse($firstTx->date)->year : date('Y');
        $years = range($startYear, date('Y') + 1);

        return view('reports.index', compact(
            'month', 'year', 'months', 'years',
            'laundryOrdersCount',
            'laundryTotalWeight',
            'laundryTotalRevenue',
            'activeTenantsCount',
            'totalRooms',
            'occupiedRooms',
            'kostTotalRevenue',
            'laundryRevenue',
            'kostRevenue',
            'otherRevenue',
            'totalIncome',
            'expenses',
            'totalExpense',
            'profit',
            'cashInflow',
            'transferInflow',
            'ewalletInflow',
            'cashOutflow',
            'transferOutflow',
            'ewalletOutflow',
            'laundryReceivables',
            'kostReceivables',
            'totalReceivables',
            'unpaidLaundryOrders',
            'unpaidTenantPayments'
        ));
    }

    /**
     * Export reports to CSV.
     */
    public function export(Request $request, string $type)
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
        ];

        $today = Carbon::today()->format('Ymd');
        
        $hasFilter = $request->filled('month') && $request->filled('year');
        $month = (int)$request->input('month');
        $year = (int)$request->input('year');
        
        if ($hasFilter) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $dateSuffix = "_{$year}_" . str_pad($month, 2, '0', STR_PAD_LEFT);
        } else {
            $dateSuffix = "_{$today}";
        }

        if ($type === 'laundry') {
            $filename = "laundry_report{$dateSuffix}.csv";
            $headers['Content-Disposition'] = "attachment; filename={$filename}";

            $callback = function() use ($hasFilter, $startDate, $endDate) {
                $file = fopen('php://output', 'w');
                // CSV Headers
                fputcsv($file, ['No. Order', 'Customer', 'Layanan', 'Berat (kg)', 'Total Harga', 'Bayar', 'Status Bayar', 'Status Order', 'Tanggal']);

                $query = LaundryOrder::with(['customer', 'service']);
                if ($hasFilter) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $orders = $query->latest()->get();
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->order_number,
                        $order->customer->name,
                        $order->service->name,
                        $order->weight,
                        $order->total_price,
                        $order->paid_amount,
                        strtoupper($order->payment_status),
                        strtoupper($order->status),
                        $order->created_at->format('Y-m-d H:i')
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        if ($type === 'kost') {
            $filename = "kost_report{$dateSuffix}.csv";
            $headers['Content-Disposition'] = "attachment; filename={$filename}";

            $callback = function() use ($hasFilter, $startDate, $endDate) {
                $file = fopen('php://output', 'w');
                // CSV Headers
                fputcsv($file, ['Nama Penghuni', 'No HP', 'No Kamar', 'Mulai Kontrak', 'Selesai Kontrak', 'Tarif Bulanan', 'Deposit', 'Status']);

                $query = Tenant::with('room');
                if ($hasFilter) {
                    $query->where(function($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function($sq) use ($startDate, $endDate) {
                              $sq->where('start_date', '<=', $startDate)
                                 ->where('end_date', '>=', $endDate);
                          });
                    });
                }
                $tenants = $query->latest()->get();
                foreach ($tenants as $tenant) {
                    fputcsv($file, [
                        $tenant->name,
                        $tenant->phone,
                        $tenant->room->room_number,
                        $tenant->start_date->format('Y-m-d'),
                        $tenant->end_date->format('Y-m-d'),
                        $tenant->monthly_fee,
                        $tenant->deposit,
                        strtoupper($tenant->status)
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        if ($type === 'finance') {
            $filename = "cashflow_report{$dateSuffix}.csv";
            $headers['Content-Disposition'] = "attachment; filename={$filename}";

            $callback = function() use ($hasFilter, $startDate, $endDate) {
                $file = fopen('php://output', 'w');
                // CSV Headers
                fputcsv($file, ['Tanggal', 'Tipe', 'Kategori', 'Metode Bayar', 'Nominal', 'Keterangan']);

                $query = FinanceTransaction::query();
                if ($hasFilter) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
                $transactions = $query->orderBy('date', 'desc')->get();
                foreach ($transactions as $tx) {
                    fputcsv($file, [
                        $tx->date->format('Y-m-d'),
                        strtoupper($tx->type),
                        strtoupper($tx->category),
                        strtoupper($tx->payment_method),
                        $tx->amount,
                        $tx->notes
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        return redirect()->route('reports.index')->with('error', 'Kategori ekspor tidak valid.');
    }
}
