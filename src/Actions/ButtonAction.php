<?php

namespace PowerComponents\LivewirePowerGrid\Actions;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\Traits\WithActions;
use App\Traits\Notify;

class ButtonAction extends Component
{
    use WithActions;
    use Notify;

    public $component = 'livewire-powergrid:button-action';

    public function executeAction()
    {
        if ($this->action->shouldBeConfirmed()) {
            $this->emit("openModal", "livewire-powergrid:confirmation-modal",
                [
                    "content" => $this->action->getConfirmationMessage($this->model),
                    "action" => $this->actionClass,
                    "model" => $this->modelToArray(),
                ]);

            return;
        }

        $this->action->handle($this->model);

        $this->emit('powergrid:refresh');
    }

    public function render()
    {
        return view('livewire-powergrid::livewire.actions.button-action');
    }
}
