<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    private function applyDateFilters($query, Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        return $query;
    }

    public function index(Request $request)
    {
        // Transactions
        $transactionsQuery = Transaction::query();
        $this->applyDateFilters($transactionsQuery, $request);
        $transactions = $transactionsQuery->orderBy('transaction_date', 'desc')->get();

        // Statistics
        $statsQuery = Transaction::query();
        $this->applyDateFilters($statsQuery, $request);
        $totalExpense = $statsQuery->sum('amount');
        $transactionCount = $statsQuery->count();
        $averageExpense = $transactionCount > 0 ? $totalExpense / $transactionCount : 0;

        // Category breakdown
        $breakdownQuery = Transaction::query();
        $this->applyDateFilters($breakdownQuery, $request);
        $categoryBreakdown = $breakdownQuery->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        return view('transactions.index', compact('transactions', 'totalExpense', 'transactionCount', 'averageExpense', 'categoryBreakdown'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:food,transport,utilities,entertainment,salary,shopping,other',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|string|in:income,expense',
            'transaction_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        Transaction::create([
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'note' => $request->note,
        ]);

        return redirect('/transactions')->with('success', 'Transaksi berhasil disimpan');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|in:food,transport,utilities,entertainment,salary,shopping,other',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|string|in:income,expense',
            'transaction_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'note' => $request->note,
        ]);

        return redirect('/transactions')->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy($id)
    {
        Transaction::find($id)->delete();
        return redirect('/transactions')->with('success', 'Transaksi berhasil dihapus');
    }

    public function latest()
    {
        $latestTransaction = Transaction::orderBy('updated_at', 'desc')->first();
        $count = Transaction::count();

        return response()->json([
            'latest_timestamp' => $latestTransaction ? $latestTransaction->updated_at->timestamp : null,
            'count' => $count,
        ]);
    }
}
