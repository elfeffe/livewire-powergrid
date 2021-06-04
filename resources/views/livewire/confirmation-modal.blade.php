<x-modal>
    <x-slot name="content">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <x-heroicon-o-exclamation
                    class="w-7 h-7"
                />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <div class="mt-2">
                    {{ $content }}
                </div>
                <span wire:loading="" class="mr-4">
          Executing action
        </span>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <button wire:click="$emit('closeModal')" class="py-2 px-4 rounded transition duration-200 ease-in-out focus:outline-none  hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 active:bg-gray-100 active:text-gray-900 focus:outline-none active:outline-none">
            {{ __('Cancel') }}
        </button>
        <button wire:click="update" class="py-2 px-4 rounded transition duration-200 ease-in-out focus:outline-none text-white bg-red-600 hover:bg-red-500 focus:bg-red-500 active:bg-red-500 focus:outline-none active:outline-none">
            {{ __('Confirm') }}
        </button>
    </x-slot>
</x-modal>
