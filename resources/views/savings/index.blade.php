@extends('layouts.app')

@section('hide-navigation', 'true')
@section('full-width', 'true')

@section('content')
<div class="flex h-screen w-full bg-[#f4f6fc] overflow-hidden font-sans">
    <!-- Desktop Left Side (Analytics & Large Glassmorphic Jars) -->
    <div class="hidden lg:flex flex-col flex-1 p-10 overflow-y-auto">
        <!-- Header -->
        <div class="mb-10 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-[#1a1832] mb-2">Celengan Virtual</h1>
                <p class="text-gray-500">Lihat progres tabungan Anda dalam bentuk visual toples kaca interaktif.</p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                <span class="text-xs font-semibold text-gray-400">Total Saldo Dompet</span>
                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($balance, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Glass Jars Grid -->
        <div class="grid grid-cols-3 gap-8">
            @forelse($activeGoals as $goal)
                @php
                    $percentage = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0;
                    $colorMap = [
                        'purple' => 'from-purple-500 to-indigo-400',
                        'blue' => 'from-blue-500 to-cyan-400',
                        'green' => 'from-emerald-500 to-teal-400',
                        'amber' => 'from-amber-500 to-orange-400',
                        'rose' => 'from-rose-500 to-pink-400',
                        'emerald' => 'from-emerald-600 to-green-400',
                        'cyan' => 'from-cyan-500 to-blue-300'
                    ];
                    $fillColor = $colorMap[$goal->color] ?? 'from-purple-500 to-indigo-400';
                @endphp
                <div class="bg-white rounded-[32px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.04)] flex flex-col items-center">
                    <!-- Toples Jar Visual -->
                    <div class="relative w-40 h-56 mb-6">
                        <!-- Jar Lid -->
                        <div class="w-20 h-4 bg-amber-800/90 rounded-t-md mx-auto shadow-sm relative z-20"></div>
                        <div class="w-24 h-2 bg-amber-900 rounded-full mx-auto relative z-20 -mt-1 shadow-sm"></div>
                        
                        <!-- Jar Body (Glassmorphism) -->
                        <div class="absolute inset-x-2 top-4 bottom-0 bg-white/20 border-2 border-white/60 rounded-b-[48px] rounded-t-[12px] shadow-[inset_0_4px_12px_rgba(255,255,255,0.4),0_8px_24px_rgba(0,0,0,0.04)] overflow-hidden backdrop-blur-[2px] z-10">
                            <!-- Water Fill -->
                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t {{ $fillColor }} transition-all duration-1000 ease-out" style="height: {{ $percentage }}%">
                                <!-- Wave Effect -->
                                <div class="absolute top-0 inset-x-0 h-3 bg-white/20 overflow-hidden animate-pulse"></div>
                            </div>
                            
                            <!-- Reflection Highlight -->
                            <div class="absolute top-2 left-3 w-3 bottom-8 bg-white/30 rounded-full blur-[1px]"></div>
                        </div>

                        <!-- Content inside Jar (floating overlay) -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center z-20 pt-6">
                            <span class="text-4xl filter drop-shadow-sm mb-2 transform hover:scale-110 transition-transform">{{ $goal->icon }}</span>
                            <span class="text-2xl font-black text-gray-900 drop-shadow-sm">{{ number_format($percentage, 0) }}%</span>
                        </div>
                    </div>

                    <!-- Target Info -->
                    <div class="text-center w-full">
                        <h3 class="font-bold text-gray-800 text-lg mb-1 truncate">{{ $goal->name }}</h3>
                        <p class="text-xs text-gray-400 font-medium mb-3">
                            Target: Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
                        </p>
                        <div class="bg-[#f8f9fc] rounded-2xl py-2 px-4 inline-flex flex-col items-center">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Terkumpul</span>
                            <span class="text-sm font-extrabold text-gray-800">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white/80 rounded-[32px] p-12 text-center shadow-[0_8px_30px_rgba(0,0,0,0.02)] border border-white/50">
                    <p class="text-gray-400 font-medium text-lg mb-2">Belum ada toples celengan aktif.</p>
                    <p class="text-sm text-gray-400">Gunakan aplikasi mobile di sebelah kanan untuk membuat celengan pertama Anda!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Mobile View (Right Side) -->
    <div class="w-full lg:w-[420px] bg-white h-screen overflow-y-auto relative shadow-[-10px_0_30px_rgba(0,0,0,0.05)] flex-shrink-0 scrollbar-hide">
        <div class="min-h-screen relative flex flex-col font-sans bg-[#fbfbfe]">
            
            <!-- Header (Mobile) -->
            <div class="px-5 pt-12 pb-6 bg-[#fbfbfe]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-extrabold text-[#1a1832]">Target Saya</h2>
                    <button onclick="toggleModal('createModal')" class="bg-[#8b5cf6] text-white p-2 rounded-xl hover:bg-violet-600 transition-colors shadow-sm flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>

                <!-- Wallet Card (Mobile) -->
                <div class="bg-[#1a1832] rounded-[24px] p-5 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full"></div>
                    <p class="text-xs text-gray-400 mb-1 font-medium">Saldo Aktif Dompet</p>
                    <h3 class="text-3xl font-extrabold mb-1">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-gray-500 font-medium">* Menabung akan mengurangi saldo ini.</p>
                </div>
            </div>

            <!-- List Celengan (Mobile) -->
            <div class="px-5 flex-1 pb-28">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 text-green-700 p-3 rounded-2xl text-xs font-semibold border border-green-100">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 bg-red-50 text-red-700 p-3 rounded-2xl text-xs font-semibold border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <h4 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mb-4">CELENGAN AKTIF</h4>
                <div class="space-y-4">
                    @forelse($activeGoals as $goal)
                        @php
                            $pct = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0;
                            $themeColors = [
                                'purple' => 'bg-purple-500', 'blue' => 'bg-blue-500', 'green' => 'bg-emerald-500',
                                'amber' => 'bg-amber-500', 'rose' => 'bg-rose-500', 'emerald' => 'bg-emerald-600',
                                'cyan' => 'bg-cyan-500'
                            ];
                            $themeColor = $themeColors[$goal->color] ?? 'bg-purple-500';
                        @endphp
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-xl shadow-sm">
                                        {{ $goal->icon }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800">{{ $goal->name }}</h5>
                                        <p class="text-[11px] text-gray-400 font-medium">Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-extrabold text-gray-700">{{ number_format($pct, 0) }}%</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full h-2 bg-gray-100 rounded-full mb-4 overflow-hidden">
                                <div class="h-full {{ $themeColor }} rounded-full" style="width: {{ $pct }}%"></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                <button onclick="openTransactionModal('add', {{ $goal->id }}, '{{ $goal->name }}')" class="flex-1 bg-violet-50 text-[#8b5cf6] text-xs font-bold py-2 rounded-xl hover:bg-violet-100 transition-colors">
                                    + Tabung
                                </button>
                                <button onclick="openTransactionModal('withdraw', {{ $goal->id }}, '{{ $goal->name }}')" class="flex-1 bg-amber-50 text-amber-600 text-xs font-bold py-2 rounded-xl hover:bg-amber-100 transition-colors" {{ $goal->current_amount <= 0 ? 'disabled style=opacity:0.5;' : '' }}>
                                    - Cairkan
                                </button>
                                <form action="{{ route('savings.destroy', $goal) }}" method="POST" class="inline" onsubmit="return confirm('Hapus celengan ini? Saldo tersisa otomatis kembali ke saldo utama.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-500 p-2 rounded-xl hover:bg-red-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-xs text-gray-400 font-medium">Belum ada celengan aktif. Buat sekarang!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Celengan Selesai -->
                @if($completedGoals->count() > 0)
                    <h4 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mt-8 mb-4">TARGET SELESAI 🎉</h4>
                    <div class="space-y-4">
                        @foreach($completedGoals as $goal)
                            <div class="bg-emerald-50/50 rounded-2xl p-4 border border-emerald-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-xl">
                                        {{ $goal->icon }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-[#065f46] line-through">{{ $goal->name }}</h5>
                                        <p class="text-[10px] text-emerald-600 font-bold">Lunas: Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('savings.destroy', $goal) }}" method="POST" class="inline" onsubmit="return confirm('Hapus catatan target selesai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-emerald-600 hover:text-red-500 transition-colors p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Bottom Nav Mobile Wrapper -->
            <div class="absolute bottom-0 left-0 right-0">
                @include('layouts.bottom-nav')
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Savings Goal -->
<div id="createModal" class="fixed inset-0 bg-black/55 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 shadow-2xl relative animate-in fade-in zoom-in duration-200">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Buat Target Baru</h3>
        <form action="{{ route('savings.store') }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Target</label>
                    <input type="text" name="name" required placeholder="Contoh: Beli Laptop Baru" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:bg-white transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Target Nominal</label>
                        <input type="number" name="target_amount" min="0.01" step="any" required placeholder="Rp" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tenggat Waktu</label>
                        <input type="date" name="target_date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:bg-white transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Emoji / Ikon</label>
                        <input type="text" name="icon" value="🎯" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-center focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Pilih Warna</label>
                        <select name="color" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:bg-white transition-all">
                            <option value="purple">Ungu</option>
                            <option value="blue">Biru</option>
                            <option value="green">Hijau</option>
                            <option value="amber">Amber</option>
                            <option value="rose">Pink</option>
                            <option value="emerald">Zamrud</option>
                            <option value="cyan">Sian</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleModal('createModal')" class="flex-1 py-2.5 border border-gray-100 text-gray-500 rounded-xl text-xs font-bold hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-[#8b5cf6] text-white rounded-xl text-xs font-bold hover:bg-violet-600 transition-colors">
                    Simpan Target
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Fund Transactions (Add/Withdraw) -->
<div id="fundModal" class="fixed inset-0 bg-black/55 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 shadow-2xl relative animate-in fade-in zoom-in duration-200">
        <h3 id="fundModalTitle" class="text-lg font-bold text-gray-900 mb-2">Isi Tabungan</h3>
        <p id="fundModalSubtitle" class="text-xs text-gray-400 font-medium mb-4">Target: Laptop Baru</p>
        
        <form id="fundForm" method="POST" action="">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nominal Uang</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-400 font-bold text-sm">Rp</span>
                    <input type="number" id="fundAmount" name="amount" min="0.01" step="any" required placeholder="0" class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:bg-white transition-all font-bold">
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleModal('fundModal')" class="flex-1 py-2.5 border border-gray-100 text-gray-500 rounded-xl text-xs font-bold hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" id="fundSubmitBtn" class="flex-1 py-2.5 bg-[#8b5cf6] text-white rounded-xl text-xs font-bold hover:bg-violet-600 transition-colors">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Coin Animation Overlay (Desktop only) -->
<div id="coinRainContainer" class="fixed inset-0 pointer-events-none z-50 hidden"></div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    function openTransactionModal(action, goalId, goalName) {
        const title = document.getElementById('fundModalTitle');
        const subtitle = document.getElementById('fundModalSubtitle');
        const form = document.getElementById('fundForm');
        const btn = document.getElementById('fundSubmitBtn');
        const amountInput = document.getElementById('fundAmount');

        amountInput.value = '';

        if (action === 'add') {
            title.innerText = '🎯 Isi Celengan';
            subtitle.innerText = 'Target: ' + goalName;
            form.action = `/savings/${goalId}/add`;
            btn.innerText = 'Masukkan Tabungan';
            btn.className = 'flex-1 py-2.5 bg-[#8b5cf6] text-white rounded-xl text-xs font-bold hover:bg-violet-600 transition-colors';
        } else {
            title.innerText = '💸 Cairkan Celengan';
            subtitle.innerText = 'Ambil dana dari: ' + goalName;
            form.action = `/savings/${goalId}/withdraw`;
            btn.innerText = 'Cairkan Uang';
            btn.className = 'flex-1 py-2.5 bg-amber-500 text-white rounded-xl text-xs font-bold hover:bg-amber-600 transition-colors';
        }

        toggleModal('fundModal');
    }

    // Trigger Coin Rain Animation on Desktop side if menabung is successful
    @if(session('savings_success'))
        document.addEventListener('DOMContentLoaded', function() {
            triggerCoinRain();
        });
    @endif

    function triggerCoinRain() {
        const container = document.getElementById('coinRainContainer');
        if (!container) return;
        
        container.innerHTML = '';
        container.classList.remove('hidden');

        // Target only the desktop half (left 60%) to prevent blocking user actions
        const totalCoins = 30;
        const width = window.innerWidth * 0.6; 
        
        for (let i = 0; i < totalCoins; i++) {
            const coin = document.createElement('div');
            coin.innerText = '🪙';
            coin.style.position = 'absolute';
            coin.style.left = Math.random() * width + 'px';
            coin.style.top = '-20px';
            coin.style.fontSize = Math.random() * (24 - 14) + 14 + 'px';
            coin.style.opacity = Math.random() * (1 - 0.7) + 0.7;
            coin.style.transition = 'transform 2s linear, opacity 2s ease-out';
            
            container.appendChild(coin);

            // Animate after a frame
            setTimeout(() => {
                const targetY = window.innerHeight + 50;
                const rotate = Math.random() * 720;
                coin.style.transform = `translateY(${targetY}px) rotate(${rotate}deg)`;
                coin.style.opacity = '0';
            }, 50);
        }

        // Clean up
        setTimeout(() => {
            container.classList.add('hidden');
        }, 2200);
    }
</script>
@endsection
