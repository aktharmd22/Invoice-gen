<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function ledger(Request $request)
    {
        $query = LedgerEntry::orderBy('entry_date')->orderBy('id');

        if ($request->filled('from')) {
            $query->whereDate('entry_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('entry_date', '<=', $request->to);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Get all for running balance calculation, then paginate
        $allEntries = LedgerEntry::orderBy('entry_date')->orderBy('id')->get();

        // Calculate cumulative balance up to the start of the filtered set
        $fromDate     = $request->filled('from') ? $request->from : null;
        $openingBalance = 0;

        if ($fromDate) {
            $openingBalance = LedgerEntry::whereDate('entry_date', '<', $fromDate)
                ->selectRaw("SUM(CASE WHEN type='credit' THEN amount ELSE -amount END) as bal")
                ->value('bal') ?? 0;
        }

        $entries      = $query->paginate(30)->withQueryString();

        // Build running balance for the displayed page
        // We need the balance at the start of current page
        $pageOffset   = ($entries->currentPage() - 1) * $entries->perPage();
        $priorEntries = LedgerEntry::orderBy('entry_date')->orderBy('id');

        if ($fromDate) {
            $priorEntries->whereDate('entry_date', '>=', $fromDate);
            if ($request->filled('to')) {
                $priorEntries->whereDate('entry_date', '<=', $request->to);
            }
            if ($request->filled('type')) {
                $priorEntries->where('type', $request->type);
            }
        } else {
            if ($request->filled('to')) {
                $priorEntries->whereDate('entry_date', '<=', $request->to);
            }
            if ($request->filled('type')) {
                $priorEntries->where('type', $request->type);
            }
        }

        $priorSum = 0;
        if ($pageOffset > 0) {
            $priorSubset = $priorEntries->take($pageOffset)->get();
            foreach ($priorSubset as $e) {
                $priorSum += $e->type === 'credit' ? $e->amount : -$e->amount;
            }
        }

        $runningBalance = $openingBalance + $priorSum;
        $balances       = [];
        foreach ($entries as $entry) {
            $runningBalance += $entry->type === 'credit' ? $entry->amount : -$entry->amount;
            $balances[$entry->id] = $runningBalance;
        }

        return view('accounts.ledger', compact('entries', 'balances'));
    }

    public function profitLoss(Request $request)
    {
        $tab = $request->get('tab', 'today');

        $today      = Carbon::today();
        $weekStart  = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $ranges = [
            'today'  => [$today,      $today],
            'week'   => [$weekStart,  $today],
            'month'  => [$monthStart, $today],
        ];

        $data = [];
        foreach ($ranges as $key => [$from, $to]) {
            $sales    = LedgerEntry::where('type', 'credit')->where('reference_type', 'bill')
                            ->whereBetween('entry_date', [$from, $to])->sum('amount');
            $returns  = LedgerEntry::where('type', 'debit')->where('reference_type', 'return')
                            ->whereBetween('entry_date', [$from, $to])->sum('amount');
            $expenses = LedgerEntry::where('type', 'debit')->where('reference_type', 'expense')
                            ->whereBetween('entry_date', [$from, $to])->sum('amount');
            $data[$key] = [
                'sales'     => $sales,
                'returns'   => $returns,
                'expenses'  => $expenses,
                'net'       => $sales - $returns - $expenses,
            ];
        }

        return view('accounts.profit-loss', compact('data', 'tab'));
    }
}
