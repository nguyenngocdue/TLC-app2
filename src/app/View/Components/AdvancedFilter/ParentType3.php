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
        private $type,
        private $name = '',
        private $valueSelected = null,
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
        return view('components.advanced-filter.dropdown3', [
            'dataSource' => $this->getDataSource(),
            'name' =>  $this->name,
            'valueSelected' => $this->valueSelected,
        ]);
    }

    private function getDataSource()
    {
        return $this->getAllTypeMorphMany();
    }
}
