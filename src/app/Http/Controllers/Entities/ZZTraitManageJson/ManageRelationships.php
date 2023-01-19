<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\JsonControls;
use App\Utils\Support\Relationships;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ManageRelationships extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-relationship";
    protected $routeKey = "_rls";
    protected $jsonGetSet = Relationships::class;

    protected function getColumns()
    {
        $viewAllControls = JsonControls::getRendererViewAll();
        $editControls = JsonControls::getRendererEdit();
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "relationship",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "control_name",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "renderer_view_all",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $viewAllControls,
            ],
            [
                "dataIndex" => "renderer_view_all_param",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "renderer_edit",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $editControls,
            ],
            [
                "dataIndex" => "renderer_edit_param",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "filter_columns",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "filter_values",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "radio_checkbox_colspan",
                "editable" => true,
                "renderer" => "text",
            ],
        ];
    }

    private function makeBlankDefaultObject()
    {
        $model = App::make($this->typeModel);
        $eloquentParams = $model->eloquentParams;
        $oracyParams = $model->oracyParams;
        $columnParams = $eloquentParams + $oracyParams;

        $result = [];
        foreach ($columnParams as $elqName => $elqValue) {
            $rowDescription = join(" | ", $elqValue);
            $result["_$elqName"] = [
                "name" => "_$elqName",
                "eloquent" => $elqName,
                "relationship" => $elqValue[0],
                "rowDescription" => $rowDescription,
            ];
        }

        return $result;
    }

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
            if ($a[$key][$column] != $updatedValue) {
                $a[$key][$column] = $updatedValue;
                $a[$key]['row_color'] = 'blue';
            }
        }
    }

    protected function getDataSource()
    {
        $result = $this->makeBlankDefaultObject($this->type);
        $json = Relationships::getAllOf($this->type);
        $this->renewColumn($json, $result, 'relationship');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);

        foreach ($json as $key => $columns) {
            $result[$key]["name"] = $key;
            foreach ($columns as $column => $value) {
                $result[$key][$column] = $value;
            }
        }

        foreach (array_keys($toBeGreen) as $key) $result[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $result[$key]['row_color'] = "red";

        foreach ($result as $key => $columns) {
            if (isset($columns['row_color']) && $columns['row_color'] === "green") continue;
            $this->attachActionButtons($result, $key, ['right_by_name']);
        }

        return $result;
    }
}
