<?php

namespace App\View\Components\Controls;

use App\Utils\Constant;
use Illuminate\View\Component;

class LockVersion extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = Constant::NAME_LOCK_COLUMN,
        private $value = null,
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
        return "<input type='hidden' name='$this->name' component='controls/lock-version' value='$this->value' />";
    }
}
