<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PublicPage extends Component
{
    public string $title;
    public string $description;
    public ?string $ogImage;
    public string $css;
    public string $javascript;
    public bool $hasSlideshow;
    public bool $noRobots;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $description = '', $ogImage = null, $css = null, $javascript = null, $hasSlideshow = false, $noRobots = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->ogImage = $ogImage ?? url('/images/sharing_image.jpg');
        $this->css = $css ?? mix('css/front.css');
        $this->javascript = $javascript ?? mix('js/front.js');
        $this->hasSlideshow = $hasSlideshow;
        $this->noRobots = $noRobots;
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
