<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="로그인" description="이메일과 비밀번호를 입력해주세요" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                label="이메일"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    label="비밀번호"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="비밀번호를 입력하세요"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        비밀번호를 잊으셨나요?
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" label="로그인 상태 유지" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    로그인
                </flux:button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-zinc-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white dark:bg-zinc-800 text-gray-500 dark:text-gray-400">또는</span>
            </div>
        </div>

        <!-- Social Login -->
        <div class="flex flex-col gap-3">
            <!-- Google -->
            <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-black dark:text-black bg-white dark:bg-white hover:bg-gray-50 dark:hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                <span>Google로 계속하기</span>
            </button>

            <!-- Kakao -->
            <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 bg-[#FEE500] hover:bg-[#FDD835] dark:bg-[#FEE500] dark:hover:bg-[#FDD835] border border-[#FEE500] dark:border-[#FDD835] rounded-lg text-sm font-medium text-[#000000] dark:text-[#000000] transition-colors">
                <svg class="w-6 h-6" viewBox="0 0 24 24">
                    <path fill="#3C1E1E" d="M12 3C6.5 3 2 6.4 2 10.5c0 2.6 1.7 4.9 4.3 6.3l-1.1 4c-.1.4.1.4.3.3l4.7-3.1c.8.1 1.6.2 2.4.2 5.5 0 10-3.4 10-7.5S17.5 3 12 3z"/>
                </svg>
                <span>카카오로 계속하기</span>
            </button>

            <!-- Naver -->
            <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 bg-[#03C75A] hover:bg-[#02B350] dark:bg-[#03C75A] dark:hover:bg-[#02B350] border border-[#03C75A] dark:border-[#02B350] rounded-lg text-sm font-medium text-white dark:text-white transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#FFFFFF" d="M16.273 12.845L7.376 0H0v24h7.727V11.155L16.624 24H24V0h-7.727v12.845z"/>
                </svg>
                <span>네이버로 계속하기</span>
            </button>
        </div>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>계정이 없으신가요?</span>
                <flux:link :href="route('register')" wire:navigate class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium">회원가입</flux:link>
            </div>
        @endif
    </div>
</x-layouts.auth>
