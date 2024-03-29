<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavBar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $blade = auth()->user()?->isMember() ? 'components.member-navbar' : 'components.guest-navbar';
        return view($blade);
    }
}
