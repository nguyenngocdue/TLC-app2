<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Log;
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
        if ($this->dataLine instanceof \App\Models\Attachment) {
            $supportedImages = [
                'image/png',
                'image/jpeg',
                'image/gif',
            ];
            switch (true) {
                case $this->dataLine->mime_type == "application/pdf":
                    return '<div class="flex flex-row justify-center"><i class="text-3xl fa-duotone fa-file-pdf"></i></div>';
                case $this->dataLine->mime_type == "application/zip":
                    return '<div class="flex flex-row justify-center"><i class="text-3xl fa-duotone fa-file-zipper"></i></div>';
                case $this->dataLine->mime_type == "text/csv":
                    return '<div class="flex flex-row justify-center"><i class="text-3xl fa-duotone fa-file-csv"></i></div>';
                case $this->dataLine->mime_type == "video/mp4":
                    return '<div class="flex flex-row justify-center"><i class="text-3xl fa-duotone fa-file-video"></i></div>';
                case !in_array($this->dataLine->mime_type, $supportedImages):
                    return $this->dataLine->mime_type;
            }
        }

        $path = app()->pathMinio() . '/';

        $dataIndex = $this->column['dataIndex'];
        $cell = $this->dataLine->$dataIndex;

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
