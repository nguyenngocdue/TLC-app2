<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Attachment2 extends Component
{
    private $attachments = [];
    public function __construct(
        private $name,
        private $value = "",
        private $readonly = false,
        private $destroyable = true,
        private $showToBeDeleted = false,
        private $label = '',
    ) {
        if (is_array($value)) {
            $this->attachments = $value;
        } else {
            $this->attachments = (json_decode(htmlspecialchars_decode($value))) ?? [];
            //Convert to array if object given
            foreach ($this->attachments as &$attachment) if (is_object($attachment)) $attachment = (array)$attachment;
        }
    }

    public function render()
    {
        return view('components.renderer.attachment2', [
            'name' => $this->name,
            'destroyable' => (bool)$this->destroyable,
            'readonly' => $this->readonly,
            'showToBeDeleted' => $this->showToBeDeleted,
            'path' => env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/',
            'attachments' => $this->attachments,
        ]);
    }
}
