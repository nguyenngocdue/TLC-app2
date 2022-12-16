<?php

namespace App\View\Components\Renderer;

use App\Utils\Constant;
use Illuminate\View\Component;

class SmartAttachment extends Component
{

    public function __construct(
        private  $readonly = false,
        private $destroyable = false,
        private $categoryName = 'attachment_1',
        private $showToBeDeleted = false,
        private $action = 'edit',
        private $labelName = '',
        private $attachmentData = [],
    ) {
        //
    }

    public function render()
    {
        // dump($this->attachmentData);
        // dump($this->destroyable);
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        return view('components.renderer.smart-attachment', [
            'readonly' => $this->readonly,
            'destroyable' => (bool)$this->destroyable,
            'categoryName' => $this->categoryName,
            'showToBeDeleted' => $this->showToBeDeleted,
            'action' => $this->action,
            'labelName' =>  $this->labelName,
            'path' => $path,
            'attachmentData' => $this->attachmentData,
        ]);
    }
}
