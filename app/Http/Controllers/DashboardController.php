<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $income = Transaction::where('type', 'income')->sum('amount');

        $expense = Transaction::where('type', 'expense')->sum('amount');

        $balance = $income - $expense;

        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $monthlyIncome = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyExpense = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $transactionCount = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->count();

        $recentTransactions = Transaction::orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Chart Data (Expenses by category this month)
        $chartData = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        // Generate simple token for QR login (not signed URL since host changes)
        $token = hash('sha256', auth()->id() . now()->format('Y-m-d')); // Token valid for 24 hours
        $hostIp = request()->getHost();
        if ($hostIp === 'localhost' || $hostIp === '127.0.0.1') {
            $hostIp = getHostByName(getHostName());
        }
        $port = request()->getPort();
        $portSuffix = ($port != 80 && $port != 443) ? ":{$port}" : "";
        $qrUrl = request()->getScheme() . '://' . $hostIp . $portSuffix . '/qr/login/' . auth()->id() . '?token=' . $token;

        return view('dashboard', compact(
            'income',
            'expense',
            'balance',
            'monthlyIncome',
            'monthlyExpense',
            'transactionCount',
            'recentTransactions',
            'chartData',
            'qrUrl'
        ));
    }

    public function qrLogin(\App\Models\User $user)
    {
        // Validate token (valid for 24 hours)
        $requestToken = request()->query('token');
        $expectedToken = hash('sha256', $user->id . now()->format('Y-m-d'));

        if ($requestToken !== $expectedToken) {
            abort(403, 'Invalid or expired token');
        }

        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard');
    }
}