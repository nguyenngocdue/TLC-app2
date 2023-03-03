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
        $fn = $this->name;
        if (!method_exists($this->item, $fn)) {
            dump("The comment $fn not found, please create an eloquent param for it.");
            return;
        }
        $commentDataSource = $this->item->{$fn};
        foreach ($commentDataSource as $commentObj)
            $commentObj->commentId = $commentObj->id;
        return view('components.controls.comment-group2', [
            'dataSource' => $commentDataSource,
        ]);
    }
}
