@props(['variant' => 'primary', 'type' => 'button'])

@php
    $classes = match($variant) {
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:bg-indigo-700',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:bg-red-700',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:bg-green-700',
        default => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:bg-indigo-700',
    };
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {$classes}"]) }}
>
    {{ $slot }}
</button>
