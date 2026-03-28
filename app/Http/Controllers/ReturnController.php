<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\LedgerEntry;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnModel::with(['bill', 'items'])->latest()->paginate(20);
        return view('returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $bill        = null;
        $deadline    = null;
        $expired     = false;
        $returnDays  = (int) Setting::get('return_days', 7);

        if ($request->filled('bill_no')) {
            $bill = Bill::with('items')
                ->where('bill_no', $request->bill_no)
                ->orWhere('phone', $request->bill_no)
                ->first();

            if ($bill) {
                $deadline = $bill->date->copy()->addDays($returnDays);
                $expired  = Carbon::today()->gt($deadline);
            }
        }

        return view('returns.create', compact('bill', 'deadline', 'expired', 'returnDays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_id'     => 'required|exists:bills,id',
            'reason'      => 'nullable|string|max:500',
            'return_items' => 'required|array|min:1',
            'return_items.*' => 'exists:bill_items,id',
        ]);

        $bill       = Bill::with('items')->findOrFail($request->bill_id);
        $returnDays = (int) Setting::get('return_days', 7);
        $deadline   = $bill->date->copy()->addDays($returnDays);

        if (Carbon::today()->gt($deadline)) {
            return back()->with('error', 'Return period has expired for this bill.');
        }

        // Calculate total refund
        $totalRefund     = 0;
        $returnItemIds   = $request->return_items;
        $returnableItems = BillItem::whereIn('id', $returnItemIds)
            ->where('bill_id', $bill->id)
            ->where('is_returned', false)
            ->get();

        if ($returnableItems->isEmpty()) {
            return back()->with('error', 'No valid items selected for return.');
        }

        foreach ($returnableItems as $item) {
            $totalRefund += $item->final_price;
        }

        // Create return record
        $return = ReturnModel::create([
            'bill_id'     => $bill->id,
            'return_date' => Carbon::today(),
            'total_refund' => $totalRefund,
            'reason'      => $request->reason,
        ]);

        // Create return items & mark bill items as returned
        foreach ($returnableItems as $item) {
            ReturnItem::create([
                'return_id'    => $return->id,
                'bill_item_id' => $item->id,
                'refund_amount' => $item->final_price,
            ]);

            $item->update(['is_returned' => true]);
        }

        // Update bill status
        $bill->refresh();
        $allReturned  = $bill->items->every(fn($i) => $i->is_returned);
        $someReturned = $bill->items->some(fn($i) => $i->is_returned);

        $bill->update([
            'status' => $allReturned ? 'fully_returned' : ($someReturned ? 'partially_returned' : 'active'),
        ]);

        // Ledger debit entry
        LedgerEntry::create([
            'type'           => 'debit',
            'reference_type' => 'return',
            'reference_id'   => $return->id,
            'amount'         => $totalRefund,
            'description'    => "Return on bill {$bill->bill_no} — {$bill->customer_name}",
            'entry_date'     => Carbon::today(),
        ]);

        return redirect()->route('returns.index')
            ->with('success', "Return processed. Refund amount: ₹{$totalRefund}");
    }

    public function destroy(ReturnModel $return)
    {
        $return->load(['items.billItem', 'bill']);

        // Restore each bill item to active
        foreach ($return->items as $returnItem) {
            if ($returnItem->billItem) {
                $returnItem->billItem->update(['is_returned' => false]);
            }
        }

        // Delete ledger debit entry for this return
        LedgerEntry::where('reference_type', 'return')
            ->where('reference_id', $return->id)
            ->delete();

        // Delete the return record (cascades return_items)
        $bill = $return->bill;
        $return->delete();

        // Recalculate bill status
        $bill->refresh()->load('items');
        $allReturned  = $bill->items->every(fn($i) => $i->is_returned);
        $someReturned = $bill->items->some(fn($i) => $i->is_returned);
        $bill->update([
            'status' => $allReturned ? 'fully_returned' : ($someReturned ? 'partially_returned' : 'active'),
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'Return deleted and items restored to active.');
    }
}
