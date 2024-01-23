<?php

namespace App\View\Components\Renderer;

use App\Utils\Constant;
use Illuminate\View\Component;

class ImageGallery extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $action = 'edit',
        private $mode = 'default',
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
        if ($this->action == 'create') return '';
        $propsAttachment = array_filter($this->dataSource, fn ($item) => $item['control'] == 'attachment');
        $attachments = array_column($propsAttachment, 'value');
        $results = collect();
        foreach ($attachments as  $collection) {
            $results = $results->merge($collection);
        }
        $results = $results->whereIn('extension', Constant::EXTENSIONS_OF_FILE_GALLERY);
        return view('components.renderer.image-gallery', ['mode' => $this->mode,'dataSource' => $results, 'pathMinio' => pathMinio()]);
    }
}
