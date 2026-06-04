<?php

use Livewire\Component;

new class extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
};

?>

@props(['count' => $count ?? 0])

<div class="p-6 bg-gray-900 text-white rounded-xl w-48 text-center">

    <h1 class="text-2xl font-bold mb-4">
        {{ $count }}
    </h1>

    <div class="flex justify-center gap-2">
        <button wire:click="decrement"
            class="px-3 py-1 bg-red-600 rounded">
            -
        </button>

        <button wire:click="increment"
            class="px-3 py-1 bg-green-600 rounded">
            +
        </button>
    </div>

</div>