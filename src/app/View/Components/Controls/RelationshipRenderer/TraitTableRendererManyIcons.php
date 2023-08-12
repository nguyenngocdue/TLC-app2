<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Helpers\Helper;

trait TraitTableRendererManyIcons
{
    private function renderManyIcons($colName, $type, $dataSource, $tableName)
    {
        $colSpan =  Helper::getColSpan($colName, $type);
        foreach ($dataSource as &$item) {
            $item['href'] = route($tableName . '.edit', $item->id);
            $item['gray'] = $item['resigned'];
        }
        $dataSource = $dataSource->all(); // Force LengthAwarePaginator to Array
        return view('components.controls.many-icon-params')->with(compact('dataSource', 'colSpan'));
    }
}
