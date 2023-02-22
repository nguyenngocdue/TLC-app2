<?php

namespace App\View\Components\Renderer;

use App\Models\User;
use Illuminate\View\Component;

class Comment2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $comment01Name = 'comment01',
        private $name = null,
        private $id = null,
        private $ownerId = null,
        private $positionRendered = null,
        private $datetime = null,
        private $content = null,
        private $readonly = false,
        private $rowIndex = null,
        private $fieldId = null,
        private $commentId = null,
        private $commentableType = null,
        private $commentableId = null,

        private $allowedDelete = null,
        private $allowedChangeOwner = null,
        private $allowedAttachment = null,
        private $forceCommentOnce = null,

        private $commentDebug = false,
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
        $user = User::find($this->ownerId);
        return view('components.renderer.comment2', [
            'comment01Name' => $this->comment01Name,
            'name' => $this->name,
            'id' => $this->id,
            'ownerId' => $this->ownerId,
            'ownerObj' => $user,
            'positionRendered' => $this->positionRendered,
            'datetime' => $this->datetime,
            'content' => $this->content,
            'readonly' => $this->readonly,
            'rowIndex' => $this->rowIndex,
            'fieldId' => $this->fieldId,
            'commentId' => $this->commentId,
            'commentableType' => $this->commentableType,
            'commentableId' => $this->commentableId,

            'allowedDelete' => $this->allowedDelete,
            'allowedChangeOwner' => $this->allowedChangeOwner,
            'allowedAttachment' => $this->allowedAttachment,
            'forceCommentOnce' => $this->forceCommentOnce,
            'commentDebug' => $this->commentDebug,
            'commentDebugType' => $this->commentDebug ?  'text' : 'hidden',
        ]);
    }
}
