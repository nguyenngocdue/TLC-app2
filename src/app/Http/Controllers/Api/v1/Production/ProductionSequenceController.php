<?php

namespace App\Http\Controllers\Api\v1\Production;

use App\Http\Controllers\Controller;
use App\Models\Prod_sequence;
use App\Models\User;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;


class ProductionSequenceController extends Controller
{

    public function store(Request $request)
    {
        try {
            $subProjectId = $request->sub_project_id;
            $prodOrderId = $request->prod_order_id;
            $prodRoutingLinkId = $request->prod_order_id;
            $prodSequence = Prod_sequence::create([
                'prod_order_id' => $prodOrderId,
                'prod_routing_link_id' => $prodRoutingLinkId,
                'status' => 'running',
            ]);
            return ResponseObject::responseSuccess([
                'sub_project_id' => $subProjectId,
                'prod_order_id' => $prodOrderId,
                'prod_routing_link_id' => $prodRoutingLinkId,
                'prod_sequence' => $prodSequence,
            ], null, 'Create Production Run Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Create Production Run Failed.');
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $prodSequence = Prod_sequence::find($id);
            $prodSequence->status = 'finished';
            $prodSequence->total_hours = $request->total_hours;
            $prodSequence->total_man_hours = $request->total_man_hours;
            $prodSequence->save();
            return ResponseObject::responseSuccess([], null, 'Finished Production Run Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Finished Production Run Failed.');
        }
    }
    public function stopped(Request $request, $id)
    {
        try {
            $prodSequence = Prod_sequence::find($id);
            $prodSequence->status = 'stopped';
            $prodSequence->total_hours = $request->total_hours;
            $prodSequence->total_man_hours = $request->total_man_hours;
            $prodSequence->save();
            return ResponseObject::responseSuccess([], null, 'Stopped Production Run Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Stopped Production Run Failed.');
        }
    }
}
