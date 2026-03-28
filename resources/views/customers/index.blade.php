@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="space-y-5">

    <!-- Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name or phone..."
                   class="flex-1 min-w-48 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <button type="submit"
                    class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-medium transition-colors">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('customers.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">
                All Customers
                <span class="text-gray-400 font-normal">({{ $customers->total() }})</span>
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">#</th>
                        <th class="px-5 py-3 text-left font-medium">Name</th>
                        <th class="px-5 py-3 text-left font-medium">Phone</th>
                        <th class="px-5 py-3 text-center font-medium">Purchases</th>
                        <th class="px-5 py-3 text-left font-medium">First Visit</th>
                        <th class="px-5 py-3 text-left font-medium">Last Visit</th>
                        <th class="px-5 py-3 text-center font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $i => $customer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-400 text-xs">
                                {{ $customers->firstItem() + $i }}
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $customer->name }}</td>
                            <td class="px-5 py-3 text-gray-600 font-mono">{{ $customer->phone }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($customer->bills_count > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $customer->bills_count >= 5 ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $customer->bills_count }}
                                        {{ $customer->bills_count === 1 ? 'purchase' : 'purchases' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ $customer->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                @php
                                    $lastBill = \App\Models\Bill::where('phone', $customer->phone)->latest('date')->first();
                                @endphp
                                {{ $lastBill ? $lastBill->date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('billing.index', ['search' => $customer->phone]) }}"
                                   class="text-gray-600 hover:text-black text-xs font-medium px-2.5 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    View Bills
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                                No customers yet. They'll appear automatically when bills are created.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection
