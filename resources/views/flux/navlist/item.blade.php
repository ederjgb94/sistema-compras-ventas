@props(['href' => '#', 'active' => false])

@php
$classes = ($active ?? request()->url() === $href)
            ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200';
@endphp

<a
    {{ $attributes->merge(['class' => 'flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-colors ' . $classes]) }}
    href="{{ $href }}"
>
    {{ $slot }}
</a>
