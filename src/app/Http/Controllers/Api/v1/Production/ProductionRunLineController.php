<?php

namespace App\Http\Controllers\Api\v1\Production;

use App\Http\Controllers\Controller;
use App\Models\Prod_run_line;
use App\Models\Prod_run;
use App\Models\Prod_user_run;
use App\Models\User;
use App\Utils\System\Api\ResponseObject;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductionRunLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function prodLine($id1, $prod_order_id, $prod_routing_link_id)
    {
        try {
            $prodRun = Prod_run::where('prod_order_id', $prod_order_id)->where('prod_routing_link_id', $prod_routing_link_id)->get();
            $prodRunFirst = Prod_run::where('prod_order_id', $prod_order_id)->where('prod_routing_link_id', $prod_routing_link_id)->first();
            $users = User::orderBy('id', 'DESC')->get();
            $skills = User::orderBy('staff_position', 'DESC')->distinct()->get(['staff_position']);
            $prodLines = Prod_run::find($prodRunFirst->id)->productionRunLives()->get();
            $lastId = Prod_run_line::orderBy('id', 'DESC')->first();
            $timeNow = Carbon::now()->format('H:i:s');
            return ResponseObject::responseSuccess([
                'users' => $users,
                'skills' => $skills,
                'prod_run_lines' => $prodLines,
                'lastId' => $lastId,
                'time_now' => $timeNow,
                'prod_order_id' => $prod_order_id,
                'prod_routing_link_id' => $prod_routing_link_id,
                'prod_run_first' => $prodRunFirst,
                'id1' => $id1
            ], null, 'Get Production Run Line Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Get Production Run Line Failed.');
        }
    }
    // public function search(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $output = '';
    //         $users = User::where('name', 'LIKE', '%' . $request->get('query') . '%')->get();
    //         foreach ($users as $user) {
    //             $output .= '<li class="list-inline-item" data-id="' . $user->uid . '" skill="' . $user->skill . '">
    //                             <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    //                                 <div class="image">
    //                                 <img src="' . $user->avatar . '" class="img-circle elevation-2" alt="User Image">
    //                                 </div>
    //                                 <span class="d-block pl-1">' . $user->name . '</span>
    //                             </div>
    //                         </li>';
    //         }
    //     }
    //     return json_encode($output);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dt = Carbon::now();
            $prodRunLine = Prod_run_line::create([
                'prod_run_id' => $request->prod_run_id,
                'date' => $dt->format('Y-m-d'),
                'start' => $dt->format('H:i:s'),
                "status" => 'running',

            ]);
            foreach ($request->user_ids as $userId) {
                $prodUserRun = Prod_run_line::create([
                    'prod_run_line_id' => $prodRunLine->id,
                    'user_id' => (int)$userId,
                ]);
            }
            return ResponseObject::responseSuccess(['test' => $prodUserRun], null, 'Create Production Run Line Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail($th);
        }
    }

    public function duplicate($id)
    {
        // try {
        //     $dt = Carbon::now();
        //     $prodLine = Prod_run_line::find($id);
        //     $newProdLine = $prodLine->replicate();
        //     $newProdLine->date = $dt->format('Y-m-d');
        //     $newProdLine->start = $dt->format('H:i:s');
        //     $newProdLine->end = null;
        //     $newProdLine->status = 'running';
        //     $newProdLine->save();
        //     $prodUserRuns = Prod_user_run::where('prod_run_id', $id)->get();
        //     foreach ($prodUserRuns as $prodUserRun) {
        //         $newProdUserRun = $prodUserRun->replicate();
        //         $newProdUserRun->run_id = $newProdLine->id;
        //         $newProdUserRun->save();
        //     }
        //     return ResponseObject::responseSuccess([], null, 'Duplicate Production Run Line Successfully.');
        // } catch (\Throwable $th) {
        //     return ResponseObject::responseFail('Duplicate Production Run Line Failed.');
        // }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            $prodRunLine = Prod_run_line::find($id);
            return ResponseObject::responseSuccess(['prod_run_line' => $prodRunLine], null, 'Get Production Run Line Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Get Production Run Line Failed.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $prodRunLine = Prod_run_line::find($id);
            if ($prodRunLine->status == 'running') {
                $dt = Carbon::now();
                $prodRunLine->end = $dt->format('H:i:s');
                $prodRunLine->status = 'complete';
                $prodRunLine->save();
                return ResponseObject::responseSuccess([], null, 'Pause Production Run Complete!');
            } else {
                $prodRunLine = Prod_run_line::find($id)->update($request->all());
                return ResponseObject::responseSuccess(['prod_run_line' => $prodRunLine, 'request' => $request->all(), 'prod_run_line_id' => $id], null, 'Update Production Run Complete');
            }
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Update Production Run Failed.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Prod_run_line::find($id)->delete();
            return ResponseObject::responseSuccess([], null, 'Remove Production Run Line Successfully.');
        } catch (\Throwable $th) {
            return ResponseObject::responseFail('Remove Production Run Line Failed.');
        }
    }
}
