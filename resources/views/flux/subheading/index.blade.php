@props(['size' => 'base'])

@php
    $classes = match($size) {
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl',
        default => 'text-base',
    };
@endphp

<p {{ $attributes->merge(['class' => "text-gray-600 dark:text-gray-400 {$classes}"]) }}>
    {{ $slot }}
</p>
