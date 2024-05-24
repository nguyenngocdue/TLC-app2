<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Attachment3 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource
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
        $dataSource = $this->dataSource;
        $extension = $dataSource->extension;
        $fileName = $dataSource->filename;
        $urlThumbnail = $dataSource->url_thumbnail;
        $urlMedia = $dataSource->url_media;
        $path = app()->pathMinio() . '/';
        return view('components.renderer.attachment3', [
            'urlThumbnail' => $path . $urlThumbnail,
            'urlMedia' => $path . $urlThumbnail,
            'fileName' => $fileName,
            'extension' => $extension
        ]);
    }
}
