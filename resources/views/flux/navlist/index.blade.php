@props(['vertical' => true])

<nav {{ $attributes->merge(['class' => 'space-y-1']) }}>
    {{ $slot }}
</nav>
