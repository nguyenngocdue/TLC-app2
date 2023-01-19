<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Settings;
use Illuminate\Support\Facades\Log;

class ManageSettings extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-setting";
    protected $routeKey = "_stn";
    protected $jsonGetSet = Settings::class;
    protected $excludedColumnsFromStoring = ['description'];

    protected function getColumns()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $firstColumns = [
            [
                'dataIndex' => 'description',
                'renderer' => 'text',
            ],
            [
                'dataIndex' => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => 'center',
            ],
        ];
        $columns = array_map(fn ($i) => [
            'dataIndex' => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'title' => $i['title'],
        ], $allStatuses);
        return array_merge($firstColumns, $columns);
    }

    protected function getDataSource()
    {
        $settings = [
            [
                'description' => 'Status to which a new document is set',
                'name' => 'new',
            ],
            [
                'description' => 'Status where a document becomes read only',
                'name' => 'read-only',
                'rowDescription' => 'Hide Save Button and all Submit Buttons',
            ],
            [
                'description' => 'Status where a document hide Save Buttons',
                'name' => 'hide-save-btn',
            ],
            [
                'description' => 'Status to which a new document is closed',
                'name' => 'closed',
                'rowDescription' => 'Ignore due date, set closed_date_gmt',
            ],
            [
                'description' => 'Status to which a new document is post_closed',
                'name' => 'post_closed',
                'rowDescription' => 'Stop rt_la_remaining from realtime',
            ],
        ];
        $dataInJson = Settings::getAllOf($this->type);
        $result = [];
        foreach ($settings as $setting) {
            $name = $setting['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name,];
            }
            $newItem['description'] = $setting['description'];
            if (isset($setting['rowDescription'])) $newItem['rowDescription'] = $setting['rowDescription'];

            $result[] = $newItem;
        }
        return $result;
    }
}
