<?php

namespace App\View\Components\Print\InspChklst;

use App\Http\Services\LoadManyCheckpointService;
use Illuminate\View\Component;

class CheckPoint2Print extends Component
{
    public function __construct(
        private $checkpoint,
    ) {
        //
    }

    function render()
    {
        // dump($this->checkpoint);
        $controlType = $this->checkpoint->getControlType->slug;
        $attachmentGroups = LoadManyCheckpointService::getAttachmentGroups($this->checkpoint->getSheet);

        switch ($controlType) {
            case 'radio':
                $body = view('components.print.insp-chklst.check-point2-option-print', ['checkpoint' => $this->checkpoint,]);
                break;
            case 'text':
            case 'textarea':
                $body = view('components.print.insp-chklst.check-point2-text-print', ['checkpoint' => $this->checkpoint,]);
                break;
            case 'signature':
                $body = view('components.print.insp-chklst.check-point2-signature-print', ['checkpoint' => $this->checkpoint,]);
                break;
            default:
                $body = "Unknown how to render control type $controlType";
                break;
        }

        $attachments = view(
            'components.print.insp-chklst.check-point2-attachment-print',
            [
                'checkpoint' => $this->checkpoint,
                'attachmentGroups' => $attachmentGroups,
            ]
        );

        $comments = view('components.print.insp-chklst.check-point2-comment-print', ['checkpoint' => $this->checkpoint,]);

        return $body . $attachments . $comments;
    }
}
