<?php

namespace App\View\Components\Controls;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\Properties;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CommentGroup2 extends Component
{
    private static $comment00Count = 1;
    private $comment01Name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id = null,
        private $name = null,
        private $item = null,
        private $type = null,
    ) {
        $this->comment01Name = "comment" . str_pad(static::$comment00Count++, 2, 0, STR_PAD_LEFT);
        // dump($this->comment01Name);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $fn = $this->name;
        $modelPath = Str::modelPathFrom($this->type);

        $properties = Properties::getAllOf('comment');
        if (!isset($properties["_" . $fn])) {
            dump("Properties of comment [$fn] not found, pls use Manage-Property screen to add one.");
            return;
        }
        $properties = $properties["_" . $fn];
        // dump($properties);
        $fieldId = $properties['field_id'];

        $user = CurrentUser::get();
        $userName = $user->name;
        $userId = $user->id;
        $userPosition = $user->position_rendered;
        // dump($userId, $userName, $userPosition);
        if (!empty((array)$this->item) && !method_exists($this->item, $fn)) {
            dump("The comment $fn not found, please create an eloquent param for it.");
            return;
        }

        $commentDataSource = $this->item->{$fn} ?? collect();
        foreach ($commentDataSource as $commentObj)
            $commentObj->commentId = $commentObj->id;
        return view('components.controls.comment-group2', [
            'comment01Name' => $this->comment01Name,

            'dataSource' => $commentDataSource,
            'allowAppending' => true,
            'commentable_type' => $modelPath,
            'commentable_id' => $this->id,
            'fieldId' => $fieldId,

            'userName' => $userName,
            'userId' => $userId,
            'userPosition' => $userPosition,

            "allowedChangeOwner" => $properties['allowed_change_owner'],
            "allowedAttachment" => $properties['allowed_attachment'],
            "allowedDelete" => $properties['allowed_delete'],
            "forceCommentOnce" => $properties['force_comment_once'],

            'now' => date(Constant::FORMAT_DATETIME_ASIAN),
        ]);
    }
}
