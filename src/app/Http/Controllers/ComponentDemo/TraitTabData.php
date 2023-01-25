<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Blade;

trait TraitTabData
{
    function getTab1()
    {
        $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,1' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>";
        $result = join("", $results);
        $a = "<div class='whitespace-nowrap'>$result</div>";
        return [
            [
                'label' => 'Tab 1',
                'children' => Blade::render($a),
            ],
            [
                'label' => 'Tab 2',
                'children' => "Content 02",
            ],
            [
                'label' => 'Tab 3',
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
