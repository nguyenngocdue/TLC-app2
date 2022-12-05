<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class AvatarName extends Component
{
    public function __construct(
        private $title = "No Name",
        private $description = "",
        private $avatar = "",
        private $rendererParam = '',
        private $dataLine = [],
        private $href = "",
    ) {
        $src = "https://cdn.vectorstock.com/i/1000x1000/23/70/man-avatar-icon-flat-vector-19152370.webp";
        $this->avatar = (!$this->avatar || $this->avatar === 'avatar') ? $src : $this->avatar;
    }

    public function render()
    {
        $title = $this->title;
        $description = $this->description;
        $avatar = $this->avatar;
        $href = $this->href;
        $rendererParam = "title=name,description=position_rendered,href=href";

        return view('components.renderer.avatar-name')->with(compact('title', 'description', 'avatar', 'rendererParam', 'href'));
    }
}
