<?php

namespace PowerComponents\LivewirePowerGrid\Http\Livewire;

use LivewireUI\Modal\ModalComponent;
use App\Traits\Notify;

class ConfirmationModal extends ModalComponent
{
    use Notify;
    public $title;
    public $content;
    public $action;
    public $model;
    public $icon;

    public function getInitializedActionProperty()
    {
        return (new $this->action());
    }

    public static function modalMaxWidth(): string
    {
        // 'sm'
        // 'md'
        // 'lg'
        // 'xl'
        // '2xl'
        // '3xl'
        // '4xl'
        // '5xl'
        // '6xl'
        // '7xl'
        return 'lg';
    }

    public function mount($action, $model, $content, $title = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->action = $action;
        $this->model = $model;
        $this->icon = $this->initializedAction->icon;
    }

    public function update()
    {
        $this->initializedAction->handle($this->model);

        $this->emit('powergrid:refresh');

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire-powergrid::livewire.confirmation-modal');
    }
}
