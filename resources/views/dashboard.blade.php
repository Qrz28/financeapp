@extends('layouts.app')

@section('hide-navigation', 'true')
@section('full-width', 'true')

@section('content')
<div class="flex h-screen w-full bg-[#f4f6fc] overflow-hidden font-sans">
    <!-- Desktop Left Side (Analytics & QR) -->
    <div class="hidden lg:flex flex-col flex-1 p-10 overflow-y-auto">
        <!-- Dashboard Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-[#1a1832] mb-2">Finance Dashboard</h1>
            <p class="text-gray-500">Scan the QR code below to manage your finances directly from your phone.</p>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Total Expenses Chart Card -->
            <div class="bg-white rounded-[32px] p-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Expenses by Category</h3>
                <div class="relative h-48 w-full flex justify-center items-center">
                    @if($chartData->count() > 0)
                        <canvas id="expenseChart"></canvas>
                    @else
                        <p class="text-sm text-gray-400">No expenses this month</p>
                    @endif
                </div>
            </div>

            <!-- QR Code Card -->
            <div class="bg-white rounded-[32px] p-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)] flex flex-col items-center justify-center">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Mobile Companion</h3>
                <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code" class="w-32 h-32">
                </div>
                <p class="text-sm text-gray-500 mt-4 text-center">Scan to open on your phone</p>

            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Income</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($income, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Expense</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($expense, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
                <p class="text-sm font-medium text-gray-500 mb-1">Net Balance</p>
                <h3 class="text-2xl font-bold {{ $balance < 0 ? 'text-red-500' : 'text-green-500' }}">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Mobile View on Right Side -->
    <div class="w-full lg:w-[420px] bg-white h-screen overflow-y-auto relative shadow-[-10px_0_30px_rgba(0,0,0,0.05)] flex-shrink-0 scrollbar-hide">
        <!-- START MOBILE DASHBOARD -->
        <div class="gradient-bg min-h-screen relative flex flex-col font-sans">
            <!-- Header Area (Over gradient) -->
            <div class="px-5 pt-12 pb-6">
                <!-- Header: Greeting & Avatar -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f3e8ff&color=7c3aed" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h1 class="text-base font-bold text-gray-900">Good Morning, {{ explode(' ', auth()->user()->name)[0] }}</h1>
                            <p class="text-[11px] text-gray-500 mt-0.5">Manage your finances with confidence today.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-800 hover:text-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Current Balance -->
                <div class="text-center mb-8">
                    <p class="text-[13px] text-gray-400 mb-1 font-medium">Current Balance</p>
                    <h2 class="text-5xl font-extrabold text-[#1a1832] tracking-tight mb-4">
                        {{ $balance < 0 ? '-' : '' }}Rp {{ number_format(abs($balance), 0, ',', '.') }}
                    </h2>
                    <div class="flex items-center justify-center">
                        <div class="inline-flex items-center bg-white/70 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/50 shadow-sm text-xs text-gray-600 font-medium">
                            <span>{{ $monthlyIncome - $monthlyExpense >= 0 ? '+' : '' }} Rp {{ number_format($monthlyIncome - $monthlyExpense, 0, ',', '.') }} this month</span>
                            <span class="mx-2 text-gray-300">&bull;</span>
                            <span>{{ $transactionCount }} Transactions</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- White Overlapping Card -->
            <div class="bg-white rounded-t-[32px] pt-8 px-6 flex-1 pb-24 shadow-[0_-8px_24px_rgba(0,0,0,0.02)] relative z-10">
                <!-- Your Balance Section -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-1">
                        <h3 class="text-base font-extrabold text-gray-900">Your Balance</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-gray-700">Detail &rsaquo;</a>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <!-- Income Card -->
                    <div class="bg-[#f8f9fc] rounded-[20px] p-5">
                        <div class="w-10 h-10 rounded-2xl bg-green-50 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m19 19-7 3-7-3"/><path d="M2 12h20"/></svg>
                        </div>
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <p class="text-[13px] font-medium text-gray-500">Income</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </div>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($income, 0, ',', '.') }}</p>
                    </div>

                    <!-- Expenses Card -->
                    <div class="bg-[#f8f9fc] rounded-[20px] p-5">
                        <div class="w-10 h-10 rounded-2xl bg-red-50 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <p class="text-[13px] font-medium text-gray-500">Expenses</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </div>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($expense, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Today's Transactions -->
                <div class="flex items-center justify-between mb-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">TODAY</p>
                    <p class="text-xs font-medium text-gray-400">
                        Total {{ $balance < 0 ? '-' : '' }}Rp {{ number_format(abs($balance), 0, ',', '.') }}
                    </p>
                </div>

                <div class="space-y-5">
                    @forelse($recentTransactions as $transaction)
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-[50px] h-[50px] rounded-[16px] {{ $transaction->type === 'income' ? 'bg-green-50 text-green-500' : 'bg-[#fff5eb] text-[#fca546]' }} flex items-center justify-center flex-shrink-0">
                                    @if($transaction->type === 'income')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m19 19-7 3-7-3"/><path d="M2 12h20"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[15px] font-bold text-gray-900 mb-1">{{ ucfirst($transaction->category) }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-[#f1f3f9] text-[10px] font-semibold text-gray-500">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                        @if($transaction->note)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-[#f1f3f9] text-[10px] font-semibold text-gray-500 truncate max-w-[100px]">
                                                {{ $transaction->note }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-[15px] font-bold text-gray-900">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] font-medium text-gray-400">{{ $transaction->transaction_date->format('M d, Y') }}</span>
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-red-400 transition-colors" onclick="return confirm('Hapus transaksi ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-sm text-gray-400 font-medium">Belum ada transaksi hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Add a wrapper for the absolute bottom nav within the mobile frame -->
        <div class="absolute bottom-0 left-0 right-0">
            @include('layouts.bottom-nav')
        </div>
        <!-- END MOBILE DASHBOARD -->
    </div>
</div>

<!-- Chart.js Script for Desktop View -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('expenseChart');
        const chartData = @json($chartData);
        
        if(ctx && chartData.length > 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.map(d => d.category.charAt(0).toUpperCase() + d.category.slice(1)),
                    datasets: [{
                        data: chartData.map(d => d.total),
                        backgroundColor: [
                            '#8b5cf6', '#fca546', '#3b82f6', '#10b981', '#f43f5e', '#64748b', '#0ea5e9'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { 
                            position: 'right', 
                            labels: { usePointStyle: true, padding: 20, font: { family: "'Figtree', sans-serif" } } 
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
