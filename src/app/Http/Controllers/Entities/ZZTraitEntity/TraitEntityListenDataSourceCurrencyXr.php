<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Support\Facades\DB;

trait TraitEntityListenDataSourceCurrencyXr
{
    private function attachCurrencyXr($result)
    {
        if (isset($result['act_currency_xrs'])) {
            $sql  = "SELECT * 
                FROM 
                    act_currency_xrs xr, 
                    act_currency_xr_lines line 
                WHERE 
                    xr.id = line.currency_xr_id;
                ";
            $rows = DB::select($sql);
            // dump($rows);
            $data = [];
            foreach ($rows as $row) {
                // $data[$row->currency_xr_id]['month'] = $row->month;
                $data[$row->currency_xr_id][$row->currency_pair_id] = $row->value;
            }
            // dump($data);
            foreach ($result['act_currency_xrs'] as &$x) {
                if (isset($x['id']) && isset($data[$x['id']])) { //<<If there is the sheet for the month and that sheet has at least one line
                    $itemToAttach = $data[$x['id']];
                    $x = $x + $itemToAttach; //Merge and keep keys
                }
            }
        }
        return $result;
    }
}
