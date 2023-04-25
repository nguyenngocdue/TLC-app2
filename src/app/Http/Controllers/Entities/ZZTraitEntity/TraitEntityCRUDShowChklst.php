<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;

trait TraitEntityCRUDShowChklst
{
    use TraitSupportEntityShow;
    public function showChklst($id)
    {
        $entity = ($this->data)::findOrFail($id);
        $entityShts = $entity->getSheets;
        foreach ($entityShts as $value) {
            $tableDataSource[] = $this->transformDataSource($value->getLines, $value->getShtSigs);
        }
        return view('components.print.check-list5', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $entityShts,
            'entityDataSource' => $entity,
            'type' => $this->type,
            'topTitle' => CurrentRoute::getTitleOf($this->type),
        ]);
    }
}
