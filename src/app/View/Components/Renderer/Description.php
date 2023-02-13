<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Description extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $label,
        private $colSpan,
        private $dataSource,
        private $columnName,
        private $control,
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
        $dataSource = $this->dataSource;
        $columnName = $this->columnName;
        $content = $dataSource[$columnName];

        $label = $this->label;
        $colSpan = $this->colSpan;
        $control = $this->control;
        return view('components.renderer.description', [
            'label' => $label,
            'colSpan' => $colSpan,
            'content' => $content,
            'control' => $control
        ]);
    }
}
