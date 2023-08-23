<?php

namespace App\View\Components\Renderer\Report;


use Illuminate\View\Component;

class ListEmptyItems extends Component
{
    public function __construct(
        private $dataSource = [],
        private $title ='empty title',
        private $span = 3
    ) {
    }


    public function render()
    {
        $dataSource = $this->dataSource;
        // dd($dataSource/$this->span);
        $dataSource = array_chunk($dataSource, ($x = count($dataSource)/$this->span) ? $x : 1);
        return view("components.renderer.report.list-empty-items", [
            'dataSource' => $dataSource,
            'title' => $this->title,
            'span' => $this->span,
        ]);
    }
}
