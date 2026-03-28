<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    private array $categories = ['Rent', 'Staff Salary', 'Electricity', 'Miscellaneous'];

    public function index(Request $request)
    {
        $query = Expense::latest('expense_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        $expenses   = $query->paginate(20)->withQueryString();
        $categories = $this->categories;

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = $this->categories;
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category'     => 'required|in:Rent,Staff Salary,Electricity,Miscellaneous',
            'amount'       => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::create($data);

        LedgerEntry::create([
            'type'           => 'debit',
            'reference_type' => 'expense',
            'reference_id'   => $expense->id,
            'amount'         => $expense->amount,
            'description'    => "{$expense->category} expense" . ($expense->note ? " — {$expense->note}" : ''),
            'entry_date'     => $expense->expense_date,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense of ₹' . number_format($expense->amount, 2) . ' recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = $this->categories;
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'category'     => 'required|in:Rent,Staff Salary,Electricity,Miscellaneous',
            'amount'       => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date',
        ]);

        $expense->update($data);

        // Sync ledger entry
        LedgerEntry::where('reference_type', 'expense')
            ->where('reference_id', $expense->id)
            ->update([
                'amount'      => $expense->amount,
                'description' => "{$expense->category} expense" . ($expense->note ? " — {$expense->note}" : ''),
                'entry_date'  => $expense->expense_date,
            ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        LedgerEntry::where('reference_type', 'expense')
            ->where('reference_id', $expense->id)
            ->delete();

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
