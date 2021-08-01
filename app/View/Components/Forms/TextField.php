<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class TextField extends Component
{

    public function __construct(
        public string $label,
        public string $name,
        public string $error = '',
        public string $type = 'text',
        public string $value = '',
        public string $placeholder = '',
    ) {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.text-field');
    }
}
