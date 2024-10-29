<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Qaqc_insp_chklst;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowChklst2
{
    use TraitGetOptionPrint;

    private $nominatedListFn = "signature_qaqc_chklst_3rd_party";

    public function showChklst2($id, $trashed)
    {


        $entity = Qaqc_insp_chklst::query();
        if ($trashed) $entity = $entity->withTrashed();
        $entity = $entity
            ->where('id', $id)
            ->with([
                'getSheets',
                'getProdOrder.getSubProject.getProject',
                'getProdRouting',
                'getQaqcInspTmpl',
            ])
            ->first();
        // dump($entity);

        $project = $entity->getProdOrder->getSubProject->getProject ?? null;
        $coverPageTitle = $project ? $project->name . " (" . $project->description . ")" : "Unknown Project";
        $coverPageDataSource = [
            'checklist_name' => $entity->getQaqcInspTmpl->short_name,
            'product_type' => $entity->getProdRouting->name,
            'product_name' => $entity->getProdOrder->name,
        ];

        return view("dashboards.pages.entity-show-chklst", [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'type' => $this->type,
            'entity' => $entity,

            'coverAvatar' => "/images/modules.png",
            'coverTitle' => $coverPageTitle,
            'coverDataSource' => $coverPageDataSource,
        ]);
    }
}
