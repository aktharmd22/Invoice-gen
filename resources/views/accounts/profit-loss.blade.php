@extends('layouts.app')
@section('title', 'Profit & Loss')
@section('page-title', 'Profit & Loss')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1.5 flex gap-1">
        @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $key => $label)
            <a href="{{ route('accounts.profit-loss', ['tab' => $key]) }}"
               class="flex-1 text-center py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ $tab === $key ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- P&L Table -->
    @php $d = $data[$tab]; @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">
                @if($tab === 'today') Today's Summary
                @elseif($tab === 'week') This Week's Summary
                @else This Month's Summary
                @endif
            </h2>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="flex items-center justify-between px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 rounded bg-green-500"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Gross Sales</p>
                        <p class="text-xs text-gray-400">Total bills (credits)</p>
                    </div>
                </div>
                <span class="text-base font-bold text-green-600">&#8377;{{ number_format($d['sales'], 2) }}</span>
            </div>
            <div class="flex items-center justify-between px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 rounded bg-orange-400"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Total Returns</p>
                        <p class="text-xs text-gray-400">Refunds issued (debits)</p>
                    </div>
                </div>
                <span class="text-base font-bold text-orange-600">- &#8377;{{ number_format($d['returns'], 2) }}</span>
            </div>
            <div class="flex items-center justify-between px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 rounded bg-red-500"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Total Expenses</p>
                        <p class="text-xs text-gray-400">Operational costs (debits)</p>
                    </div>
                </div>
                <span class="text-base font-bold text-red-600">- &#8377;{{ number_format($d['expenses'], 2) }}</span>
            </div>
            <div class="flex items-center justify-between px-5 py-5 bg-gray-50 rounded-b-xl">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 rounded {{ $d['net'] >= 0 ? 'bg-gray-900' : 'bg-red-600' }}"></div>
                    <div>
                        <p class="text-base font-bold text-gray-800">Net Profit</p>
                        <p class="text-xs text-gray-400">Sales &minus; Returns &minus; Expenses</p>
                    </div>
                </div>
                <span class="text-xl font-bold {{ $d['net'] >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                    &#8377;{{ number_format($d['net'], 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- All tabs overview -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">All Periods Overview</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Period</th>
                        <th class="px-5 py-3 text-right font-medium">Gross Sales</th>
                        <th class="px-5 py-3 text-right font-medium">Returns</th>
                        <th class="px-5 py-3 text-right font-medium">Expenses</th>
                        <th class="px-5 py-3 text-right font-medium">Net Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $key => $label)
                        @php $r = $data[$key]; @endphp
                        <tr class="hover:bg-gray-50 {{ $tab === $key ? 'bg-gray-50' : '' }}">
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $label }}</td>
                            <td class="px-5 py-3 text-right text-green-600 font-medium">&#8377;{{ number_format($r['sales'], 2) }}</td>
                            <td class="px-5 py-3 text-right text-orange-600 font-medium">&#8377;{{ number_format($r['returns'], 2) }}</td>
                            <td class="px-5 py-3 text-right text-red-600 font-medium">&#8377;{{ number_format($r['expenses'], 2) }}</td>
                            <td class="px-5 py-3 text-right font-bold {{ $r['net'] >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                                &#8377;{{ number_format($r['net'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
