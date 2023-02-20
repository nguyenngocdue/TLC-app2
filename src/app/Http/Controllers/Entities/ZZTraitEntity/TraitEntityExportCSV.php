<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\SuperProps;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

trait TraitEntityExportCSV
{
    private function makeRowData($columns, $dataLine, $no)
    {
        foreach (array_values($columns) as $column) {
            switch ($column['control']) {
                case 'no.':
                    $result[] = $no;
                    break;
                case 'id':
                case "number":
                case "text":
                case "textarea":
                case "status":
                case "hyperlink":
                    $result[] = $dataLine->{$column['column_name']};
                    break;
                case 'toggle':
                    $result[] = $dataLine->{$column['column_name']} == '1' ? 'Yes' : 'No';
                    break;
                case "picker_datetime":
                case "picker_date":
                case "picker_time":
                case "picker_month":
                case "picker_week":
                case "picker_quarter":
                case "picker_year":
                case "picker_datetime":
                    $dataTime = new DateTime($dataLine->{$column['column_name']});
                    $result[] = ($dataTime->getTimestamp() / 86400) + 25569;
                    break;
                case "thumbnail":
                    break;
                case "dropdown":
                case "radio":
                    $relationships = $column['relationships'];
                    if ($relationships['relationship'] == 'belongsTo') {
                        $result[] = $dataLine
                            ->{$relationships['control_name_function']}->name;
                    }
                    break;
                case "dropdown_multi":
                case "checkbox":
                    $fn = str_replace('()', '', $column['column_name']);
                    $data = $dataLine->$fn() ?? [];
                    $dataOracy = [];
                    foreach ($data as $value) {
                        $dataOracy[] = $value['name'];
                    }
                    $result[] = join(',', $dataOracy);
                    break;

                case "parent_link":
                    $relationships = $column['relationships'];
                    $model = $dataLine
                        ->{$relationships['control_name_function']};
                    $href = '';
                    if (isset($model)) {
                        $table = $model->getTable();
                        $id = $model['id'];
                        $href = route($table . ".show", $id);
                    }
                    $result[] = $href;
                    break;
                case "attachment":
                    $relationships = $column['relationships'];
                    $collection = $dataLine
                        ->{$relationships['control_name_function']};
                    $dataAttachment = [];
                    $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
                    foreach ($collection as $value) {
                        $dataAttachment[] = $path . $value['url_media'];
                    }
                    $result[] = join(',', $dataAttachment);
                    break;
                case "relationship_renderer":
                    switch ($column['relationships']['relationship']) {
                        case 'hasMany':
                        case 'morphMany':
                            $result[] = count($dataLine[$column['column_name']]);
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        }
        return $result;
    }
    private function makeNoColumn($columns)
    {
        $columnNo = [
            "label" => "No.",
            "control" => "no.",
        ];
        array_unshift($columns, $columnNo);
        return $columns;
    }
}
