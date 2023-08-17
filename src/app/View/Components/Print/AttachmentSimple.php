<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class AttachmentSimple extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $component = 'attachment/simple',
        private $dataSource = [],
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.print.attachment-simple',[
            'dataSource' => $this->dataSource,
            'component' => $this->component,
            'pathMinio' => pathMinio(),
        ]);
    }
}
