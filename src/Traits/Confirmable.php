<?php

namespace PowerComponents\LivewirePowerGrid\Traits;

trait Confirmable
{
    public function getConfirmationMessage($item = null)
    {
        return 'Do you really want to perform this action?';
    }
}
