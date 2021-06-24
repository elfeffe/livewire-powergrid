<div
    wire:replace
    x-on:click.away="editing = false; $refs.input.value = original"
    x-data="{
    editing:false,
    field: '{{ $field }}',
    id: {{ $row->id }},
    original: '{{ $row->$field }}'
     }"
    class="w-full">
    <input
        x-cloak
        x-ref="input"
        x-show="editing"
        value="{{ $row->$field }}"
        @keydown.enter="Livewire.emit('powergrid:eventChangeInput', {
            id: id,
            value: $event.target.value,
            field: field
        })"
        @keydown.escape="editing = false; $refs.input.value = original"
        class="bg-green-100 text-black-700 border border-green-200 rounded py-2 px-4 leading-tight outline-none w-full"
    >
    <span
        x-on:click="editing = true; $nextTick(() => {$refs.input.focus(); $refs.input.setSelectionRange(-1, -1);});"
        x-show="!editing"
        class='cursor-pointer border-b-2 border-gray-400 border-dotted'>{{ $row->$field }}</span>
</div>
