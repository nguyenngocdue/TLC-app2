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
        private $dataLine = [],
        private $href = "",
        private $gray = false,
    ) {
    }

    public function render()
    {
        $rendererParam = "title=name,description=position_rendered,href=href,gray=gray,avatar=avatar";
        if (!is_array($this->dataLine)) {
            $avatar = $this->dataLine->user->avatar;
            if ($avatar) $this->avatar = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $avatar->url_thumbnail;
        }
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
