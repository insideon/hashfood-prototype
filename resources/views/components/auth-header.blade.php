@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center gap-2">
    <flux:heading size="xl" class="text-gray-900 dark:text-white">{{ $title }}</flux:heading>
    <flux:subheading class="text-gray-600 dark:text-gray-400">{{ $description }}</flux:subheading>
</div>
