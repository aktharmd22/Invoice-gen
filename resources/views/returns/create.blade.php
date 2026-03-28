@extends('layouts.app')
@section('title', 'Process Return')
@section('page-title', 'Process Return')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    <!-- Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Search Bill</h2>
        <form method="GET" action="{{ route('returns.create') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="bill_no" value="{{ request('bill_no') }}"
                   placeholder="Enter Bill Number (e.g. BL-123456) or Phone Number"
                   class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-medium transition-colors">
                Search
            </button>
        </form>
    </div>

    @if(request('bill_no') && !$bill)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl px-5 py-4 text-sm">
            No bill found for "{{ request('bill_no') }}". Try a different bill number or phone number.
        </div>
    @endif

    @if($bill)
        <!-- Bill Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Bill No</p>
                    <p class="font-mono font-bold text-gray-900">{{ $bill->bill_no }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Customer</p>
                    <p class="font-medium text-gray-800">{{ $bill->customer_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Purchase Date</p>
                    <p class="font-medium text-gray-800">{{ $bill->date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Return Deadline</p>
                    <p class="font-medium {{ $expired ? 'text-red-600' : 'text-green-600' }}">
                        {{ $deadline->format('d M Y') }}
                        @if($expired)
                            <span class="text-xs">(Expired)</span>
                        @else
                            <span class="text-xs">({{ $deadline->diffForHumans() }})</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if($expired)
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-5 text-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Return Period Expired</p>
                        <p class="text-red-700 mt-0.5">The {{ $returnDays }}-day return window for this bill ended on {{ $deadline->format('d M Y') }}. Returns are no longer accepted.</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Return Form -->
            @php $activeItems = $bill->items->where('is_returned', false); @endphp
            @if($activeItems->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl px-5 py-4 text-sm">
                    All items in this bill have already been returned.
                </div>
            @else
                <form method="POST" action="{{ route('returns.store') }}">
                    @csrf
                    <input type="hidden" name="bill_id" value="{{ $bill->id }}">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="text-sm font-semibold text-gray-700">Select Items to Return</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Check items the customer wants to return. Refund = Final Price of each item.</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($activeItems as $item)
                                <label class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="return_items[]" value="{{ $item->id }}"
                                           class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">{{ $item->item_name }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-800">&#8377;{{ number_format($item->final_price, 2) }}</p>
                                        <p class="text-xs text-gray-400">Refund amount</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Reason (Optional)</label>
                        <textarea name="reason" rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none"
                                  placeholder="Reason for return...">{{ old('reason') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <a href="{{ route('returns.index') }}"
                           class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-8 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            Confirm Return
                        </button>
                    </div>
                </form>
            @endif
        @endif
    @endif
</div>
@endsection
