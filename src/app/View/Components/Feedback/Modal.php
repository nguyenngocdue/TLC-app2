<?php

namespace App\View\Components\Feedback;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type, private $title)
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
        return view('components.feedback.modal', [
            'type' => $this->type,
            'title' => $this->title,
        ]);
    }
}
