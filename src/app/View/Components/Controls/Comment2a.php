<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Comment2a extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $comment,
        private $debug = false,
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
        $name =  $this->comment['owner_id']['display_name'];
        $position = $this->comment['position_rendered']['value'];
        $title = "$name ($position):";

        return view(
            'components.controls.comment2a',
            [
                'comment' => $this->comment,
                // 'debug' => $this->debug,
                'input_or_hidden' => $this->debug ? 'input' : 'hidden',
                "title" => $title,
            ]
        );
    }
}
