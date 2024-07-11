<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class TableBlockReport extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('components.reports2.table-block-report', []);
    }
}
