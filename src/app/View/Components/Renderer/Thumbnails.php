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
            $max = 100;
            // dump($data['slot']);
            $path = app()->pathMinio() . '/';
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
            // $imgs = array_map(fn ($item) => "<x-renderer.image class='rounded' title='{$item['filename']}' src='{$item['url_thumbnail']}' href='{$item['url_media']}'></x-renderer.image>", $result);
            $imgs = [];
            for ($i = 0; $i < sizeof($result); $i++) {
                $item = $result[$i];
                $imgs[] = "<x-renderer.image class='rounded' title='{$item['filename']}' src='{$item['url_thumbnail']}' href='{$item['url_media']}'></x-renderer.image>";
                // $img = $imgs[$i];
                // if ($i % 5 == 0) $imgs[] = "<br/>";
            }
            $imgStr = join(" ", $imgs);
            if ($remain) {
                $imgStr .= "<x-renderer.tag color='sky'>+$remain more</x-renderer.tag>";
            }
            return "<div class='grid grid-cols-5 w-52 gap-0.5' component='thumbnails'>$imgStr</div> ";
        };
    }
}
