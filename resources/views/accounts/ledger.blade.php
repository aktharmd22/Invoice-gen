@extends('layouts.app')
@section('title', 'Ledger')
@section('page-title', 'Account Ledger')

@section('content')
<div class="space-y-5">

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('accounts.ledger') }}" class="flex flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600 whitespace-nowrap">From:</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600 whitespace-nowrap">To:</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 bg-white">
                <option value="">All Types</option>
                <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
            </select>
            <button type="submit" class="px-5 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['from','to','type']))
                <a href="{{ route('accounts.ledger') }}" class="px-5 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700">Ledger Entries <span class="text-gray-400 font-normal">({{ $entries->total() }})</span></h2>
            <a href="{{ route('accounts.ledger.export', request()->only(['from','to','type'])) }}"
               class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v3a1 1 0 001 1h16a1 1 0 001-1v-3"/>
                </svg>
                Export CSV
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Date</th>
                        <th class="px-5 py-3 text-left font-medium">Type</th>
                        <th class="px-5 py-3 text-left font-medium">Reference</th>
                        <th class="px-5 py-3 text-left font-medium">Description</th>
                        <th class="px-5 py-3 text-right font-medium">Amount</th>
                        <th class="px-5 py-3 text-right font-medium">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $entry->entry_date->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $entry->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($entry->type) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 capitalize text-xs">
                                {{ $entry->reference_type }} #{{ $entry->reference_id }}
                            </td>
                            <td class="px-5 py-3 text-gray-700 max-w-xs">{{ $entry->description }}</td>
                            <td class="px-5 py-3 text-right font-semibold whitespace-nowrap {{ $entry->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $entry->type === 'credit' ? '+' : '-' }} &#8377;{{ number_format($entry->amount, 2) }}
                            </td>
                            <td class="px-5 py-3 text-right font-bold whitespace-nowrap {{ ($balances[$entry->id] ?? 0) >= 0 ? 'text-gray-800' : 'text-red-600' }}">
                                &#8377;{{ number_format($balances[$entry->id] ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No ledger entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($entries->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $entries->links() }}</div>
        @endif
    </div>
</div>
@endsection
