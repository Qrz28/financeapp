<!-- Bottom Navigation -->
<div class="absolute bottom-0 left-0 w-full flex justify-center pointer-events-none z-50">
    <div class="w-full max-w-md pointer-events-auto">
        <div class="bg-white px-8 py-3 flex items-center justify-between relative shadow-[0_-4px_24px_rgba(0,0,0,0.03)] border-t border-gray-50">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'text-[#8b5cf6]' : 'text-gray-400 hover:text-gray-600' }} w-16">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span class="text-[11px] font-bold">Home</span>
            </a>

            <div class="relative -top-8">
                <a href="{{ route('transactions.create') }}" class="w-[60px] h-[60px] rounded-full bg-[#8b5cf6] fab-shadow flex items-center justify-center text-white hover:bg-violet-600 transition-colors border-4 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                </a>
            </div>

            <a href="{{ route('transactions.index') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('transactions.index') ? 'text-[#8b5cf6]' : 'text-gray-400 hover:text-gray-600' }} w-16">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                <span class="text-[11px] font-bold">Report</span>
            </a>
        </div>
    </div>
</div>
