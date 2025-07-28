@props(['name'])

<button x-data x-on:click="$dispatch('open-modal', '{{ $name }}')" {{ $attributes }}>
    {{ $slot }}
</button>
