<?php

namespace App\View\Components\Renderer;

use App\Utils\Constant;
use Illuminate\View\Component;

class SmartAttachment extends Component
{

    public function __construct(
        private  $readonly = false,
        private $destroyable = false,
        private $attCategory = '',
        private $showToBeDeleted = false,
        private $colName = 'attachment_1',
        private $action = 'edit',
        private $labelName = '',
        private $path = Constant::PATH,
        private $attachmentData = [],
    ) {
        //
    }

    public function render()
    {
        // dump($this->attachmentData);
        return view('components.renderer.smart-attachment', [
            'readonly' => $this->readonly,
            'destroyable' => $this->destroyable,
            'attCategory' => $this->attCategory,
            'showToBeDeleted' => $this->showToBeDeleted,
            'colName' => $this->colName,
            'action' => $this->action,
            'labelName' =>  $this->labelName,
            'path' => $this->path,
            'attachmentData' => $this->attachmentData,
        ]);
    }
}
