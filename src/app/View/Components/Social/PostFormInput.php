<?php

namespace App\View\Components\Social;

use Illuminate\View\Component;

class PostFormInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $modalId)
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
        return view('components.social.post-form-input',[
            'modalId' => $this->modalId,
        ]);
    }
}
