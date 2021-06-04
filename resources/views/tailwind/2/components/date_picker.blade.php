@if(filled($date))
    @php
        $customConfig = [];
        if (isset($date['config'])) {
            foreach ($date['config'] as $key => $value) {
                $customConfig[$key] = $value;
            }
        }
    @endphp
    @if(!$inline)
        <div class="text-sm {!! ($date['class'] != '') ?? '' !!} pt-2 p-2" @if($inline) style="max-width: 370px;" @endif>
            @else
                <div class="text-sm" wire:ignore>
                    @endif

                    @if(!$inline)
                        <label for="input_{!! $date['field'] !!}">{!! $date['label'] !!}</label>
                    @endif
                    <input id="input_{!! $date['field'] !!}"
                           data-key="filters_enabled.date_picker.{!! $date['data_field'] !!}"
                           wire:model.debounce.800ms="filters_enabled.{!! $date['data_field'] !!}"
                           wire:ignore
                           class="text-sm range_input_{!! $date['field'] !!} livewire_powergrid_input flatpickr flatpickr-input range_input_payment block appearance-no my-1 bg-white-200 border border-gray-300 text-gray-700 py-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 active
                       {{ (isset($class) != '') ? $class : 'w-32' }}
                               "
                           type="text"
                           placeholder="{{ trans('livewire-powergrid::datatable.placeholders.select') }}"
                    >
                </div>
            @push('powergrid_scripts')
                <!-- Power Grid Date Picker Scripts -->
                    <script type="application/javascript">
                        flatpickr(document.getElementsByClassName('range_input_{!! $date['field'] !!}'), {
                                'mode': 'range',
                                'defaultHour': 0,
                                ...@json(config('livewire-powergrid.plugins.flat_piker.locales.'.app()->getLocale())),
                                @if(isset($customConfig['only_future']))
                                "minDate": "today",
                                @endif
                                    @if(isset($customConfig['no_weekends']) === true)
                                "disable": [
                                    function (date) {
                                        return (date.getDay() === 0 || date.getDay() === 6);
                                    }
                                ],
                                @endif
                                ...@json($customConfig),
                                onClose: function (selectedDates, dateStr, instance) {
                                    window.livewire.emit('powergrid:eventChangeDatePiker', {
                                        selectedDates: selectedDates,
                                        values: instance._input.attributes['data-key'].value
                                    });
                                }
                            }
                        );
                    </script>
                    <!-- Power Grid Date Picker Scripts -->
        @endpush
    @endif
