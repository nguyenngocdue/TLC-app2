<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\Comment;
use App\Models\Zunit_test_7;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CommentRenderer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $type,
        private $colName = '',
        private $action,
        private $readonly = true,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $id = $this->id;
        $name = $this->colName;
        $type = $this->type;
        $action = $this->action;

        $idNameCatesDB = Helper::getDataDbByName('comment_categories', 'id', 'name');


        $dataComment = [
            [
                "id" => "",
                "content" => "",
                "owner_id" => Auth::user()->id,
                "created_at" => date_format(date_create(), "d/m/Y H:i:s"),
                'readonly' => false,
            ]
        ];

        if ($action === 'edit') {
            $array = [];
            $modelPath = "App\\Models\\" . Str::singular($type);
            $allCommentsUser = $modelPath::find($id)->comments()->get();
            $idCateCommentsUser = $allCommentsUser->pluck('category', 'id')->toArray();

            foreach ($idCateCommentsUser as $id => $keyCate) {
                if ($name === $idNameCatesDB[$keyCate]) {
                    foreach ($allCommentsUser->toArray() as $value) {
                        if ($value['id'] * 1 === $id * 1) {
                            $array[] = $value;
                        }
                    }
                }
            }
            // add an empty comment component
            $dataComment = array_merge($array, $dataComment);
        }
        // dump($dataComment);
        return view('components.controls.comment-renderer')->with(compact('name', 'type', 'dataComment', 'id', 'action'));
    }
}
