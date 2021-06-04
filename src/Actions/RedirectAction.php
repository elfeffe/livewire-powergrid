<?php

namespace PowerComponents\LivewirePowerGrid\Actions;

use PowerComponents\LivewirePowerGrid\Traits\WithActions;
use Livewire\Component;

class RedirectAction extends Component
{
    use WithActions;
    public $component = 'livewire-powergrid:redirect-action';

    public function route($route) : RedirectAction
    {
        $this->route = $route;
        return $this;
    }

    public function executeAction()
    {
        $this->action->handle($this->route, $this->model);
    }

    public function render()
    {
        return view('livewire-powergrid::livewire.actions.button-action');
    }
}
