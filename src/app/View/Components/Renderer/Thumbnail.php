<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Log;
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
        private $column,
        private $dataLine,
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

        $dataIndex = $this->column['dataIndex'];
        $cell = $this->dataLine->$dataIndex;

        // $renderAnonymousAvatar = false;
        // if (Str::contains($this->cell, "No dataIndex for",)) {
        //     if ($this->column['dataIndex'] === 'avatar') {
        //         $renderAnonymousAvatar = true;
        //     } else {
        //         return ""; //<<Render no image when no URL found
        //     }
        // }

        // $cell = json_decode($this->cell);
        // if ($renderAnonymousAvatar) {
        if (is_null($cell)) {
            $url_thumbnail = "/images/avatar.jpg";
            $url_media = "/images/avatar.jpg";
            $component = "thumbnail_anonymous";
            $href = "";
            $title = "No avatar found";
        } elseif (is_object($cell)) {
            $url_thumbnail = $path . $cell->url_thumbnail;
            $url_media = $path .  $cell->url_media;
            $component = "thumbnail_object";
            $href =  "href='{$url_media}'";
            $title =  $cell->filename;
        } else {
            $url_thumbnail = $path . $this->cell;
            $url_media = $path .  $this->cell;
            $component =  "thumbnail_string";
            $href =  "";
            $title =  $this->cell;
        }
        $imgStr = "<x-renderer.image class='rounded' title='{$title}' src='{$url_thumbnail}' $href></x-renderer.image>";
        return "<div class='flex flex-row justify-center' component='$component'>$imgStr</div>";
    }
}
