<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\BigThink\ModelExtended;
use App\Models\Qaqc_insp_control_value;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityDiff
{
    function getTable01Name($tableName, $tableNames)
    {
        foreach ($tableNames as $table01Name => $tableName2) {
            if ($tableName == $tableName2) return $table01Name;
        }
    }

    function getDiffValues($request, $previousLines)
    {
        $controlValues = Qaqc_insp_control_value::all()->pluck('name', 'id')->toArray();
        $previousValue = $previousLines->pluck('qaqc_insp_control_value_id', 'id')->toArray();
        $table01Name = $this->getTable01Name('qaqc_insp_chklst_lines', $request['tableNames']);
        $toBeSavedLines = $request[$table01Name];
        // dump($toBeSavedLines);
        $currentValue = [];
        foreach ($toBeSavedLines['id'] as $index => $id) {
            $currentValue[$id] =
                [
                    'value' => $toBeSavedLines['qaqc_insp_control_value_id'][$index],
                    'name' =>  $toBeSavedLines['name'][$index],
                ];
        }

        $diff = [];
        foreach ($currentValue as $id => $array) {
            // dump($array['value'], $previousValue[$id]);
            $diff[$id] = ['name' => $array['name']];
            if ($array['value'] != $previousValue[$id]) {
                $old = $controlValues[$previousValue[$id]];
                $new = $controlValues[$array['value']] ?? "[#]" . $array['value'];
                $diff[$id]['value'] = "$old to $new";
            }
        }
        return $diff;
    }

    function getDiffComments($request, $previousLines)
    {
        $diff = [];
        return $diff;
    }

    function getDiff(ModelExtended $previousItem, Request $request)
    {
        $previousLines = $previousItem->getLines;
        $diffValues = $this->getDiffValues($request, $previousLines);
        dump($diffValues);
        $diffComments = $this->getDiffComments($request, $previousLines);
        dump($diffComments);
        dd();
    }
}
