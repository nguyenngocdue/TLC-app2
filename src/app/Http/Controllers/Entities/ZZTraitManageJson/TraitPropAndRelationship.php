<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

trait TraitPropAndRelationship
{
    private function addGreenAndRedColor($a, $b)
    {
        $toBeGreen = array_diff_key($a, $b);
        $toBeRed = array_diff_key($b, $a);

        return [$toBeGreen, $toBeRed];
    }

    private function renewColumn(&$a, $b, $column)
    {
        foreach (array_keys($a) as $key) {
            if (!isset($b[$key])) continue;
            $updatedValue = $b[$key][$column];
            if (isset($a[$key][$column])) {
                if ($a[$key][$column] != $updatedValue) {
                    $a[$key][$column] = [
                        'title' => $a[$key][$column] . " => " . $updatedValue,
                        'value' => $updatedValue,
                    ];
                    $a[$key]['row_color'] = 'blue';
                }
            }
        }
    }

    private function attachButtons(&$json, $buttons)
    {
        foreach ($json as $key => &$columns) {
            if (isset($columns['row_color']) && $columns['row_color'] === "green") continue;
            $this->attachActionButtons($json, $key, $buttons);
        }
    }
}
