@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('content')
<div class="space-y-5">

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap gap-3">
            <select name="category" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 bg-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <input type="date" name="to" value="{{ request('to') }}"
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['category','from','to']))
                <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            @endif
            <div class="flex-1"></div>
            <a href="{{ route('expenses.create') }}"
               class="flex items-center gap-2 bg-gray-900 hover:bg-black text-white text-sm px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Expense
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Expenses <span class="text-gray-400 font-normal">({{ $expenses->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Category</th>
                        <th class="px-5 py-3 text-right font-medium">Amount</th>
                        <th class="px-5 py-3 text-left font-medium">Note</th>
                        <th class="px-5 py-3 text-left font-medium">Date</th>
                        <th class="px-5 py-3 text-center font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $expense)
                        @php
                            $catColors = [
                                'Rent' => 'bg-blue-100 text-blue-700',
                                'Staff Salary' => 'bg-purple-100 text-purple-700',
                                'Electricity' => 'bg-yellow-100 text-yellow-700',
                                'Miscellaneous' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $catColors[$expense->category] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800">&#8377;{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-5 py-3 text-gray-500 max-w-xs">{{ $expense->note ?: '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $expense->expense_date->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('expenses.edit', $expense) }}"
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2.5 py-1 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                          onsubmit="return confirm('Delete this expense?')">
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
                        <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">No expenses recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $expenses->links() }}</div>
        @endif
    </div>
</div>
@endsection
