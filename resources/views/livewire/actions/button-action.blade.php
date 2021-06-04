<div>
    <button
        class="cursor-pointer flex hover:text-blue-600 transition-all duration-300 ease-in-out focus:text-blue-600 active:text-blue-600 focus:outline-none active:outline-none"
        title="{{ $title }}"
        wire:click="executeAction()"
    >
        {{ svg('heroicon-o-' . $icon, ['class' => 'w-6 h-6']) }}
    </button>
</div>
