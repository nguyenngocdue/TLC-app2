<?php

namespace App\View\Components\Renderer;

use App\Models\Comment;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class Comment2 extends Component
{
    private $commentDebug = false;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $comment01Name = null,
        // private $name = null,
        // private $id = null,

        private $content = null,
        private $ownerId = null,
        private $positionRendered = null,
        private $commentableType = null,
        private $commentableId = null,
        private $category = null,
        private $datetime = null,
        private $commentId = null,

        private $readOnly = false,
        private $rowIndex = null,

        private $allowedDelete = null,
        private $allowedChangeOwner = null,
        private $allowedAttachment = null,
        private $forceCommentOnce = null,

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
        // if ($this->commentDebug) {
        //     echo "allowedDelete: [$this->allowedDelete], allowedChangeOwner: [$this->allowedChangeOwner]";
        //     echo "allowedAttachment: [$this->allowedAttachment], forceCommentOnce: [$this->forceCommentOnce]";
        // }
        $user = User::find($this->ownerId);
        $datetime = DateTimeConcern::convertForLoading("picker_datetime", $this->datetime);
        $avatarObj = $user ? $user->avatar : null;
        $userName = $user ? $user->name : "Not found user #" . $this->ownerId;
        $avatar = $avatarObj ? env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $avatarObj->url_thumbnail : "";
        $readOnly = $this->readOnly;
        $content = $this->content;
        if (CurrentUser::get()->id != $this->ownerId) {
            $readOnly = true;
        }
        // $readonly = !true;
        $comment_attachment = collect([]);
        if ($this->commentId) {
            $commentItem = Comment::find($this->commentId);
            // dump($commentItem->comment_attachment);
            if ($commentItem) {
                $comment_attachment = $commentItem->comment_attachment;
            }
        }
        $allowedDelete = $this->allowedDelete;
        if (is_null($this->commentId)) {
            $allowedDelete = false;
        }


        return view('components.renderer.comment2', [
            'comment01Name' => $this->comment01Name,
            // 'name' => $this->name,
            // 'id' => $this->id,
            'ownerId' => $this->ownerId,
            'ownerName' => $userName,
            'ownerAvatar' => $avatar,

            'positionRendered' => $this->positionRendered,
            'datetime' => $datetime,
            'content' => $content,
            'readOnly' => $readOnly,
            'rowIndex' => $this->rowIndex,
            'category' => $this->category,
            'commentId' => $this->commentId,
            'commentableType' => $this->commentableType,
            'commentableId' => $this->commentableId,
            'commentAttachment' => $comment_attachment,

            'allowedDelete' => $allowedDelete,
            'allowedChangeOwner' => $this->allowedChangeOwner,
            'allowedAttachment' => $this->allowedAttachment,
            'forceCommentOnce' => $this->forceCommentOnce,
            'commentDebug' => $this->commentDebug,
            'commentDebugType' => $this->commentDebug ?  'text' : 'hidden',
            'classList' => 'dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full text-sm focus:outline-none',
        ]);
    }
}
