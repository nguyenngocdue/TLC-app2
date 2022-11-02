<?php

namespace App\Http\Controllers\Api\v1\Production;

use App\Http\Controllers\Controller;
use App\Models\Prod_discipline;
use App\Models\Prod_order;
use App\Models\Sub_project;
use App\Utils\System\Api\ResponseObject;

class ProductionController extends Controller
{
    public function getSubProjects()
    {
        try {
            $subProjects = Sub_project::orderBy('id', 'ASC')->get();
            return ResponseObject::responseSuccess(
                ['sub_projects' => $subProjects],
                null,
                'Get All Sub Projects Successfully.'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Get All Sub Project Failed.');
        }
    }
    public function getProdOrders($id)
    {
        try {
            $subProjectId = $id;
            $prodOrders = Sub_project::find($id)->productionOrders()->orderBy('name', 'ASC')->get();
            return ResponseObject::responseSuccess(
                ['prod_orders' => $prodOrders, 'sub_project_id' => $subProjectId],
                null,
                'Get Production Order from Sub Project Successfully.'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Get Production Order from Sub Project Failed.');
        }
    }

    public function getProdRoutings($id1, $id2)
    {
        try {
            $subProjectId = $id1;
            $prodOrderId = $id2;
            $prodRoutings = Prod_order::find($id2)->routing;
            $prodRoutingLinks = $prodRoutings->routingLinks;
            $prodDisciplines = Prod_discipline::all();
            return ResponseObject::responseSuccess([
                'sub_project_id' => $subProjectId,
                'prod_order_id' => $prodOrderId,
                'prod_routing_links' => $prodRoutingLinks,
                'prod_disciplines' => $prodDisciplines
            ], null, 'Get Routing Links and Disciplines from Prod Orders Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Get Routing Links and Disciplines from Prod Orders Failed.');
        }
    }
}
