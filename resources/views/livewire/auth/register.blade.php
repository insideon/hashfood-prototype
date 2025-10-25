<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="회원가입" description="아래 정보를 입력하여 계정을 만드세요" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                label="이름"
                type="text"
                required
                autofocus
                autocomplete="name"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                label="이메일"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                label="비밀번호"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                label="비밀번호 확인"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    계정 만들기
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>이미 계정이 있으신가요?</span>
            <flux:link :href="route('login')" wire:navigate class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium">로그인</flux:link>
        </div>
    </div>
</x-layouts.auth>
