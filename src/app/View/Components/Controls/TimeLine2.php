<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class TimeLine2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $modelPath,
        private $props,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $loggers = $this->queryMorph('loggers', 'loggable_type', $this->modelPath, 'loggable_id', $this->id);
        $comments = $this->queryMorph('comments', 'commentable_type', $this->modelPath, 'commentable_id', $this->id);
        $merge = $loggers->merge($comments)->sortByDesc('created_at');
        return view('components.controls.time-line2', [
            'dataSource' => $merge,
            'props' => $this->props,
        ]);
    }
    private function queryMorph($table, $type, $valueType, $typeId, $valueId)
    {
        return DB::table($table)
            ->where($type, $valueType)
            ->where($typeId, $valueId)->get();
    }
}
