<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;

trait TraitEntityCRUDShowChklstSht
{
    use TraitSupportEntityShow;

    public function showChklstSht($id)
    {
        $entity = ($this->data)::findOrFail($id);
        $entityLines = $entity->getLines;
        $entityShtSigs = $entity->getShtSigs;
        // dd($a);
        $tableDataSource = $this->transformDataSource($entityLines, $entityShtSigs);
        return view('components.print.check-sheet5', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $entity,
            'type' => $this->type,
            'topTitle' => CurrentRoute::getTitleOf($this->type),
        ]);
    }
}
