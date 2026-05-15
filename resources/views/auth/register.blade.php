<x-guest-layout>
    <div class="min-h-screen relative flex flex-col">
        <!-- Decoration Circles -->
        <div class="absolute top-[-100px] right-[-100px] w-[300px] h-[300px] bg-[#8b5cf6]/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[10%] left-[-50px] w-[200px] h-[200px] bg-[#ffedf4]/20 rounded-full blur-3xl"></div>

        <!-- Header Area -->
        <div class="px-8 pt-16 pb-10 flex flex-col items-center justify-center relative z-10">
            <div class="w-20 h-20 bg-white rounded-[24px] flex items-center justify-center shadow-2xl shadow-[#8b5cf6]/10 mb-6 animate-float border border-white">
                <div class="w-12 h-12 bg-[#8b5cf6] rounded-[16px] flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                </div>
            </div>
            <h1 class="text-[32px] font-extrabold text-[#1a1832] mb-3 tracking-tight text-center">Create Account</h1>
            <p class="text-[15px] text-gray-500 font-medium text-center px-6 leading-relaxed">Join us to start your journey towards financial freedom.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-t-[40px] pt-10 px-8 flex-1 pb-12 shadow-[0_-12px_40px_rgba(0,0,0,0.03)] relative z-10">
            <form method="POST" action="{{ route('register') }}" class="max-w-sm mx-auto">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-[14px] font-bold text-gray-700 mb-2 ml-1">Full Name</label>
                    <input id="name" class="premium-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
                    @error('name')
                        <div class="text-red-500 text-[12px] font-bold mt-2 ml-1 flex items-center gap-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-[14px] font-bold text-gray-700 mb-2 ml-1">Email Address</label>
                    <input id="email" class="premium-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com" />
                    @error('email')
                        <div class="text-red-500 text-[12px] font-bold mt-2 ml-1 flex items-center gap-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-[14px] font-bold text-gray-700 mb-2 ml-1">Password</label>
                    <input id="password" class="premium-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    @error('password')
                        <div class="text-red-500 text-[12px] font-bold mt-2 ml-1 flex items-center gap-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-[14px] font-bold text-gray-700 mb-2 ml-1">Confirm Password</label>
                    <input id="password_confirmation" class="premium-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                </div>

                <button type="submit" class="premium-button">
                    <span>Create Account</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>

                <div class="mt-8 text-center">
                    <p class="text-[14px] font-medium text-gray-500">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-[#8b5cf6] font-extrabold hover:text-[#7c3aed] ml-1.5 transition-colors">Log in here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

