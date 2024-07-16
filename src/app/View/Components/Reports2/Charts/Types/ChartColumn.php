<?php

namespace App\View\Components\Reports2\Charts\Types;

use App\Http\Controllers\Reports\TraitCreateSQL;
use Illuminate\View\Component;

class ChartColumn extends Component
{

    public function __construct()
    {
    }

    public function render()
    {
        return view('components.reports2.charts.types.chart-column', []);
    }
}
