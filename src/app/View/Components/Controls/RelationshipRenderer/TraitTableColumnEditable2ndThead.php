<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\View\Components\Renderer\TableTraitCommon;
use Illuminate\Support\Facades\Blade;

trait TraitTableColumnEditable2ndThead
{
    use TableTraitCommon;
    private function makeEditable2ndThead($columns, $tableId)
    {
        // dump($columns);
        $result = [];
        $copyIcon = '<i class="fa-duotone fa-copy text-blue-500"></i>';
        foreach ($columns as $column) {
            if (isset($column['cloneable']) && $column['cloneable']) {
                $dataIndex = $column['dataIndex'];
                $button = "";
                $button .= "<x-renderer.button size='xs' value='xxx456' onClick=\"cloneFirstLineDown('$dataIndex', '$tableId')\" title='Clone value of the first line'>";
                $button .= $copyIcon;
                $button .= "</x-renderer.button>";
                $result[$dataIndex] = Blade::render($button);
            }
        }
        return $result;
    }
}
