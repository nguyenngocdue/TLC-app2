<?php

namespace App\View\Components\Renderer;

use App\Utils\Constant;
use Illuminate\View\Component;

class ImageGalleryCheckSheet extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $checkPointIds,
        private $dataSource,
        private $action)
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
        if($this->action == 'create') return '';
        $results = collect();
        foreach ($this->dataSource as $line) {
            $attachments = $line->getMorphManyByIds($this->checkPointIds, 'insp_photos');
            $results = $results->merge($attachments);
        }
        $results = $results->whereIn('extension',Constant::EXTENSIONS_OF_FILE_GALLERY);
        return view('components.renderer.image-gallery',['dataSource' => $results,'pathMinio' => pathMinio()]);
    }
}
