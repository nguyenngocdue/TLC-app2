<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class AvatarName extends Component
{
    public function __construct(private $title = "Untitled", private $description = "", private $avatar = "")
    {
        $src = "https://cdn.vectorstock.com/i/1000x1000/23/70/man-avatar-icon-flat-vector-19152370.webp";
        $this->avatar = (!$this->avatar) ? $src : $this->avatar;
    }

    public function render()
    {
        $title = $this->title;
        $description = $this->description;
        $avatar = $this->avatar;
        return view('components.renderer.avatar-name')->with(compact('title', 'description', 'avatar'));
    }
}
