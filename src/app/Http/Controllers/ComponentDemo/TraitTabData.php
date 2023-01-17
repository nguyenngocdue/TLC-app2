<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitTabData
{
    function getTab1()
    {
        return [
            [
                'label' => 'Tab 1',
                'key' => 'tab-1',
                'children' => "Content 01",
            ],
            [
                'label' => 'Tab 2',
                'key' => 'tab-2',
                'children' => "Content 02",
            ],
            [
                'label' => 'Tab 3',
                'key' => 'tab-3',
                'children' => "Content 03",
            ],
        ];
    }

    function getTab2()
    {
        return [
            [
                'label' => 'Props',
                'key' => 'Props',
                'children' => "Content 01 - Props",
            ],
            [
                'label' => 'Relationships',
                'key' => 'Relationships',
                'children' => "Content 02 - Relationships",
            ],
            [
                'label' => 'Listeners',
                'key' => 'Listeners',
                'children' => "Content 03 - Listeners",
            ],
            [
                'label' => 'Statuses',
                'key' => 'Statuses',
                'children' => "Content 03 - Statuses",
            ],
        ];
    }
}
