<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
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
    </body>
</html>
