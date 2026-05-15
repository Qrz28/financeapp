<x-guest-layout>
    <div class="min-h-screen relative flex flex-col">
        <!-- Decoration Circles -->
        <div class="absolute top-[-100px] right-[-100px] w-[300px] h-[300px] bg-[#8b5cf6]/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[10%] left-[-50px] w-[200px] h-[200px] bg-[#ffedf4]/20 rounded-full blur-3xl"></div>

        <!-- Header Area -->
        <div class="px-8 pt-20 pb-12 flex flex-col items-center justify-center relative z-10">
            <div class="w-20 h-20 bg-white rounded-[24px] flex items-center justify-center shadow-2xl shadow-[#8b5cf6]/10 mb-8 animate-float border border-white">
                <div class="w-12 h-12 bg-[#8b5cf6] rounded-[16px] flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m19 19-7 3-7-3"/><path d="M2 12h20"/></svg>
                </div>
            </div>
            <h1 class="text-[32px] font-extrabold text-[#1a1832] mb-3 tracking-tight text-center">Welcome Back</h1>
            <p class="text-[15px] text-gray-500 font-medium text-center px-6 leading-relaxed">Log in to manage your finances with confidence and ease.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-t-[40px] pt-12 px-8 flex-1 pb-12 shadow-[0_-12px_40px_rgba(0,0,0,0.03)] relative z-10">
            <form method="POST" action="{{ route('login') }}" class="max-w-sm mx-auto">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-[14px] font-bold text-gray-700 mb-2.5 ml-1">Email Address</label>
                    <input id="email" class="premium-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" />
                    @error('email')
                        <div class="text-red-500 text-[12px] font-bold mt-2 ml-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-[14px] font-bold text-gray-700 mb-2.5 ml-1">Password</label>
                    <input id="password" class="premium-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    @error('password')
                        <div class="text-red-500 text-[12px] font-bold mt-2 ml-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-10 px-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <div class="relative flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-[#8b5cf6] shadow-sm focus:ring-[#8b5cf6] w-5 h-5 transition-all" name="remember">
                        </div>
                        <span class="ml-3 text-[14px] font-semibold text-gray-600 group-hover:text-gray-900 transition-colors">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-[14px] font-bold text-[#8b5cf6] hover:text-[#7c3aed] transition-colors underline-offset-4 hover:underline" href="{{ route('password.request') }}">
                            Forgot?
                        </a>
                    @endif
                </div>

                <button type="submit" class="premium-button">
                    <span>Log In</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>

                <div class="mt-10 text-center">
                    @if (Route::has('register'))
                        <p class="text-[14px] font-medium text-gray-500">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-[#8b5cf6] font-extrabold hover:text-[#7c3aed] ml-1.5 transition-colors">Register now</a>
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

