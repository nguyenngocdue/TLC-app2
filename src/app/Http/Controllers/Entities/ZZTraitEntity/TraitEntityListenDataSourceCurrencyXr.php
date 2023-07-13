<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Act_currency;
use App\Models\Act_currency_pair;
use App\Models\Act_currency_xr;
use Illuminate\Support\Facades\DB;

trait TraitEntityListenDataSourceCurrencyXr
{
    private function getMinimalExchangeFromDB()
    {
        $sql  = "SELECT currency_xr_id, currency_pair_id, value, base_currency_id, counter_currency_id
                FROM 
                    act_currency_xrs xr, 
                    act_currency_xr_lines line,
                    act_currency_pairs pair
                WHERE 1=1
                    AND xr.id = line.currency_xr_id
                    AND pair.id = line.currency_pair_id
                ";
        $rows = DB::select($sql);
        return $rows;
    }

    private function printDebug($months, $matrix)
    {
        foreach ($months as $monthId => $month) {
            echo "MonthIS $monthId<br/>";
            foreach ($matrix[$monthId] as $row) {
                foreach ($row as $cell) {
                    // dump($cell);
                    echo $cell['a'] . "/" . $cell['b'] . " - " . sprintf("%.5f", $cell['value']) . " - ";
                }
                echo "<br/>";
            }
        }
    }

    private function enrichMatrix($rows)
    {
        $matrix = [];
        $currencies = Act_currency::all();
        // $months = [Act_currency_xr::first()];
        $months = Act_currency_xr::all();
        // dump($months);
        foreach ($months as $month) {
            foreach ($currencies as $a) {
                foreach ($currencies as $b) {
                    $matrix[$month->id][$a->id][$b->id] = [
                        'a' => $a->id,
                        'b' => $b->id,
                        'value' => ($a->id == $b->id) ? 1 : 0,
                    ];
                }
            }
        }
        $months = [];
        foreach ($rows as $row) {
            $months[$row->currency_xr_id][] = [
                'base' => $row->base_currency_id,
                'counter' => $row->counter_currency_id,
                'value' => $row->value,
            ];
        }
        foreach ($months as $monthId => $month) {
            for ($i = 0; $i < sizeof($month); $i++) {
                for ($j = 0; $j < sizeof($month); $j++) {
                    $a = $month[$i];
                    $b = $month[$j];
                    if ($a['counter'] == $b['counter']) {
                        $value = $a['value'] / $b['value'];
                        if ($matrix[$monthId][$a['base']][$b['base']]['value'] == 0) {
                            $matrix[$monthId][$a['base']][$b['base']]['value'] = round($value, 6);
                        }
                        if ($matrix[$monthId][$b['base']][$a['base']]['value']  == 0) {
                            $matrix[$monthId][$b['base']][$a['base']]['value'] = round(1 / $value, 6);
                        }
                    }
                }
            }
        }
        return $matrix;
        // dump($months);
        // dump($matrix);
        // $this->printDebug($months, $matrix);
    }

    // private function groupByMonth($rows)
    // {
    //     $data = [];
    //     foreach ($rows as $row) {
    //         // $data[$row->currency_xr_id]['month'] = $row->month;
    //         $data[$row->currency_xr_id][$row->currency_pair_id] = $row->value;
    //     }
    //     return $data;
    // }

    private function groupByMonth($matrix)
    {
        $pairs =  Act_currency_pair::all();
        // dump($pairs);
        $lookup = [];
        foreach ($pairs as $pair) {
            $lookup[$pair->base_currency_id][$pair->counter_currency_id] = $pair->id;
        }
        $data = [];
        foreach ($matrix as $monthId => $month) {
            foreach ($month as $row) {
                foreach ($row as $cell) {
                    $pairId = $lookup[$cell['a']][$cell['b']];
                    $data[$monthId][$pairId] = $cell['value'];
                }
            }
        }
        // dump($data);
        return $data;
    }

    private function attachToMonth(&$result, $data)
    {
        // dump($result['act_currency_xrs']);
        // dump($data);
        foreach ($result['act_currency_xrs'] as &$x) {
            // dump($x);
            if (isset($x['id']) && isset($data[$x['id']])) { //<<If there is the sheet for the month and that sheet has at least one line
                $itemToAttach = $data[$x['id']];
                // dump($itemToAttach);
                $x = $x + $itemToAttach; //Merge and keep keys
            }
        }
        // return $result;
    }

    private function attachCurrencyXr($result)
    {
        if (isset($result['act_currency_xrs'])) {
            $rows = $this->getMinimalExchangeFromDB();
            // dump($rows);
            $matrix = $this->enrichMatrix($rows);
            // dump($matrix);
            $data = $this->groupByMonth($matrix);
            // dump($data);
            $this->attachToMonth($result, $data);
            // dump($result['act_currency_xrs']);
        }
        return $result;
    }
}
