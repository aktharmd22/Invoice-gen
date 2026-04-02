<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bill_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bills = $query->paginate(20)->withQueryString();

        return view('billing.index', compact('bills'));
    }

    public function create()
    {
        $billNo = $this->generateBillNo();
        return view('billing.create', compact('billNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'           => 'required|string|max:255',
            'phone'                   => 'required|string|max:20',
            'bill_no'                 => 'required|string|unique:bills,bill_no',
            'items'                   => 'required|array|min:1',
            'items.*.item_name'       => 'required|string|max:255',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.original_price'  => 'required|numeric|min:0',
            'items.*.discount_amount' => 'required|numeric|min:0',
            'items.*.final_price'     => 'required|numeric|min:0',
        ]);

        [$subtotal, $totalDiscount, $grandTotal] = $this->calcTotals($request->items);

        $paymentMethod  = $request->input('payment_method', 'Cash');
        $qrEnabled      = Setting::get('upi_qr_enabled', '0') === '1';
        $upiId          = Setting::get('upi_id', '');
        $paymentStatus  = ($qrEnabled && $upiId && $paymentMethod !== 'Cash') ? 'pending' : 'paid';

        $bill = Bill::create([
            'bill_no'         => $request->bill_no,
            'customer_name'   => $request->customer_name,
            'phone'           => $request->phone,
            'subtotal'        => $subtotal,
            'total_discount'  => $totalDiscount,
            'grand_total'     => $grandTotal,
            'status'          => 'active',
            'payment_method'  => $paymentMethod,
            'payment_status'  => $paymentStatus,
            'date'            => Carbon::today(),
        ]);

        $this->syncItems($bill, $request->items);

        // Auto-register customer (no duplicates by phone)
        Customer::updateOrCreate(
            ['phone' => $request->phone],
            ['name'  => $request->customer_name]
        );

        LedgerEntry::create([
            'type'           => 'credit',
            'reference_type' => 'bill',
            'reference_id'   => $bill->id,
            'amount'         => $grandTotal,
            'description'    => "Bill {$bill->bill_no} — {$bill->customer_name}",
            'entry_date'     => $bill->date,
        ]);

        return redirect()->route('billing.show', $bill)
            ->with('success', "Bill {$bill->bill_no} created successfully.");
    }

    public function show(Bill $billing)
    {
        $billing->load('items', 'returns');
        return view('billing.show', [
            'bill'      => $billing,
            'upiId'     => Setting::get('upi_id', ''),
            'shopName'  => Setting::get('shop_name', 'My Shop'),
        ]);
    }

    public function edit(Bill $billing)
    {
        if ($billing->status === 'fully_returned') {
            return redirect()->route('billing.show', $billing)
                ->with('error', 'Cannot edit a fully returned bill.');
        }
        $billing->load('items');
        return view('billing.edit', ['bill' => $billing]);
    }

    public function update(Request $request, Bill $billing)
    {
        if ($billing->status === 'fully_returned') {
            return redirect()->route('billing.show', $billing)
                ->with('error', 'Cannot edit a fully returned bill.');
        }

        $request->validate([
            'customer_name'           => 'required|string|max:255',
            'phone'                   => 'required|string|max:20',
            'items'                   => 'required|array|min:1',
            'items.*.item_name'       => 'required|string|max:255',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.original_price'  => 'required|numeric|min:0',
            'items.*.discount_amount' => 'required|numeric|min:0',
            'items.*.final_price'     => 'required|numeric|min:0',
        ]);

        // Delete only non-returned items; keep returned ones intact
        $billing->items()->where('is_returned', false)->delete();

        // Re-create the non-returned items from form
        foreach ($request->items as $item) {
            BillItem::create([
                'bill_id'         => $billing->id,
                'item_name'       => $item['item_name'],
                'original_price'  => $item['original_price'],
                'discount_amount' => $item['discount_amount'],
                'final_price'     => $item['final_price'],
                'quantity'        => $item['quantity'],
                'is_returned'     => false,
            ]);
        }

        // Recalculate totals across ALL items (including returned)
        $billing->refresh()->load('items');
        $allItems      = $billing->items;
        $subtotal      = $allItems->sum(fn($i) => $i->original_price * $i->quantity);
        $totalDiscount = $allItems->sum('discount_amount');
        $grandTotal    = $allItems->where('is_returned', false)->sum('final_price');

        $billing->update([
            'customer_name'  => $request->customer_name,
            'phone'          => $request->phone,
            'subtotal'       => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total'    => $grandTotal,
            'payment_method' => $request->input('payment_method', $billing->payment_method),
        ]);

        // Sync customer record
        Customer::updateOrCreate(
            ['phone' => $request->phone],
            ['name'  => $request->customer_name]
        );

        // Update ledger entry
        LedgerEntry::where('reference_type', 'bill')
            ->where('reference_id', $billing->id)
            ->update([
                'amount'      => $grandTotal,
                'description' => "Bill {$billing->bill_no} — {$billing->customer_name}",
            ]);

        return redirect()->route('billing.show', $billing)
            ->with('success', "Bill {$billing->bill_no} updated successfully.");
    }

    public function destroy(Bill $billing)
    {
        // Delete associated ledger entries
        LedgerEntry::where('reference_type', 'bill')->where('reference_id', $billing->id)->delete();

        // Also delete return ledger entries linked to this bill's returns
        foreach ($billing->returns as $return) {
            LedgerEntry::where('reference_type', 'return')->where('reference_id', $return->id)->delete();
        }

        $billing->delete(); // cascades items + returns + return_items

        return redirect()->route('billing.index')
            ->with('success', 'Bill deleted successfully.');
    }

    public function confirmPayment(Bill $billing)
    {
        $billing->update(['payment_status' => 'paid']);
        return redirect()->route('billing.show', $billing)
            ->with('success', 'Payment confirmed. Bill is ready.');
    }

    public function downloadPdf(Bill $billing)
    {
        if ($billing->payment_status === 'pending') {
            return redirect()->route('billing.show', $billing)
                ->with('error', 'Payment not yet confirmed. Please confirm payment first.');
        }

        $billing->load('items', 'returns.items.billItem');

        $returnDays  = (int) Setting::get('return_days', 7);
        $returnUntil = $billing->date->copy()->addDays($returnDays)->format('d M Y');

        $pdf = Pdf::loadView('pdf.invoice', [
            'bill'         => $billing,
            'shopName'     => Setting::get('shop_name',     'My Shop'),
            'shopPhone'    => Setting::get('shop_phone',    ''),
            'shopAddress'  => Setting::get('shop_address',  ''),
            'shopMapsUrl'  => Setting::get('shop_maps_url', ''),
            'shopInsta'    => Setting::get('shop_instagram',''),
            'shopLogo'     => Setting::get('shop_logo',     ''),
            'returnUntil'  => $returnUntil,
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("invoice-{$billing->bill_no}.pdf");
    }

    // ── Helpers ──────────────────────────────────────────────

    private function calcTotals(array $items): array
    {
        $subtotal = $totalDiscount = $grandTotal = 0;
        foreach ($items as $item) {
            $subtotal      += $item['original_price'] * $item['quantity'];
            $totalDiscount += $item['discount_amount'];
            $grandTotal    += $item['final_price'];
        }
        return [$subtotal, $totalDiscount, $grandTotal];
    }

    private function syncItems(Bill $bill, array $items): void
    {
        foreach ($items as $item) {
            BillItem::create([
                'bill_id'         => $bill->id,
                'item_name'       => $item['item_name'],
                'original_price'  => $item['original_price'],
                'discount_amount' => $item['discount_amount'],
                'final_price'     => $item['final_price'],
                'quantity'        => $item['quantity'],
                'is_returned'     => false,
            ]);
        }
    }

    private function generateBillNo(): string
    {
        do {
            $number = 'BL-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (Bill::where('bill_no', $number)->exists());

        return $number;
    }
}
