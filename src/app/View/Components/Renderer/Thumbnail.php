<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Thumbnail extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $cell,
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
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $json = json_decode($this->cell);
        // dd($this->cell);
        if (Str::contains($this->cell, "No dataIndex for",)) return "";
        $url = (is_object($json)) ? $json->url_thumbnail : $this->cell;
        $title = (is_object($json)) ? $json->filename : $this->cell;
        $url = $path . $url;
        $imgStr = "<x-renderer.image w=64 title='{$title}' src='{$url}' href='{$url}'></x-renderer.image>";
        return "<div class='flex flex-row' component='thumbnail'>$imgStr</div>";
    }
}
