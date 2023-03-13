<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\JsonControls;
use App\Utils\Support\Json\Props;
use App\Utils\Support\DBTable;
use App\Utils\Support\Json\Relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManageProps extends Manage_Parent
{
    use TraitPropAndRelationship;
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
                "renderer" => "text4",
                "editable" => true,
                "width" => 10,
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_type",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "label",
                "renderer" => "text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "control",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $controls,
            ],
            [
                "dataIndex" => "align",
                'title' => "Align (Static Only)",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => [''],
            ],
            [
                "dataIndex" => "col_span",
                "editable" => true,
                "renderer" => "number4",
                'width' => 10,
                'properties' => ['placeholder' => 12],
            ],
            [
                "dataIndex" => "width",
                "editable" => true,
                "renderer" => "number4",
                'width' => 10,
                'title' => "Width (px)",
                'properties' => ['placeholder' => 100],
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
                "dataIndex" => "read_only",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 10,
            ],
            [
                "dataIndex" => "save_on_change",
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

    private function getAllMorphTo()
    {
        $relationships = Relationships::getAllOf($this->type);
        // dump($relationships);
        $dummyEntity = new $this->typeModel();
        $eloquentParams = $dummyEntity->eloquentParams;
        // dump($dummyEntity);
        $result = [];
        foreach ($relationships as $name => $relationship) {
            if ($relationship['relationship'] == 'morphTo') {
                $name = substr($name, 1); //remove first "_"
                $eloquentParam = $eloquentParams[$name];
                $result[$name]['parent_type'] = $eloquentParam[2];
                $result[$name]['parent_id'] = $eloquentParam[3];
            }
        }
        return $result;
    }

    private function applyControlListForStaticProps(&$json)
    {
        foreach ($json as &$prop) {
            $column_type = $prop['column_type'];
            if ($column_type === 'static') {
                $prop['align'] = ['value' => $prop['align'] ?? '', 'cbbDS' => ['', 'center', 'right']];
                $prop['control'] = ['value' => $prop['control'] ?? '', 'cbbDS' => JsonControls::getHeadings()];
                $prop['hidden_view_all'] = 'DO_NOT_RENDER';
            } else {
                $prop['align'] = 'DO_NOT_RENDER';
            }
        }
    }

    private function applyControlListForMorphTo(&$json)
    {
        $allMorphTo = $this->getAllMorphTo();
        // dump($allMorphTo);
        // dump($json);
        foreach ($allMorphTo as $controlName => $parentPairs) {
            $json['_' . $controlName]['control'] = [
                'value' => $json['_' . $controlName]['control'],
                'cbbDS' => ['parent_link', ''],
            ];

            $parent_type = $parentPairs['parent_type'];
            // $json['_' . $parent_type]['hidden_view_all'] = 'true';
            $json['_' . $parent_type]['control'] = [
                'value' => $json['_' . $parent_type]['control'],
                'cbbDS' => ['parent_type', ''],
            ];

            $parent_id = $parentPairs['parent_id'];
            // $json['_' . $parent_type]['hidden_view_all'] = 'true';
            $json['_' . $parent_id]['control'] = [
                'value' => $json['_' . $parent_id]['control'],
                'cbbDS' => ['parent_id', ''],
            ];
        }
    }

    protected function getDataSource()
    {
        $json = Props::getAllOf($this->type);
        $result0 = $this->makeBlankDefaultObject();
        $result1 = $this->getRenderableRelationships();
        $result = array_merge($result0, $result1);
        $this->renewColumn($json, $result, 'column_type');
        [$toBeGreen, $toBeRed] = $this->addGreenAndRedColor($result, $json);

        foreach (array_keys($toBeGreen) as $key) $json[$key]['row_color'] = "green";
        foreach (array_keys($toBeRed) as $key) $json[$key]['row_color'] = "red";
        foreach ($json as &$line) if (isset($line['column_type']) && $line['column_type'] === 'static') $line['row_color'] = "amber";

        foreach ($result as $key => $rows) {
            foreach ($rows as $column => $value) {
                //Keep values of JSON file
                if (in_array($column, ['label', 'col_span'])) continue;
                $json[$key][$column] = $value;
            }
        }

        $this->applyControlListForStaticProps($json);
        $this->applyControlListForMorphTo($json);

        $this->attachButtons($json, ['up', 'down', 'right_by_name']);
        return $json;
    }

    public function create(Request $request)
    {
        $table02 = $request->input('table02');
        $name = $table02['name'][0];
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
