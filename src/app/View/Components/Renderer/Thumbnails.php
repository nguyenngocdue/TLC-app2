<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Thumbnails extends Component
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
        // dump($cell);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $cell = json_decode($this->cell);
        return function (array $data) {
            $max = 3;
            // dump($data['slot']);
            $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
            $cell = json_decode($data['slot']);
            // dump($cell);
            if (is_null($cell)) return "";
            $remain = 0;
            if (sizeof($cell) > $max) {
                $remain = sizeof($cell) - $max;
                $cell = array_splice($cell, 0, $max);
            }
            $result = array_map(fn ($item) => [
                'url_thumbnail' => $path . $item->url_thumbnail,
                'url_media' => $path . $item->url_media,
                'filename' => $item->filename,
            ], $cell);
            $imgs = array_map(fn ($item) => "<x-renderer.image class='rounded' title='{$item['filename']}' src='{$item['url_thumbnail']}' href='{$item['url_media']}'></x-renderer.image>", $result);
            $imgStr = join(" ", $imgs);
            if ($remain) {
                $imgStr .= "<x-renderer.tag color='sky'>+$remain more</x-renderer.tag>";
            }
            return "<div class='flex flex-row' component='thumbnails'>$imgStr</div> ";
        };
    }
}
