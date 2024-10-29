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

        if ($this->type == 'qaqc_insp_chklst_sht') {
            $this->checkIsExternalInspectorAndNominated($entity);
            $this->checkIsCouncilMemberAndNominated($entity);
        }

        $project = $entity->getProdOrder->getSubProject->getProject ?? null;
        if ($project->getAvatar) {
            $coverAvatar = app()->pathMinio() . $project->getAvatar->url_media;
        } else {
            $coverAvatar = "/images/modules.png";
        }

        $coverPageTitle = $project ? $project->description . " (" . $project->name . ")" : "Unknown Project";
        $coverPageDataSource = [
            'Checklist Name' => $entity->getQaqcInspTmpl->short_name,
            'Product Type' => $entity->getProdRouting->name,
            'Product Name' => $entity->getProdOrder->name,
        ];

        return view("dashboards.pages.entity-show-chklst", [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'type' => $this->type,
            'entity' => $entity,

            'coverAvatar' => $coverAvatar,
            'coverTitle' => $coverPageTitle,
            'coverDataSource' => $coverPageDataSource,
        ]);
    }
}
