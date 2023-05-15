<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowChklstSht
{
    use TraitSupportEntityShow;
    use TraitGetOptionPrint;

    public function showChklstSht($id)
    {
        $entity = ($this->data)::findOrFail($id);
        $entityLines = $entity->getLines->sortBy('order_no');
        $entityShtSigs = $entity->getShtSigs;
        // dd($a);
        $tableDataSource = $this->transformDataSource($entityLines, $entityShtSigs);
        return view('components.print.check-sheet5', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $entity,
            'type' => $this->type,
            'typePlural' => Str::plural($this->type),
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'classListOptionPrint' => ClassList::DROPDOWN,
            'valueOptionPrint' => $this->getValueOptionPrint(),
        ]);
    }
}
