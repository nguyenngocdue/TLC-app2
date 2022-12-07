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

        $tableName = (new Comment)->getTable();
        $db = DB::table($tableName)->where('commentable_id', $this->id)->get();
        $dataComment = json_decode($db, true);
        // dump($dataComment, $name);
        return view('components.controls.comment-renderer')->with(compact('name', 'type', 'dataComment', 'id', 'action'));
    }
}
