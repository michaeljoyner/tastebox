<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminBaseLayout extends Component
{
    public $title;
    public $javascript;
    public $css;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $javascript = '', $css = '')
    {
        //
        $this->title = $title;
        $this->javascript = $javascript;
        $this->css = $css;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.admin-base-layout');
    }
}
