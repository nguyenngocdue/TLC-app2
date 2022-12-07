<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Comment extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
        private $type = '',
        private $id = '',
        private $readonly = true,
        private $dataComment = [],
        private $action = 'create',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $ownerDB = Auth::user();
        $name = $this->name;
        $type = $this->type;
        $readonly = $this->readonly;
        $dataComment = $this->dataComment;
        $action = $this->action;
        // dd($dataComment);


        $commentCatesDB = DB::table('comment_categories')->select("id", 'name')->get();
        $json = json_decode($commentCatesDB, true);
        $nameIdsDB = array_column($json, 'name', 'id');

        $array = [];
        foreach ($dataComment as $value) {
            $array[$nameIdsDB[$value['category']]] = [
                'content' => $value['content'],
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at']
            ];
        }
        $dataComment = $array;
        // dd($dataComment, $name);
        return view('components.renderer.comment')->with(compact('name', 'type', 'readonly', 'ownerDB', 'dataComment', 'action'));
    }
}
