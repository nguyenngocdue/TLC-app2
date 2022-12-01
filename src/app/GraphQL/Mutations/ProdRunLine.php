<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_run_line;
use App\Models\Prod_run;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

final class ProdRunLine
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $prodRun = Prod_run::where('prod_order_id', $args['prod_order_id'])->where('prod_routing_link_id', $args['prod_routing_link_id'])->get();
            $prodRunFirst = Prod_run::where('prod_order_id', $args['prod_order_id'])->where('prod_routing_link_id', $args['prod_routing_link_id'])->first();
            $users = User::orderBy('id', 'DESC')->get();
            $skills = User::orderBy('position_rendered', 'DESC')->distinct()->get(['position_rendered']);
            $prodLines = Prod_run::find($prodRunFirst->id)->productionRunLines()->get();
            $prodLineLast = Prod_run_line::orderBy('id', 'DESC')->first();
            $timeNow = Carbon::now()->format('H:i:s');
            return [
                'users' => $users,
                'skills' => $skills,
                'prod_run_lines' => $prodLines,
                'prod_run_line_last' => $prodLineLast,
                'time_now' => $timeNow,
                'prod_order_id' => $args['prod_order_id'],
                'prod_routing_link_id' => $args['prod_routing_link_id'],
                'prod_run_first' => $prodRunFirst,
                'status' => 'Get Production Run Line Successfully.'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => $th
            ];
        }
    }
}
