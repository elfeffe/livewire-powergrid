<form @if($action->method !== 'delete') target="_blank" @endif action="{{ route($action->route, $parameters) }}" method="post">
    @method($action->method)
    @csrf
    <button type="submit" class="
                 {{ (filled($action->class)) ? 'focus:outline-none text-sm py-2.5 px-5 rounded border '.$action->class
                                :'focus:outline-none text-sm py-2.5 px-5 rounded border'
                 }}"
    >
        {{ (filled($action->caption)) ? $action->caption: 'Editar' }}
    </button>
</form>
