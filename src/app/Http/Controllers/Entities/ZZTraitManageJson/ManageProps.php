<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\JsonControls;
use App\Utils\Support\Json\Props;
use App\Utils\Support\DBTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManageProps extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-prop";
    protected $routeKey = "_prp";
    protected $jsonGetSet = Props::class;

    protected function getColumns()
    {
        $controls = JsonControls::getControls();
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "move_to",
                "align" => "center",
                "renderer" => "text",
                "editable" => true,
                "width" => 10,
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_type",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "label",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "control",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $controls,
            ],
            [
                "dataIndex" => "col_span",
                "editable" => true,
                "renderer" => "number",
                'width' => 10,
            ],
            [
                "dataIndex" => "width",
                "editable" => true,
                "renderer" => "number",
                'width' => 10,
                'title' => "Width (px)",
            ],
            [
                "dataIndex" => "hidden_view_all",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "hidden_edit",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "hidden_label",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "hidden_filter",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "hidden_print",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "new_line",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "validation",
                "editable" => true,
                "renderer" => "text",
            ],
            [
                "dataIndex" => "formula",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => [
                    '',
                    'All_ConcatNameWith123',
                    'All_SlugifyByName',
                    'User_PositionRendered',
                    '(not-yet)Wir_NameRendered',
                    '(not-yet)format_production',
                    '(not-yet)format_compliance',
                ],
                "properties" => ['strFn' => 'same'],
            ],
        ];
    }

    private function makeBlankDefaultObject()
    {
        $columnNames = DBTable::getColumnNames(Str::plural($this->type));
        $columnTypes = DBTable::getColumnTypes(Str::plural($this->type));

        $result = [];
        foreach ($columnNames as $key => $value) {
            $result["_$value"] = [
                "name" => "_$value",
                "column_name" => $value,
                "column_type" => $columnTypes[$key],
                "label" => Str::headline($value),
                "col_span" => 12,
            ];
        }
        return $result;
    }

    private function getRenderableRelationships()
    {
        $model = App::make($this->typeModel);
        $eloquentParams = $model->eloquentParams;
        $oracyParams = $model->oracyParams;
        $columnParams = $eloquentParams + $oracyParams;
        // Log::info($columnParams);

        $propEloquent = JsonControls::getManagePropEloquents();
        $allows =  [...$propEloquent, ...JsonControls::getManagePropOracies()];
        // Log::info($allows);

        $result = [];
        foreach ($columnParams as $elqName => $elqValue) {
            if (in_array($elqValue[0], $allows)) {
                $column_type = in_array($elqValue[0], $propEloquent) ? "eloquent_prop" : "oracy_prop";
                $result["_$elqName"] = [
                    "name" => "_$elqName",
                    "column_name" => "$elqName",
                    "column_type" => $column_type,
                    "label" => Str::headline($elqName),
                    "col_span" => 12,
                ];
            }
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
        $result0 = $this->makeBlankDefaultObject();
        $result1 = $this->getRenderableRelationships();
        $result = array_merge($result0, $result1);

        $json = Props::getAllOf($this->type);

        $this->renewColumn($json, $result, 'column_type');
        // Log::info($result);
        // Log::info($json);
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);
        foreach (array_keys($toBeGreen) as $key) $json[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $json[$key]['row_color'] = "red";
        foreach ($json as &$line) if (isset($line['column_type']) && $line['column_type'] === 'static') $line['row_color'] = "amber";
        // foreach ($json as &$line) if (isset($line['column_type']) && $line['column_type'] === 'oracy_prop') $line['row_color'] = "";

        foreach ($result as $key => $columns) {
            foreach ($columns as $column => $value) {
                //Make sure this only happen with current rows, not new rows
                // if (isset($json[$key]['row_color']) && $json[$key]['row_color'] !== "green") {
                //Keep label of JSON file
                if (in_array($column, ['label', 'col_span'])) continue;
                // }
                $json[$key][$column] = $value;
            }
        }

        foreach ($json as $key => $columns) {
            if (isset($columns['row_color']) && $columns['row_color'] === "green") continue;
            $this->attachActionButtons($json, $key, ['up', 'down', 'right_by_name']);
        }

        return $json;
    }

    public function create(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = [
            'name' => "_" . $name,
            'column_name' => $name,
            'column_type' => 'static',
            'title' => Str::headline($name),
            'label' => Str::headline($name),
            'col_span' => 12,
        ];
        // dump($newItems);

        $dataSource = $this->jsonGetSet::getAllOf($this->type) + $newItems;
        $this->jsonGetSet::setAllOf($this->type, $dataSource);
        return back();
    }
}
