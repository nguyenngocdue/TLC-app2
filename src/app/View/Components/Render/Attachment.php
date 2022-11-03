<?php

namespace App\View\Components\Render;

use Illuminate\Support\Facades\App;
use Illuminate\View\Component;

class Attachment extends Component
{
    public $attachment, $model, $relationship;
    public function __construct($attachment, $model, $relationship)
    {
        $this->attachment = $attachment;
        $this->model = $model;
        $this->relationship = $relationship;
    }

    public function render()
    {
        $dataModel = App::make($this->model)::find($this->attachment)->{$this->relationship};
        $countCheck = $dataModel->count();
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        if ($countCheck == 0) {
            $items = $dataModel->all();
            return view('components.renderer.attachment')->with(compact('items', 'path'));;
        } else if ($countCheck > 3) {
            $items = $dataModel->all();
            // dd($items);
            $itemShows = array_slice($items, 0, 3);
            $countRemaining =  count($items) - count($itemShows);
            return view('components.renderer.attachment')->with(compact('items', 'itemShows', 'countRemaining', 'path'));
        } else {
            $items = $dataModel->all();
            return view('components.renderer.attachment')->with(compact('items', 'path'));
        }
    }
}
