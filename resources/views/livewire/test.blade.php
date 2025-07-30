<?php

use Livewire\Volt\Component;

new class extends Component {
    public $message = 'Hello from Volt!';
    
    public function changeMessage()
    {
        $this->message = 'Button clicked!';
    }
}; ?>

<div>
    <h1>{{ $message }}</h1>
    <button wire:click="changeMessage" class="px-4 py-2 bg-blue-500 text-white rounded">
        Click me
    </button>
</div>
