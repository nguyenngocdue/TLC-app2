<?php

namespace App\View\Components\Renderer;

use App\Models\Comment as ModelsComment;
use App\Models\User;
use App\Utils\Constant;
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
        private $required = false,
        private $dataComment = [],
        private $action = 'create',
        private $labelName = '',
        private $showToBeDeleted = false,
        private $destroyable = false,
        private $attachmentData = [],
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $name = $this->name;
        $type = $this->type;
        $action = $this->action;
        $id = $this->id;
        $labelName = $this->labelName;
        $destroyable = $this->destroyable;
        $tempData =
            [
                "id" => 0,
                "content" => "Not found data",
                "owner_id" => Auth::user()->id,
                "created_at" => date_format(date_create(), "d/m/Y H:i:s"),
                'readonly' => false,
            ];
        $data = !count($this->dataComment) ? $tempData : $this->dataComment + ['readonly' => (bool)$this->readonly];
        $user =  User::find($data['owner_id']);

        $commentUser = ModelsComment::find($data['id']);
        $attachmentData = $this->attachmentData;
        if (!is_null($commentUser) && count($attachmentData) === 0) {
            $attachmentData = [$name => $commentUser->media()->get()->toArray()];
        }
        $showToBeDeleted = $this->showToBeDeleted;
        // dump('DataComment', $data);
        // dump($attachmentData);
        // dd($attachmentData);
        return view('components.renderer.comment')->with(compact('labelName', 'id', 'name', 'type', 'data', 'action', 'user', 'attachmentData', 'showToBeDeleted', 'destroyable'));
    }
}
