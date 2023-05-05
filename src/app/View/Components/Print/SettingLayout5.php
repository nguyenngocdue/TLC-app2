<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class SettingLayout5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $class,
        private $value,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.print.setting-layout5', [
            'type' => $this->type,
            'class' => $this->class,
            'value' => $this->value,
        ]);
    }
}
