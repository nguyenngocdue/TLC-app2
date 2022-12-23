<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\Comment;
use App\Models\Zunit_test_7;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CommentGroup extends Component
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
        private $label = '',
        private $destroyable = true,
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
        $label = $this->label;
        $showToBeDeleted =  env('APP_ENV')  === 'local';
        $owner_id = Auth::user()->id;

        $idNameCatesDB = Helper::getDataDbByName('fields', 'id', 'name');
        $orphanAttachmentDB = json_decode(DB::table('attachments')->where([['owner_id', '=',  $owner_id], ['object_id', '=', null], ['object_type', '=', null]])->select('id', 'category', 'object_id')->get(), true);

        $attachmentData = [];
        foreach ($orphanAttachmentDB as $attach) {
            if ($idNameCatesDB[$attach['category']] === $name) {
                $ele = (array)DB::table('attachments')->find($attach['id']);
                $attachmentData[$name][] = $ele;
            }
        }


        // dump($attachmentData);
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

            foreach ($idCateCommentsUser as $idComment => $keyCate) {
                if ($name === $idNameCatesDB[$keyCate]) {
                    foreach ($allCommentsUser->toArray() as $value) {
                        if ($value['id'] * 1 === $idComment * 1) {
                            $array[] = $value;
                        }
                    }
                }
            }
            // add an empty comment component
            $dataComment = array_merge($array, $dataComment);
        }
        return view('components.controls.comment-group', [
            "id" => $id,
            "name" => $name,
            "type" => $type,
            'label' => $label,
            "action" => $action,
            "dataComment" => $dataComment,
            'destroyable' => $this->destroyable,
            'showToBeDeleted' => $showToBeDeleted,
            'attachmentData' => $attachmentData
        ]);
    }
}
