<?php

namespace PowerComponents\LivewirePowerGrid\UI;

class UI
{
    public static function badge($title, $type = 'default')
    {
        return view('livewire-powergrid::components.badge', [
            'title' => $title,
            'type' => $type
        ])->render();
    }

    public function avatar($src)
    {
        return view('livewire-powergrid::components.img', [
            'src' => $src,
            'variant' => 'avatar'
        ])->render();
    }

    public function link($title, $to)
    {
        return view('livewire-powergrid::components.link', compact(
            'to',
            'title'
        ))->render();
    }

    public function icon($icon, $type = 'default', $class = "")
    {
        return view('livewire-powergrid::components.icon', compact(
            'icon',
            'type',
            'class'
        ))->render();
    }

    public function attributes($attributes, $options = [])
    {
        return $this->component('livewire-powergrid::components.attributes-list', array_merge(
            ['data' => $attributes],
            $options
        ));
    }
}
