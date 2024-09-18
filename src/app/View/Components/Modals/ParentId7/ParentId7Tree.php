<?php

namespace App\View\Components\Modals\ParentId7;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ParentId7Tree extends Component
{
    protected $jsFileName = 'parentId7Tree.js';

    public function __construct(
        private $inputId,
    ) {}

    protected function getDataSource()
    {
        return [
            ["id" => "root_0", "parent" => "#", "text" => "Root 01 - Sample of Tree"],
            ["id" => "sub_01", "parent" => "root_0", "text" => "Sub 01"],
            ["id" => "sub_01a", "parent" => "sub_01", "text" => "Sub 01 A"],
            ["id" => "sub_01b", "parent" => "sub_01", "text" => "Sub 01 B"],
            ["id" => "sub_02", "parent" => "root_0", "text" => "Sub 02"],
            ["id" => "sub_02a", "parent" => "sub_02", "text" => "Sub 02 A"],
            ["id" => "sub_02b", "parent" => "sub_02", "text" => "Sub 02 B"],
        ];
    }

    function render()
    {
        return view(
            'components.modals.parent-id7.parent-id7-tree',
            [
                'jsFileName' => $this->jsFileName,
                'inputId' => $this->inputId,

                'treeSource' => $this->getDataSource(),
            ]
        );
    }
}
