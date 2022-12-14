<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Thumbnail extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {
            $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
            $slot = json_decode($data['slot']);
            // dump($slot);
            $result = array_map(fn ($item) => [
                'url_thumbnail' => $path . $item->url_thumbnail,
                'url_media' => $path . $item->url_media,
                'filename' => $item->filename,
            ], $slot);
            $imgs = array_map(fn ($item) => "<x-renderer.image title='{$item['filename']}' src='{$item['url_thumbnail']}'></x-renderer.image>", $result);
            $imgStr = join(" ", $imgs);
            return "<div class='flex h-12 w-12'>$imgStr</div>";
        };
        // return view('components.renderer.thumbnail');
    }
}
