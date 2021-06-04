<?php

namespace PowerComponents\LivewirePowerGrid\Traits;

trait WithActions
{
    public $actionClass;
    public $route;
    public $icon;
    public $title;
    public $model;

    public function modelToArray()
    {
        $class = get_class($this->model);
        $model = $this->model->toArray();
        $model['class'] = $class;
        return $model;
    }

    public function getActionProperty()
    {
        return (new $this->actionClass);
    }

    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    public function mount($actionClass = null, $model = null, $route = null)
    {
        $this->route = $route;
        $this->actionClass = $actionClass;

        $this->icon = $this->action->icon;
        $this->title = $this->action->title;
        $this->model = $model;
    }
}
