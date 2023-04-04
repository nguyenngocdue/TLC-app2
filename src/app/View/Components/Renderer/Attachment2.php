<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\Json\Properties;
use Illuminate\View\Component;

class Attachment2 extends Component
{
    private $attachments = [];
    private $debugAttachment = false;
    public function __construct(
        private $name,
        //either a string of serialized array of attachments object or array or attachments 
        private $value = "",
        private $readOnly = false,
        private $destroyable = true,
        private $showToBeDeleted = true,
        private $showUploadFile = true,
        private $label = '',
        private $properties,
    ) {
        if (is_array($value)) {
            $this->attachments = $value;
        } else {
            $this->attachments = (json_decode(htmlspecialchars_decode($value))) ?? [];
            //Convert to array if object given
            foreach ($this->attachments as &$attachment) if (is_object($attachment)) $attachment = (array)$attachment;
        }
        // dump($this->attachments);
    }


    public function render()
    {
        $properties = $this->properties;
        $message =  "Allows MAX " . $properties['max_file_count'] . " files (each " . $properties['max_file_size'] . "MB) (" . $properties['allowed_file_types'] . ")";
        switch ($properties['allowed_file_types']) {
            case 'only_images':
                $acceptAttachment = ".png,.jpeg,.gif,.jpg,.svg,.webp";
                break;
            case 'only_videos':
                $acceptAttachment = "video/mp4";
                break;
            case 'only_media':
                $acceptAttachment = "video/* image/*";
                break;
            case 'only_non_media':
                $acceptAttachment = ".csv,.pdf,.zip";
                break;
            case 'all_supported':
                $acceptAttachment = "";
                break;
            default:
                break;
        }
        return view('components.renderer.attachment2', [
            'name' => $this->name,
            'destroyable' => $this->destroyable,
            'readOnly' => $this->readOnly,
            'showToBeDeleted' => $this->showToBeDeleted,
            'showUploadFile' => $this->showUploadFile,
            'path' => env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/',
            'attachments' => $this->attachments,
            'acceptAttachment' => $acceptAttachment,
            'message' => $message,
            'hiddenOrText' => $this->debugAttachment ? "text" : "hidden",
        ]);
    }
}
