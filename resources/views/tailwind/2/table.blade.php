<div class="relative flex flex-col" @if(!$load) wire:init="load" @endif>
    <div class="absolute top-0 left-0 w-full z-20 bg-gray-300 opacity-25 h-full" wire:loading ></div>
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full w-full sm:px-6 lg:px-8">

            @include('livewire-powergrid::tailwind.2.header')

            @if(config('livewire-powergrid.filter') === 'outside')
                @if(count($make_filters) > 0)
                    <div>
                        @include('livewire-powergrid::tailwind.2.filter')
                    </div>
                @endif
            @endif

            <div
                class="shadow border-b border-gray-200 sm:rounded-lg overflow-x-auto bg-white rounded-lg shadow overflow-auto relative">
                <table class="min-w-full divide-y divide-gray-200  overflow-y-auto whitespace-nowrap">
                    <thead class="bg-gray-50">
                    <tr>
                        @include('livewire-powergrid::tailwind.2.checkbox-all')

                        @foreach($columns as $column)
                            @if($column->show())
                                <th
                                    id="th_{{ $column->field }}"
                                    class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap leading-4 font-semibold"
                                    style="@if($column->sortable)cursor:pointer; @endif {{ ($column->header_style != '') ? $column->header_style:'' }}"
                                >
                                    <div class="align-content-between">
                                        @if($column->sortable === true)
                                            <span class="text-base pr-2">
                                                @if ($orderBy !== $column->field)
                                                    {!! $sortIcon !!}
                                                @elseif ($orderAsc)
                                                    {!! $sortAscIcon !!}
                                                @else
                                                    {!! $sortDescIcon !!}
                                                @endif
                                            </span>
                                        @endif
                                        <span
                                            @if($column->sortable === true) wire:click="setOrder('{{$column->field}}')" @endif>
                                            {{$column->title}}
                                        </span>
                                        @include('livewire-powergrid::tailwind.2.clear_filter')
                                    </div>
                                </th>
                            @endif
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="text-gray-800">

                    @include('livewire-powergrid::tailwind.2.inline-filter')

                    @if(empty($data))
                        <tr class="border-b border-gray-200 hover:bg-gray-100 text-sm">
                            <td class="text-center p-2" colspan="{{ (($checkbox) ? 1:0)
                        + ((isset($actionBtns)) ? 1: 0)
                        + (count($columns))
                        }}">
                                @if(!$load)
                                    <div class="animate-pulse flex space-x-4">
                                        <div class="flex-1 space-y-4 py-1">
                                            @for ($k = $perPage ; $k > 0; $k--)
                                                <div class="h-10 bg-gray-200"></div>
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <span>{{ trans('livewire-powergrid::datatable.labels.no_data') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @foreach($data as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 text-sm" wire:key="{{ 'tr_' . $row->id . time() }}">

                            @include('livewire-powergrid::tailwind.2.checkbox-row')

                            @foreach($columns as $column)

                                @php
                                    $field = $column->field;
                                @endphp

                                @if($column->show())
                                    <td class="px-3 py-2 {{ ($column->body_class != '') ? $column->body_class : '' }}"
                                        style="@if($column->width) max-width: {{ $column->width }}px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            white-space: nowrap;
                                        @endif
                                        {{ ($column->body_style != '') ? $column->body_style : '' }}"
                                        id="td_{{ $column->field }}"
                                    >
                                        <div class="flex justify-between">

                                            @if(!$column->editable)
                                                @if($column->badge)
                                                        @UIBadge($row->status, $row->badgeType())
                                                @else
                                                    <div>
                                                        @if(!$column->html)
                                                            {!! $row->$field !!}
                                                        @else
                                                            {{ $row->$field }}
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif

                                            @if($column->editable)
                                                <div class="flex flex-row space-x-2 @if(!$column->actions) w-full @endif">
                                                    @if($column->actions)
                                                        @foreach($column->actions as $action)
                                                            @if($action->renderIf($row))

                                                                <livewire:dynamic-component
                                                                    :component="$action->ui->component"
                                                                    :model="$row"
                                                                    :route="$action->ui->route"
                                                                    :actionClass="get_class($action)"
                                                                    :key="$action->ui->component . $row->id . $loop->index . time()"
                                                                />
                                                            @endif

                                                        @endforeach
                                                    @else
                                                        @include('livewire-powergrid::tailwind.2.components.editable')
                                                    @endif
                                                </div>
                                            @endif

                                            @if($column->click_to_copy)
                                                <div
                                                    x-data="{ input: 'deeee' }"
                                                    class="w-6 h-6 bg-blue-200"
                                                    @click="$clipboard(input)"
                                                    title="{{ __('Copy') }}"></div>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="p-5 flex flex-row w-full flex justify-between pt-3 bg-white overflow-y-auto relative">
        @if($perPage_input)
            <div class="flex flex-row">
                <div class="relative h-10">
                    <select wire:model="perPage"
                            class="block appearance-none bg-white-200 border border-gray-300 text-gray-700  py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-state">
                        @foreach($perPageValues as $value)
                            <option value="{{$value}}"> @if($value == 0)
                                    {{ trans('livewire-powergrid::datatable.labels.all') }} @else {{ $value }} @endif</option>
                        @endforeach
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    </div>
                </div>
                <div class="pl-4 hidden sm:block md:block lg:block w-full"
                     style="padding-top: 6px;">{{ trans('livewire-powergrid::datatable.labels.results_per_page') }}</div>
            </div>
        @endif

        @if(!is_array($data))
            <div class="">
                @if(method_exists($data, 'links'))
                    {!! $data->links('livewire-powergrid::tailwind.2.pagination', ['record_count' => $record_count]) !!}
                @endif
            </div>
        @endif
    </div>
</div>
