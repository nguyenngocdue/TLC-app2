<?php

namespace App\Http\Services;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MatrixFilterParamService
{
    public static function get($pluralType = null, $modelId = null)
    {
        $pluralType = $pluralType ?: CurrentRoute::getTypePlural();
        $modelId = $modelId ?: CurrentRoute::getEntityId($pluralType);

        $model = Str::modelPathFrom($pluralType);
        $sheet = $model::find($modelId);
        $params = [];
        switch ($pluralType) {
            case "qaqc_insp_chklst_shts":
            case "qaqc_punchlists":
                $chklst = $sheet->getChklst;
                $params['qaqc_insp_tmpl_id'] = $chklst->qaqc_insp_tmpl_id;
                $prodOrder = $chklst->getProdOrder;

                $params['sub_project_id'] = $prodOrder->sub_project_id;
                $params['project_id'] = $prodOrder->getSubProject->project_id;
                $params['prod_routing_id'] = $prodOrder->prod_routing_id;
                break;
            default:
                // dump("Unknown " . $pluralType . " MatrixFilterParamService");
                break;
        }
        return $params;
    }
}
