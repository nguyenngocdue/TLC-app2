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
        private $btnUpload = false,
        private $showToBeDeleted = false,
        private $path = Constant::PATH,
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
        $data = $this->dataComment + ['readonly' => $this->readonly];
        $user = User::find($data['owner_id']);
        $labelName = $this->labelName;
        $path = $this->path;


        $commentUser = ModelsComment::find($data['id']);

        $attachmentData = [];
        if (!is_null($commentUser)) {
            $attachmentData = [$name => $commentUser->media()->get()->toArray()];
        }

        $showbtnUpload = $this->btnUpload  ? "<x-controls.uploadfiles id={$id} colName={$name} action={$action} labelName={$this->labelName} />" : "";

        // dump($attachmentData);
        $showToBeDeleted = $this->showToBeDeleted;
        return view('components.renderer.comment')->with(compact('labelName', 'id', 'name', 'type', 'data', 'action', 'user', 'showbtnUpload', 'attachmentData', 'showToBeDeleted', 'path'));
    }
}
