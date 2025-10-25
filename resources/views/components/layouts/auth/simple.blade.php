<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-zinc-900 antialiased">
        <!-- Header -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-gray-50/80 dark:bg-zinc-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-zinc-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-3 font-medium" wire:navigate>
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-rose-500 rounded-lg flex items-center justify-center">
                            <flux:icon.fire class="w-5 h-5 text-white" />
                        </div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">해시푸드</span>
                    </a>

                    <!-- Dark Mode Toggle -->
                    <div class="flex items-center">
                        @livewire('dark-mode-toggle')
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 pt-24">
            <div class="w-full max-w-sm">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
