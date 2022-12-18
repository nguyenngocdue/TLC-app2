<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Utils\Support\JsonControls;
use App\Utils\Support\Props;
use App\Utils\Support\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractPropController extends Controller
{
    protected $type = "";
    protected $typeModel = "";
    public function __construct()
    {
    }

    public function getType()
    {
        return $this->type;
    }

    private function getColumns()
    {
        $controls = JsonControls::getControls();
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
            ],
            [
                "dataIndex" => "hidden_view_all",
                "editable" => true,
                "renderer" => "dropdown",
            ],
            [
                "dataIndex" => "hidden_edit",
                "editable" => true,
                "renderer" => "dropdown",
            ],
            [
                "dataIndex" => "new_line",
                "editable" => true,
                "renderer" => "dropdown",
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
                "attributes" => ['strFn' => 'same'],
            ],
            // [
            //     "dataIndex" => "frozen_left",
            //     "editable" => true,
            //     "renderer" => "dropdown",
            // ],
            // [
            //     "dataIndex" => "frozen_right",
            //     "editable" => true,
            //     "renderer" => "dropdown",
            // ],

        ];
    }

    private function makeBlankDefaultObject()
    {
        $columnNames = Table::getColumnNames(Str::plural($this->type));
        $columnTypes = Table::getColumnTypes(Str::plural($this->type));

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
        $columnEloquentParams = App::make($this->typeModel)->eloquentParams;

        $result = [];
        foreach ($columnEloquentParams as $elqName => $elqValue) {
            if (in_array($elqValue[0], JsonControls::getManagePropEloquents())) {
                $result["_$elqName"] = [
                    "name" => "_$elqName",
                    "column_name" => "$elqName",
                    "column_type" => "ELQ(" . $elqValue[0] . ")",
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

    private function getDataSource()
    {
        $result0 = $this->makeBlankDefaultObject();
        $result1 = $this->getRenderableRelationships();
        $result = array_merge($result0, $result1);

        $json = Props::getAllOf($this->type);

        $this->renewColumn($json, $result, 'column_type');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);
        foreach (array_keys($toBeGreen) as $key) $json[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $json[$key]['row_color'] = "red";
        foreach ($json as &$line) if (isset($line['column_type']) && $line['column_type'] === 'static') $line['row_color'] = "amber";

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
            $json[$key]['action'] = Blade::render("<div class='whitespace-nowrap'>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='up,$key'><i class='fa fa-arrow-up'></i></x-renderer.button>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='down,$key'><i class='fa fa-arrow-down'></i></x-renderer.button>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,$key' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>
            </div>");
        }

        return $json;
    }

    public function index()
    {
        $type = $this->type;
        $columns = $this->getColumns();
        $dataSourceWithKey = $this->getDataSource();
        $dataSource = array_values($dataSourceWithKey);
        $route = route($type . '_prop.store');
        return view('dashboards.pages.manageprop')->with(compact('type', 'columns', 'dataSource', 'route'));
    }

    public function store(Request $request)
    {
        $data = $request->input();
        $columns = array_filter($this->getColumns(), fn ($column) => !in_array($column['dataIndex'], ['action']));
        $result = Props::convertHttpObjectToJson($data, $columns);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            // Log::info($direction . " " . $name);
            Props::move($result, $direction, $name);
        }
        Props::setAllOf($this->type, $result);
        return back();
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

        $dataSource = Props::getAllOf($this->type) + $newItems;
        Props::setAllOf($this->type, $dataSource);
        return redirect()->back();
    }
}
