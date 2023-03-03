<?php

namespace App\View\Components\Controls;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Database\Seeders\FieldSeeder;
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
        $fieldId = FieldSeeder::getIdFromFieldName($this->name);
        $modelPath = Str::modelPathFrom($this->type);


        $user = CurrentUser::get();
        $userName = $user->name;
        $userId = $user->id;
        $userPosition = $user->position_rendered;
        // dump($userId, $userName, $userPosition);

        if (!method_exists($this->item, $fn)) {
            dump("The comment $fn not found, please create an eloquent param for it.");
            return;
        }
        $commentDataSource = $this->item->{$fn};
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

            'now' => date(Constant::FORMAT_DATETIME_ASIAN),
        ]);
    }
}
