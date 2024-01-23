<?php

namespace App\View\Components\Renderer;

use App\Utils\Constant;
use Illuminate\View\Component;

class ImageGalleryKanbanTask extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $item,
        private $action = 'edit',
        private $mode = 'kanban'
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
        $results = collect();
        $propsAttachment = array_filter($this->dataSource, fn ($item) => $item['control'] == 'attachment');
        $results = collect();
        foreach ($propsAttachment as $prop) {
            $results  = $results->merge($this->item->{$prop['column_name']});
        }

        $results = $results->whereIn('extension', Constant::EXTENSIONS_OF_FILE_GALLERY);
        return view('components.renderer.image-gallery', ['mode' => $this->mode,'dataSource' => $results, 'pathMinio' => pathMinio()]);
    }
}
