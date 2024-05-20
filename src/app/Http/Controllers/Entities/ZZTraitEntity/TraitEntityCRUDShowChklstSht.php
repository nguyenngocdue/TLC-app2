<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowChklstSht
{
    use TraitSupportEntityShow;
    use TraitGetOptionPrint;

    private $nominatedListFn = "signature_qaqc_chklst_3rd_party";

    public function showChklstSht($id, $trashed)
    {
        $entity = $trashed ? ($this->modelPath)::withTrashed()->findOrFail($id) : ($this->modelPath)::findOrFail($id);

        $this->checkIsExternalInspectorAndNominated($entity);
        $this->checkIsCouncilMemberAndNominated($entity);

        $entityLines = $entity->getLines->sortBy('order_no');
        $entityShtSigs = $entity->{$this->nominatedListFn};
        $tableDataSource = $this->transformDataSource($entityLines, $entityShtSigs);
        $valueOptionPrint = $this->getValueOptionPrint();
        return view('components.print.print-check-sheet5', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $entity,
            'type' => $this->type,
            'typePlural' => Str::plural($this->type),
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'classListOptionPrint' => ClassList::DROPDOWN,
            'valueOptionPrint' => $valueOptionPrint,
            'layout' => $this->getLayoutPrint($valueOptionPrint, 'check-sheet'),
            'nominatedListFn' => $this->nominatedListFn,
        ]);
    }
}
