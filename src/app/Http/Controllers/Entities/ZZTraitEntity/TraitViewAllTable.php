<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Relationships;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllTable
{
    private function createObject($prop, $type)
    {
        $output = [
            'title' => $prop['label'],
            'dataIndex' => $prop['column_name'],
        ];
        $output['width'] = (isset($prop['width']) && $prop['width']) ? $prop['width'] : 100;

        switch ($prop['control']) {
            case 'id':
                $output['renderer'] = 'id';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['fixed'] = 'left';
                break;
            case 'qr_code':
                $output['renderer'] = 'qr-code';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 40;
                $output['fixed'] = 'left';
                break;
            case 'avatar':
                $output['renderer'] = 'avatar_user';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 10;
                break;
            case 'action_column':
                $output['renderer'] = 'action_column';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 70;
                $output['fixed'] = 'left';
                break;
            case 'restore_column':
                $output['renderer'] = 'restore_column';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 10;
                $output['fixed'] = 'left';
                break;
            case 'checkbox_column':
                $output['renderer'] = 'checkbox_column';
                $output['align'] = 'center';
                $output['type'] = $type;
                $output['width'] = 30;
                $output['fixed'] = 'left';
                break;
            case 'toggle':
                $output['renderer'] = "toggle";
                $output['align'] = "center";
                break;
            case "number":
                $output['align'] = "right";
                break;
            case "picker_time":
            case "picker_month":
            case "picker_date":
            case "picker_week":
            case "picker_quarter":
            case "picker_year":
            case "picker_datetime":
                $output['renderer'] = "date-time";
                $output['align'] = "center";
                $output['rendererParam'] = $prop['control'];
                break;
            case "text":
            case "textarea":
            case "textarea_diff":
            case "textarea_diff_draft":
                $output['renderer'] = "text";
                break;
            case "status":
                $output['renderer'] = "status";
                $output['align'] = 'center';
                break;
            case "thumbnail":
                $output['renderer'] = "thumbnail";
                break;
            case "hyperlink":
                $output['renderer'] = "hyper-link";
                $output['align'] = 'center';
                break;
            case "doc_id":
                $output['renderer'] = 'doc-id';
                $output['align'] = 'center';
                // $output['attributes']['id'] = 'id';
                $output['attributes']['type'] = 'type';
                break;
            default:
                break;
        }

        $frozenLeft = ($prop["frozen_left"] ?? false) ? "left" : false;
        $frozenRight = ($prop["frozen_right"] ?? false) ? "right" : false;
        $nowrap = ($prop["nowrap_view_all"] ?? false);

        if ($frozenLeft) $output['fixed'] = 'left';
        if ($frozenRight) $output['fixed'] = 'right';
        if ($nowrap) $output['nowrap'] = true;
        return $output;
    }

    private function getColumns($type, $columnLimit, $trash = false)
    {
        $props = SuperProps::getFor($type)['props'];
        $props = array_filter($props, fn($prop) => !$prop['hidden_view_all']);
        $props = array_filter($props, fn($prop) => $prop['column_type'] !== 'static_heading');
        $props = array_filter($props, fn($prop) => $prop['column_type'] !== 'static_control');
        if ($columnLimit) {
            $allows = array_keys($columnLimit);
            $props = array_filter($props, fn($prop) => in_array($prop['name'], $allows));
        }
        $qrCodeColumn = [
            'label' => "Print",
            'column_name' => 'id',
            'control' => 'qr_code',
        ];
        array_splice($props, 1, 0, [$qrCodeColumn]);
        if (app()->present()) {
            $trashInfoColumn = $trash ? [
                [
                    'label' => "Deleted By",
                    'column_name' => 'deleted_by',
                    'control' => 'avatar',
                    'frozen_left' => true,
                ],
                [
                    'label' => "Deleted At",
                    'column_name' => 'deleted_at',
                    'control' => 'picker_datetime',
                    'frozen_left' => true,
                    'width' => 170,
                ]
            ] : [];
            $actionColumn = $trash ? [
                'label' => "Action",
                'column_name' => 'id',
                'control' => 'restore_column',
            ] : [
                'label' => "Action",
                'column_name' => 'id',
                'control' => 'action_column',
            ];

            $checkboxColumn = [
                'label' => "",
                'column_name' => 'id',
                'control' => 'checkbox_column',
            ];
            array_splice($props, 0, 0, [$checkboxColumn, $actionColumn, ...$trashInfoColumn]);
        }
        $result = array_values(array_map(fn($prop) => $this->createObject($prop, $type), $props));
        return $result;
    }

    private function attachRendererIntoColumn(&$columns)
    {
        // Log::info($columns);
        // Log::info($rawDataSource);
        $eloquentParams = $this->typeModel::$eloquentParams;
        // $oracyParams = $this->typeModel::$oracyParams;
        // Log::info($eloquentParams);

        $json = Relationships::getAllOf($this->type);
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                $dataIndex = $column['dataIndex'];
                if (!isset($eloquentParams[$dataIndex])) continue; //<<Id, Name, Slug...
                // if (!isset($eloquentParams[$dataIndex]) && !isset($oracyParams[$dataIndex])) continue; //<<Id, Name, Slug...

                if (isset($eloquentParams[$dataIndex])) {
                    $relationship = $eloquentParams[$dataIndex][0];
                    $allows = JsonControls::getViewAllEloquents();
                    // } elseif (isset($oracyParams[$dataIndex])) {
                    //     $relationship = $oracyParams[$dataIndex][0];
                    //     $allows = JsonControls::getViewAllOracies();
                }
                if (in_array($relationship, $allows)) {
                    // dd($json, $dataIndex, $json["_{$dataIndex}"]);
                    if (!isset($json["_{$dataIndex}"])) {
                        throw new \Exception("Please create [$dataIndex] in Relationships View");
                    } else {
                        $relationshipJson = $json["_{$dataIndex}"];
                        // dump($relationshipJson);
                        $column['renderer'] = $relationshipJson['renderer_view_all'] ?? "";
                        $column['rendererParam'] = $relationshipJson['renderer_view_all_param'] ?? "";
                        $column['rendererUnit'] = $relationshipJson['renderer_view_all_unit'] ?? "";
                    }
                }
            }
        }
    }

    private function attachEloquentNameIntoColumn(&$columns)
    {
        $eloquentParams = $this->typeModel::$eloquentParams;
        $eloquent = [];
        foreach ($eloquentParams as $key => $eloquentParam) {
            if (in_array($eloquentParam[0], ['belongsTo'])) {
                $eloquent[$eloquentParam[2]] = $key;
            }
        }
        // Log::info($eloquent);
        // Log::info($columns);

        $keys = array_keys($eloquent);
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                if (in_array($column['dataIndex'], $keys)) {
                    $column['dataIndex'] = $eloquent[$column['dataIndex']];
                }
            }
        }
    }

    private function getTabPane($advanceFilters)
    {
        $currentStatus = isset($advanceFilters['status']) ? $advanceFilters['status'] : '';
        $dataTaxonomy = [];
        switch (JsonControls::getViewAllTabTaxonomy()) {
            case 'status':
                $dataTaxonomy = LibStatuses::getFor($this->type);
                break;
            default:
                break;
        }
        $tableName = Str::plural($this->type);
        $action = "updateValueAdvanceFilter";
        $dataSource = [
            'all' => [
                'href' => "?action=$action&_entity=" . $tableName . "&status%5B%5D=&",
                'title' => "<x-renderer.tag>All</x-renderer.tag>",
                'active' => true,
            ]
        ];
        if (!($this->typeModel)::isStatusless()) {
            foreach ($dataTaxonomy as $key => $value) {
                $isActive = ($currentStatus && count($currentStatus) == 1) && ($currentStatus[0] == $key);
                if ($isActive) $dataSource['all']['active'] = false;
                $dataSource[$key] = [
                    'href' => "?action=$action&_entity=" . $tableName . "&status%5B%5D=$key",
                    'title' => "<x-renderer.status>" . $key . "</x-renderer.status>",
                    'active' => $isActive,
                ];
            }
        }
        return $dataSource;
    }
}
