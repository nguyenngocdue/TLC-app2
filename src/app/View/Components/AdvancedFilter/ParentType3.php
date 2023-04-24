<?php

namespace App\View\Components\AdvancedFilter;

use App\View\Components\Controls\TraitMorphTo;
use Illuminate\View\Component;

class ParentType3 extends Component
{
    use TraitMorphTo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
        private $valueSelected = null,
        private $type,
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
        $type = $this->type;
        $name = $this->name;
        $selected = $this->valueSelected;
        return "<x-controls.parent_type2 type='$type' name='$name' selected='$selected'/>";
    }
}
