<?php

namespace PowerComponents\LivewirePowerGrid\Actions;

use PowerComponents\LivewirePowerGrid\Traits\Confirmable;

abstract class Action
{
    /** @var String $title Title of the action */
    public $title;
    public $model;

    /** @var String $icon Hero icon name */
    public $icon;

    public $ui;
    public $id;

    /** Item the action will be executed with */
    public $item;

    private $messages = [
        'success' => 'Action was executed successfully',
        'negative' => 'There was an error executing this action',
    ];

    public function __construct($ui = null)
    {
        $this->ui = $ui;
    }

    public function ui($ui)
    {
        $this->ui = $ui;
        return $this;
    }

    public function setModel($model)
    {
        if(gettype($model) === 'array')
        {
            $class = new $model['class'];
            $model = $class::find($model['id']);
            $this->model = $model;
        }
    }

    public function isRedirect()
    {
        return get_class($this) === RedirectAction::class;
    }

    public function renderIf($item)
    {
        return true;
    }

    public function success($message = null)
    {
        $this->setMessage('success', $message);
    }

    public function error($message = null)
    {
        $this->setMessage('negative', $message);
    }

    private function setMessage($type = 'success', $message = null)
    {
        session()->flash('messageType', $type);
        session()->flash('message', $message ?: $this->messages[$type]);

        session()->push('actions.messages', ['type' => $type, 'message' =>  $message ?: $this->messages[$type]]);

    }

    public function shouldBeConfirmed()
    {
        return in_array(
            Confirmable::class,
            class_uses_recursive($this)
        );
    }
}
