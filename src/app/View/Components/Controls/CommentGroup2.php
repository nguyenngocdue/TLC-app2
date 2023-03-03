<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class CommentGroup2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $item = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $commentDataSource = $this->item->{$this->name};
        return view('components.controls.comment-group2', [
            'dataSource' => $commentDataSource,
        ]);
    }
}
