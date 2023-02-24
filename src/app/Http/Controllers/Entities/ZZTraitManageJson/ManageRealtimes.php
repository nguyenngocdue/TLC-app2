<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\JsonControls;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\Realtimes;

class ManageRealtimes extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-realtime";
    protected $routeKey = "_rtm";
    protected $jsonGetSet = Realtimes::class;

    protected function getColumns()
    {
        $controls = JsonControls::getControls();
        return [
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
                "dataIndex" => "label",
                "renderer" => "text",
                // "editable" => true,
            ],
            [
                "dataIndex" => "realtime_type",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ["", "leaving_new", "never_freeze"],
            ],
            [
                "dataIndex" => "realtime_fn",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => [
                    '',
                    'All_Timestamp',
                    'OTRL_RemainingHours',
                    'LA_RemainingDays(not-yet)',
                ],
                "properties" => ['strFn' => 'same'],
            ],
        ];
    }

    public function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = Realtimes::getAllOf($this->type);
        $result = [];
        foreach ($allProps as $prop) {
            if ($prop['control'] !== 'realtime') continue;
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = [
                    'name' => $name,
                    'column_name' => $prop['column_name'],
                ];
            }
            $newItem['label'] = $prop['label'];
            if (isset($prop['column_type']) && $prop['column_type'] === 'static') $newItem['row_color'] = "amber";
            $result[] = $newItem;
        }
        return $result;
    }
}
