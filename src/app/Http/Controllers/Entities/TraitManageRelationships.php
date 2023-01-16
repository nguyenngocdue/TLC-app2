<?php

namespace App\Http\Controllers\Entities;

use App\Utils\Support\JsonControls;
use App\Utils\Support\Relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

trait TraitManageRelationships
{
    private function getColumnsRelationship()
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

    private function makeBlankDefaultObjectRelationship()
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

    private function addGreenAndRedColorRelationship($a, $b)
    {
        $toBeGreen = array_diff_key($a, $b);
        $toBeRed = array_diff_key($b, $a);

        return [$toBeGreen, $toBeRed];
    }

    private function renewColumnRelationship(&$a, $b, $column)
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

    private function getDataSourceRelationship($type)
    {
        $result = $this->makeBlankDefaultObjectRelationship($type);
        $json = Relationships::getAllOf($type);
        $this->renewColumnRelationship($json, $result, 'relationship');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColorRelationship($result, $json);

        foreach ($json as $key => $columns) {
            $result[$key]["name"] = $key;
            foreach ($columns as $column => $value) {
                $result[$key][$column] = $value;
            }
        }

        foreach (array_keys($toBeGreen) as $key) $result[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $result[$key]['row_color'] = "red";

        foreach ($result as $key => $columns) {
            if (!isset($columns['row_color']) || $columns['row_color'] === "green") continue;
            // <x-renderer.button htmlType='submit' name='button' size='xs' value='up,$key'><i class='fa fa-arrow-up'></i></x-renderer.button>
            // <x-renderer.button htmlType='submit' name='button' size='xs' value='down,$key'><i class='fa fa-arrow-down'></i></x-renderer.button>
            $result[$key]['action'] = Blade::render("<div class='whitespace-nowrap'>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,$key' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>
            </div>");
        }

        return $result;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexRelationship(Request $request)
    {
        return view('dashboards.pages.manage-relationship', [
            'title' => $this->getTitle($request),
            'type' => $this->type,
            'route' => route($this->type . '_rls.store'),
            'columns' => $this->getColumnsRelationship(),
            'dataSource' => array_values($this->getDataSourceRelationship($this->type)),
        ]);
    }

    public function storeRelationship(Request $request)
    {
        $data = $request->input();
        $columns = array_filter($this->getColumnsRelationship(), fn ($column) => !in_array($column['dataIndex'], ['action']));
        $result = Relationships::convertHttpObjectToJson($data, $columns);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            Relationships::move($result, $direction, $name);
        }
        Relationships::setAllOf($this->type, $result);
        return back();
    }
}
