@extends('layouts.app')
@section('title', 'Returns')
@section('page-title', 'Returns')

@section('content')
<div class="space-y-5">

    <div class="flex justify-end">
        <a href="{{ route('returns.create') }}"
           class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Process New Return
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">All Returns <span class="text-gray-400 font-normal">({{ $returns->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Return #</th>
                        <th class="px-5 py-3 text-left font-medium">Bill No</th>
                        <th class="px-5 py-3 text-left font-medium">Customer</th>
                        <th class="px-5 py-3 text-center font-medium">Items Returned</th>
                        <th class="px-5 py-3 text-right font-medium">Refund Amount</th>
                        <th class="px-5 py-3 text-left font-medium">Date</th>
                        <th class="px-5 py-3 text-left font-medium">Reason</th>
                        <th class="px-5 py-3 text-center font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-700">#{{ $return->id }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('billing.show', $return->bill) }}" class="font-mono text-gray-900 hover:text-black font-medium">
                                    {{ $return->bill->bill_no }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ $return->bill->customer_name }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-red-100 text-red-700 font-medium">
                                    {{ $return->items->count() }} item(s)
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-red-600">&#8377;{{ number_format($return->total_refund, 2) }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $return->return_date->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $return->reason ?: '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                <form method="POST" action="{{ route('returns.destroy', $return) }}"
                                      onsubmit="return confirm('Delete this return? The items will be restored to active on the bill.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-xs font-medium px-2.5 py-1 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">No returns recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $returns->links() }}</div>
        @endif
    </div>
</div>
@endsection
