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
        // private $rendererParam = '',
        private $dataLine = [],
        private $href = "",
        private $gray = false,
    ) {
        $src = "/images/avatar.jpg";
        $this->avatar = (!$this->avatar || $this->avatar === 'avatar') ? $src : $this->avatar;
    }

    public function render()
    {
        $rendererParam = "title=name,description=position_rendered,href=href,avatar=avatar,gray=gray";
        return view('components.renderer.avatar-name', [
            'title' => $this->title,
            'description' => $this->description,
            'avatar' => $this->avatar,
            'rendererParam' => $rendererParam,
            'href' => $this->href,
            'gray' => $this->gray,
        ]);
    }
}
