<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingsGoal;
use App\Models\Transaction;

class SavingsGoalController extends Controller
{
    public function index()
    {
        $goals = SavingsGoal::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $activeGoals = $goals->where('status', 'active');
        $completedGoals = $goals->where('status', 'completed');

        // Menghitung Saldo Utama (sama seperti Dashboard)
        $income = Transaction::where('type', 'income')->sum('amount');
        $expense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        // Mendapatkan riwayat tabungan khusus
        $savingsTransactions = Transaction::whereNotNull('savings_goal_id')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('savings.index', compact('activeGoals', 'completedGoals', 'balance', 'savingsTransactions'));
    }

    public function create()
    {
        return view('savings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'nullable|date|after_or_equal:today',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|in:purple,blue,green,amber,rose,emerald,cyan',
        ]);

        SavingsGoal::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'icon' => $request->icon,
            'color' => $request->color,
            'status' => 'active',
        ]);

        return redirect()->route('savings.index')->with('success', 'Target celengan berhasil dibuat!');
    }

    public function addFunds(Request $request, SavingsGoal $goal)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Proteksi: Hitung saldo aktif dompet
        $income = Transaction::where('type', 'income')->sum('amount');
        $expense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        if ($request->amount > $balance) {
            return back()->withErrors(['amount' => 'Saldo dompet tidak mencukupi untuk ditabung!']);
        }

        // Catat sebagai transaksi pengeluaran (mengurangi saldo aktif)
        Transaction::create([
            'type' => 'expense',
            'category' => 'savings',
            'amount' => $request->amount,
            'transaction_date' => now(),
            'note' => 'Menabung: ' . $goal->name,
            'savings_goal_id' => $goal->id,
        ]);

        // Update saldo celengan
        $goal->current_amount += $request->amount;
        
        // Cek jika target tercapai
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->status = 'completed';
        }
        $goal->save();

        return redirect()->route('savings.index')->with('success', 'Berhasil menabung ke celengan!')->with('savings_success', true);
    }

    public function withdrawFunds(Request $request, SavingsGoal $goal)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($request->amount > $goal->current_amount) {
            return back()->withErrors(['amount' => 'Dana di celengan tidak mencukupi untuk dicairkan!']);
        }

        // Catat sebagai transaksi pemasukan (menambah kembali saldo aktif)
        Transaction::create([
            'type' => 'income',
            'category' => 'savings',
            'amount' => $request->amount,
            'transaction_date' => now(),
            'note' => 'Pencairan: ' . $goal->name,
            'savings_goal_id' => $goal->id,
        ]);

        // Update saldo celengan
        $goal->current_amount -= $request->amount;

        // Cek status target
        if ($goal->current_amount < $goal->target_amount) {
            $goal->status = 'active';
        }
        $goal->save();

        return redirect()->route('savings.index')->with('success', 'Dana celengan berhasil dicairkan ke saldo utama!');
    }

    public function destroy(SavingsGoal $goal)
    {
        // Refund otomatis ke saldo utama jika celengan masih memiliki dana
        if ($goal->current_amount > 0) {
            Transaction::create([
                'type' => 'income',
                'category' => 'savings',
                'amount' => $goal->current_amount,
                'transaction_date' => now(),
                'note' => 'Pengembalian Dana Celengan (Dihapus): ' . $goal->name,
            ]);
        }

        // Putus hubungan transaksi lama agar tidak error karena foreign key nullOnDelete
        $goal->delete();

        return redirect()->route('savings.index')->with('success', 'Celengan berhasil dihapus, dana tersisa dikembalikan ke saldo utama!');
    }
}
