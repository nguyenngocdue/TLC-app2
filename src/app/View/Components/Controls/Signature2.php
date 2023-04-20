<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Signature2 extends Component
{
    static $count = 0;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $value = null,
        private $debug = false,
    ) {
        //
        static::$count++;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $value_decoded = (htmlspecialchars_decode($this->value));
        return view(
            'components.controls.signature2',
            [
                'name' => $this->name,
                'value' => $this->value,
                'value_decoded' => $value_decoded,
                'count' => static::$count,
                'input_or_hidden' => $this->debug ? "text" : "hidden",
            ]
        );
    }
}
