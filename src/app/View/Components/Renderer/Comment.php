<?php

namespace App\View\Components\Renderer;

use App\Models\User;
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

        $data = $this->dataComment + ['readonly' => $this->readonly];
        $user = User::find($data['owner_id']);
        // dump($data);
        return view('components.renderer.comment')->with(compact('name', 'type', 'data', 'action', 'user'));
    }
}
