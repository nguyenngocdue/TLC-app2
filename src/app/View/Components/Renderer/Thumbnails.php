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
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $cell = json_decode($this->cell);
        // dd($cell);
        $result = array_map(fn ($item) => [
            'url_thumbnail' => $path . $item->url_thumbnail,
            'url_media' => $path . $item->url_media,
            'filename' => $item->filename,
        ], $cell);
        $imgs = array_map(fn ($item) => "<x-renderer.image w=64 title='{$item['filename']}' src='{$item['url_thumbnail']}' href='{$item['url_media']}'></x-renderer.image>", $result);
        $imgStr = join(" ", $imgs);
        return "<div class='flex flex-row' component='thumbnails'>$imgStr</div>";
    }
}
