<x-guest-layout>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(0.5deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.15; transform: scale(1); filter: blur(100px); }
            50% { opacity: 0.25; transform: scale(1.1); filter: blur(120px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-pulse-glow {
            animation: pulse-glow 8s ease-in-out infinite;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        .dark .login-card {
            background: rgba(10, 20, 24, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
    </style>

    <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6 md:p-8 bg-[#f4f7f6] dark:bg-[#070d0f] transition-colors duration-500 overflow-hidden">
        {{-- Decorative glowing backdrops --}}
        <div class="absolute inset-0 pointer-events-none z-0">
            <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-eec-cyan/15 dark:bg-eec-cyan/10 animate-pulse-glow"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[700px] h-[700px] rounded-full bg-eec-teal/15 dark:bg-eec-teal/10 animate-pulse-glow" style="animation-delay: -4s;"></div>
            
            {{-- Modern grid pattern --}}
            <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, #005a6a 1px, transparent 0); background-size: 28px 28px;"></div>
        </div>

        {{-- Theme toggle --}}
        <button type="button"
                @click="$store.theme.toggle()"
                class="absolute right-6 top-6 z-20 flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 bg-white/90 text-gray-500 shadow-md transition-all duration-300 hover:border-eec-cyan/40 hover:text-eec-cyan hover:scale-105 active:scale-95 dark:border-gray-800 dark:bg-gray-900/90 dark:text-gray-400"
                aria-label="Toggle theme">
            <svg x-show="$store.theme.mode === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
            <svg x-show="$store.theme.mode === 'dark'" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
        </button>

        {{-- Centered One-Card Container --}}
        <div class="relative z-10 w-full max-w-[460px]">
            <div class="login-card border border-gray-200/60 dark:border-gray-800/80 rounded-[3rem] p-8 sm:p-12 shadow-2xl shadow-gray-200/30 dark:shadow-none relative overflow-hidden transition-all duration-300">
                {{-- Decorative background glow inside the card --}}
                <div class="absolute -right-12 -top-12 w-32 h-32 rounded-full bg-eec-cyan/8 blur-2xl pointer-events-none"></div>
                <div class="absolute -left-12 -bottom-12 w-32 h-32 rounded-full bg-eec-teal/8 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col items-center">
                    {{-- Logo & Brand Section --}}
                    <div class="flex flex-col items-center text-center mb-10">
                        <x-eec-brand size="xl" class="animate-float" />
                        {{-- Official slogan --}}
                        <div class="mt-3 flex items-center justify-center gap-2 text-[10px] font-semibold text-gray-400 dark:text-gray-500" style="letter-spacing:.18em">
                            <span>Collaborate</span>
                            <span class="text-eec-cyan font-black">|</span>
                            <span>Innovate</span>
                            <span class="text-eec-cyan font-black">|</span>
                            <span>Deliver</span>
                        </div>
                    </div>

                    {{-- Form Title --}}
                    <div class="w-full text-center mb-8">
                        <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-snug">Plant &amp; Equipment<br><span class="text-eec-teal dark:text-eec-cyan">Management System</span></h1>
                        <p class="mt-2 text-[11px] font-semibold text-gray-400 dark:text-gray-550">Sign in to access the secure administrative portal.</p>
                    </div>

                    {{-- Notifications --}}
                    @if($errors->any())
                        <div class="w-full mb-6 rounded-2xl border border-red-200 bg-red-50/50 px-4 py-3.5 text-xs font-bold text-red-650 dark:border-red-900/30 dark:bg-red-950/20 dark:text-red-400">
                            @foreach($errors->all() as $error)
                                <p class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                                    {{ $error }}
                                </p>
                            @endforeach
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="w-full mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/50 px-4 py-3.5 text-xs font-bold text-emerald-750 dark:border-emerald-900/30 dark:bg-emerald-950/20 dark:text-emerald-350">
                            <p class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                {{ session('success') }}
                            </p>
                        </div>
                    @endif

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('login') }}" class="w-full space-y-6" id="login-form">
                        @csrf

                        {{-- Email Input --}}
                        <div class="space-y-2">
                            <label for="email" class="text-[10px] font-black text-gray-450 dark:text-gray-450 uppercase tracking-widest block">Corporate Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-eec-cyan transition-colors duration-250">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                       class="block w-full rounded-2xl border-0 bg-gray-50/60 dark:bg-gray-900/40 pl-11 pr-4 py-3.5 text-sm text-gray-900 shadow-inner ring-1 ring-gray-200/90 dark:ring-gray-800/80 placeholder:text-gray-400 dark:placeholder:text-gray-650 transition-all duration-300 focus:bg-white dark:focus:bg-gray-950 focus:outline-none focus:ring-2 focus:ring-eec-cyan/60 dark:text-white dark:focus:ring-eec-cyan/50 {{ $errors->has('email') ? '!ring-red-400' : '' }}"
                                       placeholder="user@eec.com">
                            </div>
                        </div>

                        {{-- Password Input --}}
                        <div class="space-y-2" x-data="{ showPassword: false }">
                            <label for="password" class="text-[10px] font-black text-gray-450 dark:text-gray-450 uppercase tracking-widest block">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-eec-cyan transition-colors duration-250">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                </div>
                                <input x-bind:type="showPassword ? 'text' : 'password'" name="password" id="password" required
                                       class="block w-full rounded-2xl border-0 bg-gray-50/60 dark:bg-gray-900/40 pl-11 pr-12 py-3.5 text-sm text-gray-900 shadow-inner ring-1 ring-gray-200/90 dark:ring-gray-800/80 placeholder:text-gray-400 dark:placeholder:text-gray-650 transition-all duration-300 focus:bg-white dark:focus:bg-gray-950 focus:outline-none focus:ring-2 focus:ring-eec-cyan/60 dark:text-white dark:focus:ring-eec-cyan/50 {{ $errors->has('password') ? '!ring-red-400' : '' }}"
                                       placeholder="••••••••">
                                <button type="button" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 transition-colors hover:text-eec-cyan" @click="showPassword = !showPassword" tabindex="-1">
                                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Keep me signed in --}}
                        <div class="flex items-center">
                            <label class="flex cursor-pointer select-none items-center gap-2.5 group">
                                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded-lg border-gray-300 dark:border-gray-800 bg-gray-50 text-eec-cyan focus:ring-eec-cyan/20 dark:bg-gray-900">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 group-hover:text-gray-750 dark:group-hover:text-gray-300 transition-colors">Keep me signed in</span>
                            </label>
                        </div>

                        @if(config('services.turnstile.site_key'))
                            <div class="flex justify-center pt-2">
                                <div class="cf-turnstile"
                                     data-sitekey="{{ config('services.turnstile.site_key') }}"
                                     data-theme="auto"
                                     data-callback="onTurnstileSuccess"
                                     data-expired-callback="onTurnstileExpired"></div>
                            </div>
                            <input type="hidden" name="turnstile_token" id="turnstile_token">
                        @endif

                        {{-- Submit Button --}}
                        <button type="submit"
                                class="w-full rounded-2xl bg-gradient-to-r from-eec-teal to-eec-cyan py-4 text-xs font-black uppercase tracking-wider text-white shadow-xl shadow-eec-teal/20 transition-all duration-300 hover:shadow-eec-cyan/35 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-eec-cyan/40 focus:ring-offset-2 dark:focus:ring-offset-[#070d0f]">
                            Authenticate
                        </button>
                    </form>
                </div>
            </div>

            <p class="mt-8 text-center text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-600">
                EEC Plant &amp; Equipment Ledger &middot; Secure System
            </p>
        </div>
    </div>

    @if(config('services.turnstile.site_key'))
        <script>
            function onTurnstileSuccess(token) {
                document.getElementById('turnstile_token').value = token;
            }

            function onTurnstileExpired() {
                document.getElementById('turnstile_token').value = '';
            }

            document.getElementById('login-form').addEventListener('submit', function (e) {
                if (window.turnstile && !document.getElementById('turnstile_token').value) {
                    e.preventDefault();
                    alert('Please complete the security check.');
                }
            });
        </script>
    @endif
</x-guest-layout>
