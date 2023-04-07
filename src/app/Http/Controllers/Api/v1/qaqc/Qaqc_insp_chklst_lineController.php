<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Models\Qaqc_insp_chklst_line;
use App\Utils\System\Api\ResponseObject;

class Qaqc_insp_chklst_lineController extends Controller
{
    public function getHrefFormNcr($id)
    {
        try {
            $model = Qaqc_insp_chklst_line::findOrFail($id);
            $nameModel = Qaqc_insp_chklst_line::class;
            $projectId = $model->getProject->id ?? '';
            $subProjectId = $model->getSubProject->id ?? '';
            $prodRoutingId = $model->getProdRouting->id ?? '';
            $prodOrderId = $model->getProdOrder->id ?? '';
            $href = "/dashboard/qaqc_ncrs/create?parent_type=$nameModel&parent_id=$id&project_id=$projectId&sub_project_id=$subProjectId&prod_routing_id=$prodRoutingId&prod_order_id=$prodOrderId";
            return ResponseObject::responseSuccess(
                $href,
                [],
                'Get Href successfully!',
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
