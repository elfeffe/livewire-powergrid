@if(filled($select))
    <div wire:ignore class="@if($inline) px-3 @endif{!! ($select['class'] != '') ?? '' !!} pt-2 p-2 text-sm">
        @if(!$inline)
            <label for="input_{!! $select['relation_id'] !!}">{{$select['label']}}</label>
        @endif
        <div class="relative text-sm">
            <select id="input_{!! $select['relation_id'] !!}"
                    class="text-sm appearance-none livewire_powergrid_input block appearance-no mt-1 mb-1 bg-white-200 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 w-full active
         {{ (isset($class)) ? $class : 'w-9/12' }}"
                    wire:model="filters.select.{!! $select['relation_id'] !!}"
                    wire:ignore
                    data-live-search="{{ $select['live-search'] }}">
                <option value="">{{ trans('livewire-powergrid::datatable.select.all') }}<</option>
                @foreach($select['data_source'] as $relation)
                    <option value="{{ $relation['id'] }}">{{ $relation[$select['display_field']] }}</option>
                @endforeach
            </select>
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            </div>
        </div>
    </div>
@endif
