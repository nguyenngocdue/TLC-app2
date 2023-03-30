<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class AvatarItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $title = "No Name",
        private $description = "",
        private $avatar = "",
        private $href = "",
        private $gray = false,
        private $timeLine = false,
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
        return view('components.renderer.avatar-item', [
            'title' => $this->title,
            'description' => $this->description,
            'avatar' => $this->avatar,
            'href' => $this->href,
            'gray' => $this->gray,
            'timeLine' => $this->timeLine,
        ]);
    }
}
