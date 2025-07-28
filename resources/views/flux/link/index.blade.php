@props(['href' => '#'])

<a {{ $attributes->merge(['class' => 'text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 underline transition duration-150 ease-in-out']) }} href="{{ $href }}">
    {{ $slot }}
</a>
