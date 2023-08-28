<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowChklst
{
    use TraitSupportEntityShow;
    use TraitGetOptionPrint;
    public function showChklst($id, $trashed)
    {
        $entity = $trashed ? ($this->modelPath)::withTrashed()->findOrFail($id) : ($this->modelPath)::findOrFail($id);;
        $entityShts = $entity->getSheets;
        foreach ($entityShts as $value) {
            $tableDataSource[] = $this->transformDataSource($value->getLines->sortBy('order_no'), $value->getShtSigs);
        }
        $valueOptionPrint = $this->getValueOptionPrint();
        return view('components.print.check-list5', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $entityShts,
            'entityDataSource' => $entity,
            'type' => $this->type,
            'typePlural' => Str::plural($this->type),
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'classListOptionPrint' => ClassList::DROPDOWN,
            'valueOptionPrint' => $valueOptionPrint,
            'layout' => $this->getLayoutPrint($valueOptionPrint, 'check-list')
        ]);
    }
}
