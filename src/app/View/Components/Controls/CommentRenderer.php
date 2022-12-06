<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class CommentRenderer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $id, private $type, private $colName, private $tablePath, private $action)
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
        $name = $this->colName;
        $type = $this->type;
        // dump($currentUser);
        return view('components.controls.comment-renderer')->with(compact('name', 'type'));
    }
}
