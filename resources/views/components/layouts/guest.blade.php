<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('dark_mode', false) ? 'dark' : '' }}">
    <head>
        @include('partials.head')
        <script>
            // 다크 모드 상태를 즉시 적용
            if (localStorage.getItem('dark-mode') === 'true' || (!localStorage.getItem('dark-mode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        <nav class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3" wire:navigate>
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-rose-500 rounded-lg flex items-center justify-center">
                                <flux:icon.fire class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-zinc-900 dark:text-white">해시푸드</flux:heading>
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @livewire('dark-mode-toggle')
                        @auth
                            <flux:button variant="ghost" href="{{ route('dashboard') }}" wire:navigate>
                                대시보드
                            </flux:button>
                        @else
                            <flux:button variant="ghost" href="{{ route('login') }}" wire:navigate>
                                로그인
                            </flux:button>
                            <flux:button variant="primary" href="{{ route('register') }}" wire:navigate>
                                회원가입
                            </flux:button>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{ $slot }}

        @fluxScripts
        
        <script>
            // Livewire 이벤트 리스너
            document.addEventListener('livewire:init', () => {
                Livewire.on('dark-mode-changed', (isDark) => {
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('dark-mode', 'true');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('dark-mode', 'false');
                    }
                });
            });
        </script>
    </body>
</html>
