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
                'label' => 'New',
                'key' => 'new',
                'children' => "Content 01 - New",
            ],
            [
                'label' => 'Assigned',
                'key' => 'assigned',
                'children' => "Content 02 - Assigned",
            ],
            [
                'label' => 'Inspected',
                'key' => 'inspected',
                'children' => "Content 03 - Inspected",
            ],
        ];
    }
}
