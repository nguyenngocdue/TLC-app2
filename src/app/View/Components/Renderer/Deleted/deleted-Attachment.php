<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Attachment extends Component
{

    public function __construct(
        private  $readonly = false,
        private $destroyable = false,
        private $categoryName = 'attachment_1',
        private $showToBeDeleted = false,
        private $label = '',
        private $attachmentData = [],
    ) {
        //
    }

    public function render()
    {
        // dump($this->attachmentData);
        // dump($this->destroyable);
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        return view('components.renderer.attachment', [
            'readonly' => $this->readonly,
            'destroyable' => (bool)$this->destroyable,
            'categoryName' => $this->categoryName,
            'showToBeDeleted' => $this->showToBeDeleted,
            'action' => CurrentRoute::getControllerAction(),
            'label' =>  $this->label,
            'path' => $path,
            'attachmentData' => $this->attachmentData,
        ]);
    }
}
