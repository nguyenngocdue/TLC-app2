<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\Json\Properties;
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

    private function getProperties()
    {
        $properties = Properties::getAllOf('attachment');
        foreach ($properties as $property) {
            if ($property['field_name'] === $this->name) {
                return $property;
            }
        }
        return null;
    }

    public function render()
    {
        $properties = $this->getProperties();
        // dump($properties);
        $properties = !is_null($properties) ? $properties : [
            "max_file_count" => 5,
            "max_file_size" => 10,
            "allowed_file_types" => 'only_images',
        ];
        $message =  "Allows MAX " . $properties['max_file_count'] . " files (each " . $properties['max_file_size'] . "MB) (" . $properties['allowed_file_types'] . ")";

        return view('components.renderer.attachment2', [
            'name' => $this->name,
            'destroyable' => (bool)$this->destroyable,
            'readonly' => $this->readonly,
            'showToBeDeleted' => $this->showToBeDeleted,
            'path' => env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/',
            'attachments' => $this->attachments,
            'message' => $message,
        ]);
    }
}
