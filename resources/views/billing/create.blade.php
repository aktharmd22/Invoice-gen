@extends('layouts.app')
@section('title', 'New Bill')
@section('page-title', 'Create New Bill')

@section('content')
<div class="max-w-4xl mx-auto" x-data="billingForm()">

    <form method="POST" action="{{ route('billing.store') }}">
        @csrf

        <!-- Bill Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <h2 class="text-base font-semibold text-gray-700">Bill Details</h2>
                <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg">
                    <span class="text-xs text-indigo-500 font-medium">Bill No:</span>
                    <span class="font-mono text-gray-900 font-bold text-sm">{{ $billNo }}</span>
                    <input type="hidden" name="bill_no" value="{{ $billNo }}">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                           placeholder="Enter customer name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                           placeholder="e.g. 9876543210">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent bg-white">
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="GPay" {{ old('payment_method') == 'GPay' ? 'selected' : '' }}>GPay</option>
                        <option value="UPI"  {{ old('payment_method') == 'UPI'  ? 'selected' : '' }}>UPI</option>
                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="Other"{{ old('payment_method') == 'Other'? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-700">Bill Items</h2>
                <button type="button" @click="addItem"
                        class="flex items-center gap-2 bg-gray-900 hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Item
                </button>
            </div>

            <!-- Items list -->
            <div class="space-y-4">
                <template x-for="(item, index) in items" :key="item.id">
                    <div class="border border-gray-200 rounded-lg p-4 relative bg-gray-50">
                        <button type="button" @click="removeItem(index)"
                                class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pr-6">
                            <!-- Item Name -->
                            <div class="sm:col-span-2 lg:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Item Name *</label>
                                <input type="text" :name="'items['+index+'][item_name]'" x-model="item.item_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                                       placeholder="Item description">
                            </div>
                            <!-- Quantity -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Quantity *</label>
                                <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity"
                                       @input="calcFinal(item)" min="1" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                            </div>
                            <!-- MRP -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">MRP / Unit Price *</label>
                                <input type="number" :name="'items['+index+'][original_price]'" x-model.number="item.original_price"
                                       @input="calcFinal(item)" min="0" step="0.01" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                                       placeholder="0.00">
                            </div>
                            <!-- Discount % -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Discount %</label>
                                <div class="relative">
                                    <input type="number" x-model.number="item.discount_percent"
                                           @input="calcFinal(item)" min="0" max="100" step="0.01"
                                           class="w-full px-3 py-2 pr-7 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                                           placeholder="0">
                                    <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">%</span>
                                </div>
                                <input type="hidden" :name="'items['+index+'][discount_amount]'" :value="item.discount_amount.toFixed(2)">
                            </div>
                            <!-- Final Price -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Final Price</label>
                                <div class="relative">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                                    <input type="number" :name="'items['+index+'][final_price]'" :value="item.final_price.toFixed(2)"
                                           readonly
                                           class="w-full pl-6 pr-3 py-2 border border-gray-200 bg-white rounded-lg text-sm text-gray-900 font-semibold cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="items.length === 0" class="text-center py-8 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                    No items added yet. Click "Add Item" to start.
                </div>
            </div>

            <!-- Totals -->
            <div x-show="items.length > 0" class="mt-5 border-t border-gray-200 pt-4">
                <div class="flex justify-end">
                    <div class="w-full sm:w-72 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal (MRP × Qty)</span>
                            <span class="font-medium">&#8377;<span x-text="subtotal.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <span>Total Discount</span>
                            <span class="font-medium">- &#8377;<span x-text="totalDiscount.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                            <span>Grand Total</span>
                            <span class="text-gray-900">&#8377;<span x-text="grandTotal.toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('billing.index') }}"
               class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" :disabled="items.length === 0"
                    class="px-8 py-2.5 bg-gray-900 hover:bg-black disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                Save Bill
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function billingForm() {
    return {
        items: [],
        nextId: 1,

        get subtotal()      { return this.items.reduce((s, i) => s + (i.original_price * i.quantity), 0); },
        get totalDiscount() { return this.items.reduce((s, i) => s + i.discount_amount, 0); },
        get grandTotal()    { return this.items.reduce((s, i) => s + i.final_price, 0); },

        addItem() {
            this.items.push({
                id: this.nextId++,
                item_name: '',
                quantity: 1,
                original_price: 0,
                discount_percent: 0,
                discount_amount: 0,
                final_price: 0,
            });
        },

        removeItem(index) { this.items.splice(index, 1); },

        calcFinal(item) {
            const lineTotal = item.original_price * item.quantity;
            item.discount_amount = lineTotal * (item.discount_percent / 100);
            item.final_price = Math.max(0, lineTotal - item.discount_amount);
        },
    };
}
</script>
@endpush
