<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\LedgerEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = Carbon::today();
        $weekStart  = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        // Helper closure to sum ledger by type and date range
        $sum = fn($type, $refType, $from, $to) => LedgerEntry::where('type', $type)
            ->where('reference_type', $refType)
            ->whereBetween('entry_date', [$from, $to])
            ->sum('amount');

        // Today stats
        $todaySales    = $sum('credit', 'bill',    $today, $today);
        $todayReturns  = $sum('debit',  'return',  $today, $today);
        $todayExpenses = $sum('debit',  'expense', $today, $today);
        $todayProfit   = $todaySales - $todayReturns - $todayExpenses;

        // This week
        $weekSales    = $sum('credit', 'bill',    $weekStart, $today);
        $weekReturns  = $sum('debit',  'return',  $weekStart, $today);
        $weekExpenses = $sum('debit',  'expense', $weekStart, $today);
        $weekProfit   = $weekSales - $weekReturns - $weekExpenses;

        // This month
        $monthSales    = $sum('credit', 'bill',    $monthStart, $today);
        $monthReturns  = $sum('debit',  'return',  $monthStart, $today);
        $monthExpenses = $sum('debit',  'expense', $monthStart, $today);
        $monthProfit   = $monthSales - $monthReturns - $monthExpenses;

        // Last 7 days chart data
        $chartLabels  = [];
        $chartSales   = [];
        $chartExpenses = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $chartLabels[]   = $day->format('D, d M');
            $chartSales[]    = (float) $sum('credit', 'bill',    $day, $day);
            $chartExpenses[] = (float) $sum('debit',  'expense', $day, $day);
        }

        // Recent 10 bills
        $recentBills = Bill::latest()->take(10)->get();

        return view('dashboard', compact(
            'todaySales', 'todayReturns', 'todayExpenses', 'todayProfit',
            'weekSales', 'weekReturns', 'weekExpenses', 'weekProfit',
            'monthSales', 'monthReturns', 'monthExpenses', 'monthProfit',
            'chartLabels', 'chartSales', 'chartExpenses',
            'recentBills'
        ));
    }
}
