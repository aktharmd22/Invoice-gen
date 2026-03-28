@extends('layouts.app')
@section('title', 'All Bills')
@section('page-title', 'All Bills')

@section('content')
<div class="space-y-5">

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('billing.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search bill no, phone, customer..."
                   class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 bg-white">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="partially_returned" {{ request('status') === 'partially_returned' ? 'selected' : '' }}>Partially Returned</option>
                <option value="fully_returned" {{ request('status') === 'fully_returned' ? 'selected' : '' }}>Fully Returned</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-medium transition-colors">
                Search
            </button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('billing.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Bills <span class="text-gray-400 font-normal">({{ $bills->total() }})</span></h2>
            <a href="{{ route('billing.create') }}" class="flex items-center gap-2 bg-gray-900 hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Bill
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Bill No</th>
                        <th class="px-5 py-3 text-left font-medium">Customer</th>
                        <th class="px-5 py-3 text-left font-medium">Phone</th>
                        <th class="px-5 py-3 text-right font-medium">Grand Total</th>
                        <th class="px-5 py-3 text-left font-medium">Status</th>
                        <th class="px-5 py-3 text-left font-medium">Date</th>
                        <th class="px-5 py-3 text-center font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bills as $bill)
                        @php
                            $sc = ['active'=>'bg-green-100 text-green-700','partially_returned'=>'bg-yellow-100 text-yellow-700','fully_returned'=>'bg-red-100 text-red-700'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <a href="{{ route('billing.show', $bill) }}" class="font-mono text-gray-900 hover:text-black font-medium">{{ $bill->bill_no }}</a>
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ $bill->customer_name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $bill->phone }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800">&#8377;{{ number_format($bill->grand_total, 2) }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$bill->status] ?? '' }}">
                                    {{ ucwords(str_replace('_', ' ', $bill->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $bill->date->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <a href="{{ route('billing.show', $bill) }}"
                                       class="text-gray-900 hover:text-black text-xs font-medium px-2.5 py-1 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('billing.pdf', $bill) }}"
                                       class="text-gray-600 hover:text-gray-800 text-xs font-medium px-2.5 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        PDF
                                    </a>
                                    @if($bill->status !== 'fully_returned')
                                        <a href="{{ route('billing.edit', $bill) }}"
                                           class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2.5 py-1 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                                            Edit
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('billing.destroy', $bill) }}"
                                          onsubmit="return confirm('Delete {{ $bill->bill_no }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-xs font-medium px-2.5 py-1 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No bills found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bills->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
