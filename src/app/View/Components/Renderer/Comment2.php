<?php

namespace App\View\Components\Renderer;

use App\Models\User;
use App\Utils\Support\DateTimeConcern;
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
        // private $id = null,

        private $content = null,
        private $ownerId = null,
        private $positionRendered = null,
        private $commentableType = null,
        private $commentableId = null,
        private $category = null,
        private $datetime = null,
        private $commentId = null,

        private $readonly = false,
        private $rowIndex = null,

        private $allowedDelete = null,
        private $allowedChangeOwner = null,
        private $allowedAttachment = null,
        private $forceCommentOnce = null,

        private $commentDebug = !false,
    ) {
        //
        // dump($datetime);
        // dump($content);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = User::find($this->ownerId);
        $datetime = DateTimeConcern::convertForLoading("picker_datetime", $this->datetime);
        // dump($this->datetime);
        // dump($datetime);

        return view('components.renderer.comment2', [
            'comment01Name' => $this->comment01Name,
            'name' => $this->name,
            // 'id' => $this->id,
            'ownerId' => $this->ownerId,
            'ownerObj' => $user,
            'positionRendered' => $this->positionRendered,
            'datetime' => $datetime,
            'content' => $this->content,
            'readonly' => $this->readonly,
            'rowIndex' => $this->rowIndex,
            'category' => $this->category,
            'commentId' => $this->commentId,
            'commentableType' => $this->commentableType,
            'commentableId' => $this->commentableId,

            'allowedDelete' => $this->allowedDelete,
            'allowedChangeOwner' => $this->allowedChangeOwner,
            'allowedAttachment' => $this->allowedAttachment,
            'forceCommentOnce' => $this->forceCommentOnce,
            'commentDebug' => $this->commentDebug,
            'commentDebugType' => $this->commentDebug ?  'text' : 'hidden',
            'classList' => 'dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full text-sm focus:border-purple-400 focus:outline-none',
        ]);
    }
}
