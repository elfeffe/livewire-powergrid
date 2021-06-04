@if(isset($actionBtns) && count($actionBtns))
    <td class="px-1 py-1 whitespace-nowrap flex flex-row">
        @foreach($actionBtns as $action)
            @php
                $parameters = [];
                foreach ($action->param as $param => $value) {
                    $parameters[$param] = $row->{$value};
                }
            @endphp
            @include('livewire-powergrid::tailwind.2.actions.' . $action->view)
        @endforeach
    </td>
@endif
