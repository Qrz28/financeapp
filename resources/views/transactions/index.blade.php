@extends('layouts.app')

@section('hide-navigation', 'true')

@section('content')
<div class="gradient-bg min-h-screen relative flex flex-col">
    <!-- Header Area (Over gradient) -->
    <div class="px-5 pt-12 pb-6">
        <div class="flex items-center gap-4 mb-8">
            <h1 class="text-3xl font-extrabold text-[#1a1832] tracking-tight">Report</h1>
        </div>

        <div class="text-center mb-6">
            <p class="text-[13px] text-gray-500 mb-1 font-medium">Total Expense</p>
            <h2 class="text-4xl font-extrabold text-[#1a1832] tracking-tight">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <!-- White Overlapping Card -->
    <div class="bg-white rounded-t-[32px] pt-8 px-6 flex-1 pb-24 shadow-[0_-8px_24px_rgba(0,0,0,0.02)] relative z-10">
        <div class="flex items-center justify-between mb-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">TRANSACTION HISTORY</p>
            <p class="text-xs font-medium text-gray-400">{{ $transactionCount }} Items</p>
        </div>

        <div class="space-y-5">
            @forelse($transactions as $transaction)
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
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="text-gray-300 hover:text-[#8b5cf6] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>
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
                    <p class="text-sm text-gray-400 font-medium">Belum ada transaksi</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@include('layouts.bottom-nav')
@endsection