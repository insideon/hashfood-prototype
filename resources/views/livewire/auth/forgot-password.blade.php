<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="비밀번호 찾기" description="가입하신 이메일 주소를 입력하시면 비밀번호 재설정 링크를 보내드립니다" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                label="이메일"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                비밀번호 재설정 이메일 보내기
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>또는</span>
            <flux:link :href="route('login')" wire:navigate class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium">로그인으로 돌아가기</flux:link>
        </div>
    </div>
</x-layouts.auth>
