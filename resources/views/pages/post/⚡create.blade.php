<?php

use Livewire\Component;

new class extends Component {
    public $count = 1;

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

<div class="flex gap-4 flex-col justify-center items-center">
    <h1 class="text-3xl">{{ $count }}</h1>

    <div class="flex gap-6">
        <button wire:click="increment" class="py-1 px-5 rounded bg-green-500 font-bold">+</button>
        <button wire:click="decrement" class="py-1 px-5 rounded bg-red-500 font-bold">-</button>
    </div>
</div>
