<?php

namespace App\View\Components\Render;

use Illuminate\Support\Facades\App;
use Illuminate\View\Component;

class Attachment extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $attachment, $model, $relationship;
    public function __construct($attachment, $model, $relationship)
    {
        $this->attachment = $attachment;
        $this->model = $model;
        $this->relationship = $relationship;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataModel = App::make($this->model)::find($this->attachment)->{$this->relationship};
        $countCheck = $dataModel->count();
        $path = env('AWS_ENDPOINT', 'http://192.168.100.100:9000') . '/' . env('AWS_BUCKET', 'hello-001') . '/';
        if ($countCheck == 0) {
            $items = $dataModel->all();
            return view('components.render.attachment')->with(compact('items', 'path'));;
        } else if ($countCheck > 3) {
            $items = $dataModel->all();
            $itemShows = array_slice($items, 0, 3);
            $countRemaining =  count($items) - count($itemShows);
            return view('components.render.attachment')->with(compact('items', 'itemShows', 'countRemaining', 'path'));
        } else {
            $items = $dataModel->all();
            return view('components.render.attachment')->with(compact('items', 'path'));
        }
    }
}
