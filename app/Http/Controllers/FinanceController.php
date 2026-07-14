<?php

namespace App\Http\Controllers;

use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FinanceTransaction::query();

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->input('start_date'), $request->input('end_date')]);
        }

        $transactions = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Calculate KPI totals (unfiltered for overall P&L, or we can filter it based on date for deep analysis. Let's do overall P&L for dashboard cards, and date-filtered totals for search!)
        $totalIncome = FinanceTransaction::where('type', 'income')->sum('amount');
        $totalExpense = FinanceTransaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('finance.index', compact('transactions', 'totalIncome', 'totalExpense', 'netProfit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:income,expense',
            'category' => 'required|string', // laundry, kost, operasional, listrik, air, detergen, peralatan, lainnya
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cash,transfer,ewallet',
            'notes' => 'nullable|string',
        ]);

        FinanceTransaction::create($validated);

        return redirect()->route('finance.index')->with('success', 'Transaksi keuangan berhasil dicatat.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceTransaction $transaction)
    {
        if (auth()->user()->isStaff()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Staff tidak diperbolehkan menghapus data keuangan.');
        }

        // If transaction is linked to a laundry order or tenant, let's notify the user but allow it (since admin/owner has access)
        $transaction->delete();

        return redirect()->route('finance.index')->with('success', 'Transaksi keuangan berhasil dihapus.');
    }
}
