<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\Props;
use App\Utils\Support\JsonControls;
use App\Utils\Support\Json\Relationships;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ManageRelationships extends Manage_Parent
{
    use TraitPropAndRelationship;

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
                "renderer" => "read-only-text",
                "editable" => true,
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
                "dataIndex" => "control",
                "editable" => true,
                "renderer" => "read-only-text",
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
                "renderer" => "number",
            ],
        ];
    }

    private function makeBlankDefaultObject()
    {
        $model = App::make($this->typeModel);
        $eloquentParams = $model->eloquentParams;
        $oracyParams = $model->oracyParams;
        $columnParams = $eloquentParams + $oracyParams;

        $allProps = Props::getAllOf($this->type);

        $result = [];
        foreach ($columnParams as $elqName => $elqValue) {
            $rowDescription = "$elqName => " . join(" | ", $elqValue);
            $control_name = Relationships::getColumnName("_" . $elqName,  $elqValue[0], $columnParams);

            if (!isset($allProps["_" . $control_name])) {
                dump("Cannot find $control_name in $elqName, maybe create it in ManageProps screen.");
            } else {
                $result["_$elqName"] = [
                    "name" => "_$elqName",
                    "eloquent" => $elqName,
                    "relationship" => $elqValue[0],
                    'control_name' => $control_name,
                    'control' => $allProps["_" . $control_name]['control'],
                    "rowDescription" => $rowDescription,
                ];
            }
        }

        return $result;
    }

    protected function getDataSource()
    {
        $json = Relationships::getAllOf($this->type);
        $result = $this->makeBlankDefaultObject($this->type);
        $this->renewColumn($json, $result, 'relationship');
        $this->renewColumn($json, $result, 'control_name');
        $this->renewColumn($json, $result, 'control');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);

        foreach ($json as $key => $columns) {
            $result[$key]["name"] = $key;
            foreach ($columns as $column => $value) {
                $result[$key][$column] = $value;
            }
        }

        foreach ($json as $key => $columns) {
            $result[$key]["name"] = $key;
            foreach ($columns as $column => $value) {
                if ($column === 'radio_checkbox_colspan') {
                    if (isset($json[$key]['control'])) {
                        $control = ($json[$key]['control']);
                        if (!in_array($control, ['radio', 'checkbox'])) {
                            $result[$key][$column] = 'DO_NOT_RENDER';
                        }
                    }
                }
                if (in_array($column, ['filter_columns', 'filter_values'])) {
                    if (isset($json[$key]['control'])) {
                        $control = ($json[$key]['control']);
                        if (!in_array($control, ['radio', 'checkbox', 'dropdown', 'dropdown_multi'])) {
                            $result[$key][$column] = 'DO_NOT_RENDER';
                        }
                    }
                }
            }
        }

        foreach (array_keys($toBeGreen) as $key) $result[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $result[$key]['row_color'] = "red";

        $this->attachButtons($result, ['right_by_name']);
        return $result;
    }
}
