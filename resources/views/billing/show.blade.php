@extends('layouts.app')
@section('title', 'Bill '.$bill->bill_no)
@section('page-title', 'Bill Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500">Bill Number</p>
            <p class="text-xl font-mono font-bold text-gray-900">{{ $bill->bill_no }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('billing.pdf', $bill) }}"
               class="flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                PDF
            </a>
            @if($bill->status !== 'fully_returned')
                <a href="{{ route('billing.edit', $bill) }}"
                   class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('returns.create', ['bill_no' => $bill->bill_no]) }}"
                   class="flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    Return
                </a>
            @endif
            <form method="POST" action="{{ route('billing.destroy', $bill) }}"
                  onsubmit="return confirm('Delete bill {{ $bill->bill_no }}? This will also remove all its returns and ledger entries.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
            <a href="{{ route('billing.index') }}"
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                &larr; Back
            </a>
        </div>
    </div>

    <!-- Bill Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Customer</p>
                <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $bill->customer_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Phone</p>
                <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $bill->phone }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Date</p>
                <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $bill->date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Status</p>
                @php $sc = ['active'=>'bg-green-100 text-green-700','partially_returned'=>'bg-yellow-100 text-yellow-700','fully_returned'=>'bg-red-100 text-red-700']; @endphp
                <span class="mt-0.5 inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sc[$bill->status] ?? '' }}">
                    {{ ucwords(str_replace('_', ' ', $bill->status)) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Payment</p>
                <span class="mt-0.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    {{ $bill->payment_method }}
                </span>
            </div>
        </div>
    </div>

    <!-- Items Table (Internal View — shows MRP, discount, final) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Items (Internal View)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">#</th>
                        <th class="px-5 py-3 text-left font-medium">Item</th>
                        <th class="px-5 py-3 text-center font-medium">Qty</th>
                        <th class="px-5 py-3 text-right font-medium">MRP/Unit</th>
                        <th class="px-5 py-3 text-right font-medium">Discount</th>
                        <th class="px-5 py-3 text-right font-medium">Final Price</th>
                        <th class="px-5 py-3 text-center font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bill->items as $i => $item)
                        <tr class="{{ $item->is_returned ? 'bg-red-50 text-gray-400' : 'hover:bg-gray-50' }} transition-colors">
                            <td class="px-5 py-3">{{ $i + 1 }}</td>
                            <td class="px-5 py-3 font-medium {{ $item->is_returned ? '' : 'text-gray-800' }}">
                                {{ $item->item_name }}
                            </td>
                            <td class="px-5 py-3 text-center">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-right">&#8377;{{ number_format($item->original_price, 2) }}</td>
                            <td class="px-5 py-3 text-right text-red-500">- &#8377;{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right font-semibold {{ $item->is_returned ? '' : 'text-gray-800' }}">
                                &#8377;{{ number_format($item->final_price, 2) }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($item->is_returned)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-600">Returned</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-600">Active</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Totals -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-end">
            @php $totalRefunded = $bill->returns->sum('total_refund'); @endphp
            <div class="w-full sm:w-80 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal (MRP × Qty)</span>
                    <span class="font-medium">&#8377;{{ number_format($bill->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-red-600">
                    <span>Total Discount</span>
                    <span class="font-medium">- &#8377;{{ number_format($bill->total_discount, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                    <span>Grand Total</span>
                    <span>&#8377;{{ number_format($bill->grand_total, 2) }}</span>
                </div>
                @if($totalRefunded > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Returns Refunded</span>
                        <span class="font-medium">- &#8377;{{ number_format($totalRefunded, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                        <span>Net Amount</span>
                        <span class="text-green-700">&#8377;{{ number_format($bill->grand_total - $totalRefunded, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Returns History -->
    @if($bill->returns->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Return History</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">Return #</th>
                            <th class="px-5 py-3 text-left font-medium">Date</th>
                            <th class="px-5 py-3 text-right font-medium">Refund</th>
                            <th class="px-5 py-3 text-left font-medium">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bill->returns as $return)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-gray-700">#{{ $return->id }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $return->return_date->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-red-600">&#8377;{{ number_format($return->total_refund, 2) }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $return->reason ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
