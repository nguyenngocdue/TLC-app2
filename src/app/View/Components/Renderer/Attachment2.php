<?php

namespace App\View\Components\Renderer;

use App\Utils\ClassList;
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
        private $showUploadFile = true,
        private $label = '',
        private $properties = [],
        private $openType = 'gallery',
    ) {
        // dump($openType);
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
        $properties['max_file_count'] = $properties['max_file_count'] ?? 10;
        $properties['max_file_size'] = $properties['max_file_size'] ?? 10;
        $properties['allowed_file_types'] = $properties['allowed_file_types'] ?? 'only_images';
        // dd($properties);
        $message =  "<i class='fa-sharp fa-regular fa-upload'></i> Browse (Max " . $properties['max_file_count'] . " files, each " . $properties['max_file_size']  . "MB)";
        switch ($properties['allowed_file_types']) {
            case 'only_images':
                $acceptAttachment = ".png,.jpeg,.gif,.jpg,.svg,.webp";
                $title = "Only Images (JPG, JPEG, PNG, GIF, WEBP, SVG)";
                break;
            case 'only_videos':
                $acceptAttachment = "video/mp4";
                $title = "Only Videos (MP4)";
                break;
            case 'only_media':
                $acceptAttachment = "video/* image/*";
                $title = "Only Images (JPG, JPEG, PNG, GIF, WEBP, SVG) and Videos (MP4)";
                break;
            case 'only_non_media':
                $acceptAttachment = ".csv,.pdf,.zip";
                $title = "Only Non-Media (CSV, PDF, ZIP)";
                break;
            case 'all_supported':
                $acceptAttachment = "";
                $title = "All supported formats";
                break;
            default:
                break;
        }
        $docFiles = [];
        $remainingFiles = [];
        foreach($this->attachments as $item){
            if(in_array($item['extension'],['pdf','csv','zip'])){
                $docFiles[] = $item;
            }else $remainingFiles[] = $item;
        }
        return view('components.renderer.attachment2', [
            'name' => $this->name,
            'destroyable' => $this->destroyable,
            'readOnly' => $this->readOnly,
            'showUploadFile' => $this->showUploadFile,
            'path' => env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/',
            'docs' => $docFiles,
            'attachments' => $remainingFiles,
            'acceptAttachment' => $acceptAttachment,
            'message' => $message,
            'messageTitle' => $title,
            'hiddenOrText' => $this->debugAttachment ? "text" : "hidden",
            'btnClass' => ClassList::BUTTON,
            'openType' => $this->openType,
        ]);
    }
}

