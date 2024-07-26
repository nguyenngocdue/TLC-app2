<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Qaqc_insp_chklst;
use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowChklst
{
    use TraitSupportEntityShow;
    use TraitGetOptionPrint;

    private $nominatedListFn = "signature_qaqc_chklst_3rd_party";

    public function showChklst($id, $trashed)
    {
        // $entity = $trashed ? ($this->modelPath)::withTrashed()->findOrFail($id) : ($this->modelPath)::findOrFail($id);;
        // $modelPath = Qaqc_insp_chklst::class;
        // $entity = ($this->modelPath)::query()
        $entity = Qaqc_insp_chklst::class::query()
            ->where('id', $id)
            ->with([
                "getSheets" => function ($query) {
                    $query
                        ->whereNot('status', 'not_applicable')
                        ->with([
                            "getLines" => function ($query) {
                                $query->orderBy('order_no')
                                    ->with([
                                        "getControlGroup",
                                        "getControlType",
                                        "getControlValue",
                                        "getGroup",

                                        "insp_photos",
                                        "insp_comments",
                                    ]);
                            }
                        ])
                        ->with($this->nominatedListFn)
                        ->with('getChklst.getProdOrder.getSubProject.getProject');
                },
                // "getProdOrder.getSubProject.getProject",
            ])
            ->first();

        $entityShts = $entity->getSheets;

        $tableDataSource = [];
        foreach ($entityShts as $sheet) {
            $tableDataSource[] = $this->transformDataSource($sheet->getLines, $sheet->{$this->nominatedListFn});
        }

        $valueOptionPrint = $this->getValueOptionPrint();
        return view('components.print.print-check-list5', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $entityShts,
            'entityDataSource' => $entity,
            'type' => $this->type,
            'typePlural' => Str::plural($this->type),
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'classListOptionPrint' => ClassList::DROPDOWN,
            'valueOptionPrint' => $valueOptionPrint,
            'layout' => $this->getLayoutPrint($valueOptionPrint, 'check-list'),
            'nominatedListFn' => $this->nominatedListFn,
        ]);
    }
}
