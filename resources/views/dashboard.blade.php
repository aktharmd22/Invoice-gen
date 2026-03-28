@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Row 1: Today -->
    <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Today</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sales</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">&#8377;{{ number_format($todaySales, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-gray-500 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Returns</p>
                <p class="text-2xl font-bold text-red-600 mt-1">&#8377;{{ number_format($todayReturns, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-red-400 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Expenses</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">&#8377;{{ number_format($todayExpenses, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-orange-400 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Net Profit</p>
                <p class="text-2xl font-bold {{ $todayProfit >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                    &#8377;{{ number_format($todayProfit, 2) }}
                </p>
                <div class="mt-2 w-8 h-1 {{ $todayProfit >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded"></div>
            </div>
        </div>
    </div>

    <!-- Row 2: This Week -->
    <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">This Week</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sales</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">&#8377;{{ number_format($weekSales, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-gray-500 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Returns</p>
                <p class="text-2xl font-bold text-red-600 mt-1">&#8377;{{ number_format($weekReturns, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-red-400 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Expenses</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">&#8377;{{ number_format($weekExpenses, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-orange-400 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Net Profit</p>
                <p class="text-2xl font-bold {{ $weekProfit >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                    &#8377;{{ number_format($weekProfit, 2) }}
                </p>
                <div class="mt-2 w-8 h-1 {{ $weekProfit >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded"></div>
            </div>
        </div>
    </div>

    <!-- Row 3: This Month -->
    <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">This Month</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sales</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">&#8377;{{ number_format($monthSales, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-gray-500 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Returns</p>
                <p class="text-2xl font-bold text-red-600 mt-1">&#8377;{{ number_format($monthReturns, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-red-400 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Expenses</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">&#8377;{{ number_format($monthExpenses, 2) }}</p>
                <div class="mt-2 w-8 h-1 bg-orange-400 rounded"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Net Profit</p>
                <p class="text-2xl font-bold {{ $monthProfit >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                    &#8377;{{ number_format($monthProfit, 2) }}
                </p>
                <div class="mt-2 w-8 h-1 {{ $monthProfit >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded"></div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Last 7 Days &mdash; Sales vs Expenses</h2>
        <div class="relative h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Recent Bills -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Recent Bills</h2>
            <a href="{{ route('billing.index') }}" class="text-xs text-gray-900 hover:text-black font-medium">View all &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Bill No</th>
                        <th class="px-5 py-3 text-left font-medium">Customer</th>
                        <th class="px-5 py-3 text-left font-medium">Amount</th>
                        <th class="px-5 py-3 text-left font-medium">Status</th>
                        <th class="px-5 py-3 text-left font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentBills as $bill)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <a href="{{ route('billing.show', $bill) }}" class="font-mono text-gray-900 hover:text-black font-medium">{{ $bill->bill_no }}</a>
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ $bill->customer_name }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-800">&#8377;{{ number_format($bill->grand_total, 2) }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $sc = ['active'=>'bg-green-100 text-green-700','partially_returned'=>'bg-yellow-100 text-yellow-700','fully_returned'=>'bg-red-100 text-red-700'];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$bill->status] ?? '' }}">
                                    {{ ucwords(str_replace('_', ' ', $bill->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $bill->date->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">No bills yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Sales (Rs)',
                data: @json($chartSales),
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderColor: 'rgba(99,102,241,1)',
                borderWidth: 1,
                borderRadius: 4,
            },
            {
                label: 'Expenses (Rs)',
                data: @json($chartExpenses),
                backgroundColor: 'rgba(251,146,60,0.7)',
                borderColor: 'rgba(251,146,60,1)',
                borderWidth: 1,
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rs ' + v.toLocaleString() } }
        }
    }
});
</script>
@endpush
