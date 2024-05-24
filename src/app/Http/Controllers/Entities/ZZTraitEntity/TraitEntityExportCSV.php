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
                case "textarea_diff":
                case "textarea_diff_draft":
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
                        $theSubObject = $dataLine->{$relationships['control_name_function']};
                        if ($theSubObject) $result[] = $theSubObject->name ?? $theSubObject->id;
                        else $result[] = "NULL";
                    }
                    break;
                case "dropdown_multi":
                case "checkbox":
                    $fn = str_replace('()', '', $column['column_name']);
                    $data = $dataLine->$fn() ?? [];
                    $dataOracy = [];
                    foreach ($data as $value) {
                        if ($value) $dataOracy[] = $value['name'];
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
                    $path = app()->pathMinio() . '/';
                    foreach ($collection as $value) {
                        $dataAttachment[] = $path . $value['url_media'];
                    }
                    $result[] = join(',', $dataAttachment);
                    break;
                case "relationship_renderer":
                    $relationships = $column['relationships'];
                    switch ($relationships['relationship']) {
                        case 'hasMany':
                        case 'morphMany':
                            $collection = $dataLine->{$relationships['control_name_function']};
                            $renderer_view_all = $relationships['renderer_view_all'];
                            $renderer_view_all_param = $relationships['renderer_view_all_param'];
                            $renderer_view_all_unit = $relationships['renderer_view_all_unit'];
                            $tag = "x-renderer.$renderer_view_all";
                            $slot =  json_encode($collection->toArray());
                            $output = "<$tag renderRaw=1 rendererParam='$renderer_view_all_param' rendererUnit='$renderer_view_all_unit'>$slot</$tag>";
                            $result[] = Blade::render($output);

                            // switch ($relationships['renderer_view_all']) {
                            //     case 'agg_date_range':
                            //         $collection = $dataLine->{$relationships['control_name_function']};
                            //         $maxDate = $collection->max($relationships['renderer_view_all_param']);
                            //         $minDate = $collection->min($relationships['renderer_view_all_param']);
                            //         if ($maxDate === $minDate) $result[] = $maxDate;
                            //         else $result[] = $minDate . ' to ' . $maxDate;
                            //         break;
                            //     case 'agg_sum':
                            //         $result[] = $dataLine->sum($relationships['renderer_view_all_param']);
                            //     case 'agg_count':
                            //     default:
                            //         $result[] = count($dataLine[$column['column_name']]);
                            //         break;
                            // }
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
    private function getColumnsExportCSV($type)
    {
        $props = SuperProps::getFor($type)['props'];
        return $props = array_filter($props, function ($prop) {
            return !$prop['hidden_view_all'] && $prop['column_type'] !== 'static' && $prop['control'] !== 'thumbnail';
        });
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
    private function normalizeDataSourceAndColumnsByAdvanceFilter()
    {
        [,, $advanceFilters] = $this->getUserSettingsViewAll();
        $type = Str::plural($this->type);
        $columns = $this->getColumnsExportCSV($type);
        $columns = $this->makeNoColumn($columns);
        $dataSource = $this->getDataSource($advanceFilters)->get();
        return [$columns, $dataSource];
    }
    private function groupByDataSource($request, $dataSource)
    {
        if ($request->groupBy) {
            $result = [];
            foreach ($dataSource as $key => $item) {
                $groupKey = substr($item[$request->groupBy], 0, $request->groupByLength);
                if (!isset($result[$groupKey])) {
                    $result[$groupKey] = [];
                }
                $result[$groupKey][$key] = $item;
            }
            return $result;
        }
        return $dataSource;
    }
    private function makeDataSourceForViewMatrix($request, $dataSource)
    {
        $rows = [];
        if (!$request->groupBy) $dataSource = [$dataSource];
        foreach ($dataSource as $key => $value) {
            if (gettype($key) !== 'integer') $rows[] = [$key];
            foreach ($value as $no => $item) {
                if (isset($item['name_for_group_by'])) unset($item['name_for_group_by']);
                $item = array_values(array_map(fn ($item) => isset($item->value) ? strip_tags($item->value) : strip_tags($item), $item));
                array_unshift($item, ($no + 1));
                $rows[] = $item;
            }
        }
        return $rows;
    }
    private function sortDataValueByColumns($columns, $dataSource)
    {
        $columns = array_column($columns, 'dataIndex');
        $result = [];
        foreach ($dataSource as $item) {
            $arrayTemp = [];
            foreach ($columns as $key) {
                if (isset($item[$key])) $arrayTemp[$key] = $item[$key];
            }
            $result[] = $arrayTemp;
        }
        return $result;
    }
    private function getModelPath()
    {
        $namespace = 'App\View\Components\Renderer\ViewAllMatrixType';
        return $namespace . '\\' . Str::of(Str::plural($this->type))->studly();
    }
}
