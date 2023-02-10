<?php

namespace App\View\Components\Controls;

use App\View\Components\Renderer\TraitMorphTo;
use Illuminate\View\Component;

class ParentType extends Component
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
        $dataSource = $this->getAllTypeMorphMany();
        return view('components.controls.parent-type', [
            'dataSource' => $dataSource,
            'name' =>  $this->name,
            'valueSelected' => $this->valueSelected,
        ]);
    }
}
