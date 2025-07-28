@props(['size' => 'base'])

@php
    $classes = match($size) {
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl',
        '3xl' => 'text-3xl',
        '4xl' => 'text-4xl',
        default => 'text-base',
    };
@endphp

<p {{ $attributes->merge(['class' => "text-gray-700 dark:text-gray-300 {$classes}"]) }}>
    {{ $slot }}
</p>
