<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Blade;

trait TraitDemoProgressBar
{
    public function getDataSourceProgressBar()
    {
        return [
            [
                'color' => 'red',
                'percent' => '10%',
                'label' => 'Overdue',
            ],
            [
                'color' => 'green',
                'percent' => '80%',
                'label' => '> 7 days',
            ],
            [
                'color' => 'yellow',
                'percent' => '10%',
                'label' => 'next 7 days',
            ],
        ];
    }
}
