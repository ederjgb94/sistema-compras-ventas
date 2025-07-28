@props(['size' => 'lg', 'level' => '2'])

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
        default => 'text-lg',
    };
@endphp

@switch($level)
    @case('1')
        <h1 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h1>
        @break
    @case('2')
        <h2 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h2>
        @break
    @case('3')
        <h3 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h3>
        @break
    @case('4')
        <h4 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h4>
        @break
    @case('5')
        <h5 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h5>
        @break
    @case('6')
        <h6 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h6>
        @break
    @default
        <h2 {{ $attributes->merge(['class' => "font-bold text-gray-900 dark:text-gray-100 {$classes}"]) }}>{{ $slot }}</h2>
@endswitch
