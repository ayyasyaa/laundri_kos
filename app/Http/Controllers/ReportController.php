<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\LaundryOrder;
use App\Models\Customer;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        // 1. Laundry Report Summary
        $laundryOrdersCount = LaundryOrder::count();
        $laundryTotalWeight = LaundryOrder::sum('weight');
        $laundryTotalRevenue = FinanceTransaction::where('type', 'income')
            ->where('category', 'laundry')
            ->sum('amount');

        // 2. Boarding House Report Summary
        $activeTenantsCount = Tenant::where('status', 'aktif')->count();
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'terisi')->count();
        $kostTotalRevenue = FinanceTransaction::where('type', 'income')
            ->where('category', 'kost')
            ->sum('amount');

        // 3. Cash Flow Summary
        $totalIncome = FinanceTransaction::where('type', 'income')->sum('amount');
        $totalExpense = FinanceTransaction::where('type', 'expense')->sum('amount');
        $profit = $totalIncome - $totalExpense;

        return view('reports.index', compact(
            'laundryOrdersCount',
            'laundryTotalWeight',
            'laundryTotalRevenue',
            'activeTenantsCount',
            'totalRooms',
            'occupiedRooms',
            'kostTotalRevenue',
            'totalIncome',
            'totalExpense',
            'profit'
        ));
    }

    /**
     * Export reports to CSV.
     */
    public function export(string $type)
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
        ];

        $today = Carbon::today()->format('Ymd');

        if ($type === 'laundry') {
            $filename = "laundry_report_{$today}.csv";
            $headers['Content-Disposition'] = "attachment; filename={$filename}";

            $callback = function() {
                $file = fopen('php://output', 'w');
                // CSV Headers
                fputcsv($file, ['No. Order', 'Customer', 'Layanan', 'Berat (kg)', 'Total Harga', 'Bayar', 'Status Bayar', 'Status Order', 'Tanggal']);

                $orders = LaundryOrder::with(['customer', 'service'])->latest()->get();
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
            $filename = "kost_report_{$today}.csv";
            $headers['Content-Disposition'] = "attachment; filename={$filename}";

            $callback = function() {
                $file = fopen('php://output', 'w');
                // CSV Headers
                fputcsv($file, ['Nama Penghuni', 'No HP', 'No Kamar', 'Mulai Kontrak', 'Selesai Kontrak', 'Tarif Bulanan', 'Deposit', 'Status']);

                $tenants = Tenant::with('room')->latest()->get();
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
            $filename = "cashflow_report_{$today}.csv";
            $headers['Content-Disposition'] = "attachment; filename={$filename}";

            $callback = function() {
                $file = fopen('php://output', 'w');
                // CSV Headers
                fputcsv($file, ['Tanggal', 'Tipe', 'Kategori', 'Metode Bayar', 'Nominal', 'Keterangan']);

                $transactions = FinanceTransaction::orderBy('date', 'desc')->get();
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
