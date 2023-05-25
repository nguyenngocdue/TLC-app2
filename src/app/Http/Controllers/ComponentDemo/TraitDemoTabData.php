<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Blade;

trait TraitDemoTabData
{
    function getTab1()
    {
        return [
            ['title' => 'Static',],
            ['title' => 'Data Display',],
            ['title' => 'Data Entry',],
            ['title' => 'Attachments',],
            ['title' => 'Comments',],
            ['title' => 'Editable Tables',],
            ['title' => 'Navigation',],
            ['title' => 'Feedbacks',],
        ];
    }

    function getTab2()
    {
        return [
            ['title' => 'Props',],
            ['title' => 'Relationships',],
            ['title' => 'Listeners',],
            ['title' => 'Statuses',],
        ];
    }
}
