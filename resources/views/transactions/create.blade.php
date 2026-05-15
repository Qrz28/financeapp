@extends('layouts.app')

@section('hide-navigation', 'true')

@section('content')
<div class="gradient-bg min-h-screen relative flex flex-col">
    <!-- Header -->
    <div class="px-5 pt-12 pb-6 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-white/50 backdrop-blur-md flex items-center justify-center text-gray-700 hover:bg-white/80 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-lg font-bold text-[#1a1832]">New Transaction</h1>
        <div class="w-10"></div> <!-- Spacer -->
    </div>

    <!-- White Card Form -->
    <div class="bg-white rounded-t-[32px] pt-8 px-6 flex-1 pb-24 shadow-[0_-8px_24px_rgba(0,0,0,0.02)] relative z-10">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <!-- Tipe -->
                <div>
                    <label for="type" class="block text-[13px] font-bold text-gray-700 mb-1.5 ml-1">Type</label>
                    <select name="type" id="type" class="block w-full rounded-2xl border-gray-200 bg-[#f8f9fc] px-4 py-3.5 text-sm font-medium focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 transition-shadow" required>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Income</option>
                    </select>
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-[13px] font-bold text-gray-700 mb-1.5 ml-1">Category</label>
                    <div class="relative">
                        <select name="category" id="category" class="block w-full rounded-2xl border-gray-200 bg-[#f8f9fc] pl-11 pr-4 py-3.5 text-sm font-medium focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 transition-shadow appearance-none" required>
                            <option value="" disabled selected>Select category...</option>
                            <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>🍔 Food & Drink</option>
                            <option value="transport" {{ old('category') == 'transport' ? 'selected' : '' }}>🚗 Transport</option>
                            <option value="shopping" {{ old('category') == 'shopping' ? 'selected' : '' }}>🛍️ Shopping</option>
                            <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>💡 Utilities</option>
                            <option value="entertainment" {{ old('category') == 'entertainment' ? 'selected' : '' }}>🎬 Entertainment</option>
                            <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>💰 Salary</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>📦 Other</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h.01"/><path d="M17 7h.01"/><path d="M7 17h.01"/><path d="M17 17h.01"/></svg>
                        </div>
                    </div>
                    @error('category')
                        <div class="text-red-500 text-xs mt-1.5 ml-1 font-medium">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nominal -->
                <div>
                    <label for="amount" class="block text-[13px] font-bold text-gray-700 mb-1.5 ml-1">Amount</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">Rp</span>
                        </div>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" placeholder="0" class="block w-full rounded-2xl border-gray-200 bg-[#f8f9fc] pl-11 pr-4 py-3.5 text-lg font-bold text-gray-900 focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 transition-shadow" required>
                    </div>
                    @error('amount')
                        <div class="text-red-500 text-xs mt-1.5 ml-1 font-medium">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="transaction_date" class="block text-[13px] font-bold text-gray-700 mb-1.5 ml-1">Date</label>
                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" class="block w-full rounded-2xl border-gray-200 bg-[#f8f9fc] px-4 py-3.5 text-sm font-medium focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 transition-shadow" required>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="note" class="block text-[13px] font-bold text-gray-700 mb-1.5 ml-1">Note (Optional)</label>
                    <textarea name="note" id="note" rows="2" placeholder="What was this for?" class="block w-full rounded-2xl border-gray-200 bg-[#f8f9fc] px-4 py-3.5 text-sm font-medium focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 transition-shadow">{{ old('note') }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#8b5cf6] text-white py-4 rounded-2xl font-bold text-sm hover:bg-violet-600 transition-colors shadow-[0_8px_20px_rgba(139,92,246,0.3)]">
                        Save Transaction
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection