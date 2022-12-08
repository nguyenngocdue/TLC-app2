<?php

namespace App\View\Components\Controls;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

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
        private $tablePath,
        private $action,
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

        $commentCateDB = DB::table('comment_categories')->select('id', 'name')->get();
        $commentCateDB =  $commentCateDB->toArray();
        $nameIdsDB = array_column($commentCateDB, 'id', 'name');
        $idCate = $nameIdsDB[$name];

        $db = DB::table('comments')->where([['commentable_id', '=', $id], ['category', '=', $idCate]])->get();

        $dateTimeInstance = date_create();
        $time = date_format($dateTimeInstance, "d/m/Y H:i:s");
        $tempDB = ['content' => '', 'created_at' => $time, 'updated_at' => ''];

        $dataComment = json_decode($db, true)[0] ?? $tempDB;
        return view('components.controls.comment-renderer')->with(compact('name', 'type', 'dataComment', 'id', 'action'));
    }
}
