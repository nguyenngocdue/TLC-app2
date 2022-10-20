<?php

namespace App\Http\Controllers\Api\v1\Production;

use App\Http\Controllers\Controller;
use App\Models\Prod_run;
use App\Models\User;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;


class ProductionRunController extends Controller
{

    public function store(Request $request)
    {
        try {
            $subProjectId = $request->sub_project_id;
            $prodOrderId = $request->prod_order_id;
            $prodRoutingLinkId = $request->prod_order_id;
            $prodRun = Prod_run::create([
                'prod_order_id' => $prodOrderId,
                'prod_routing_link_id' => $prodRoutingLinkId,
                'status_prod' => 'running',
            ]);
            return ResponseObject::responseSuccess([
                'sub_project_id' => $subProjectId,
                'prod_order_id' => $prodOrderId,
                'prod_routing_link_id' => $prodRoutingLinkId,
                'prod_run' => $prodRun,
            ], null, 'Create Production Run Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Create Production Run Failed.');
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $prodRun = Prod_run::find($id);
            $prodRun->status_prod = 'finished';
            $prodRun->total_hours = $request->total_hours;
            $prodRun->total_man_hours = $request->total_man_hours;
            $prodRun->save();
            return ResponseObject::responseSuccess([], null, 'Finished Production Run Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Finished Production Run Failed.');
        }
    }
    public function stopped(Request $request, $id)
    {
        try {
            $prodRun = Prod_run::find($id);
            $prodRun->status_prod = 'stopped';
            $prodRun->total_hours = $request->total_hours;
            $prodRun->total_man_hours = $request->total_man_hours;
            $prodRun->save();
            return ResponseObject::responseSuccess([], null, 'Stopped Production Run Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Stopped Production Run Failed.');
        }
    }
}
