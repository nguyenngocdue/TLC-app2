<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Blade;

trait TraitTabData
{
    function getTab1()
    {
        return [
            [
                'label' => 'Static',
                'children' => "Content 01",
            ],
            [
                'label' => 'Data Display',
                'children' => "Content 02",
            ],
            [
                'label' => 'Data Entry',
                'children' => "Content 03",
            ],
            [
                'label' => 'Attachments',
                'children' => "Content 03",
            ],
            [
                'label' => 'Comments',
                'children' => "Content 03",
            ],
            [
                'label' => 'Editable Tables',
                'children' => "Content 03",
            ],
            [
                'label' => 'Navigation',
                'children' => "Content 03",
            ],
            [
                'label' => 'Feedbacks',
                'children' => "Content 03",
            ],
        ];
    }

    function getTab2()
    {
        return [
            [
                'label' => 'Props',
                'children' => "Content 01 - Props",
            ],
            [
                'label' => 'Relationships',
                'children' => "Content 02 - Relationships",
            ],
            [
                'label' => 'Listeners',
                'children' => "Content 03 - Listeners",
            ],
            [
                'label' => 'Statuses',
                'children' => "Content 03 - Statuses",
            ],
        ];
    }
}
