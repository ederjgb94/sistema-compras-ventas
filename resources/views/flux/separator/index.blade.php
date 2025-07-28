@props(['variant' => 'default'])

@php
    $classes = match($variant) {
        'default' => 'border-gray-300 dark:border-gray-700',
        'subtle' => 'border-gray-200 dark:border-gray-800',
        'primary' => 'border-indigo-500 dark:border-indigo-700',
        default => 'border-gray-300 dark:border-gray-700',
    };
@endphp

<hr {{ $attributes->merge(['class' => "border-t {$classes} my-4"]) }} />
