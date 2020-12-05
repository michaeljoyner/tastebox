<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PublicPage extends Component
{
    public string $title;
    public string $description;
    public string $css;
    public string $javascript;
    public array $ogdata;
    public bool $hasSlideshow;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $description = '', $css = null, $javascript = null, $hasSlideshow = false, $ogdata = [])
    {
        $this->title = $title;
        $this->description = $description;
        $this->css = $css ?? mix('css/front.css');
        $this->javascript = $javascript ?? mix('js/front.js');
        $this->ogdata = $ogdata;
        $this->hasSlideshow = $hasSlideshow;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.public-page');
    }
}
