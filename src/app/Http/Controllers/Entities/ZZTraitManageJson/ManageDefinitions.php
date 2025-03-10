<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Definitions;
use Illuminate\Support\Facades\Log;

class ManageDefinitions extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-definition";
    protected $routeKey = "_dfn";
    protected $jsonGetSet = Definitions::class;
    protected $storingBlackList = ['description'];

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
                'renderer' => 'read-only-text4',
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
                'description' => '(not-yet) Status where a document becomes read only',
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
                'rowDescription' => 'Hide save button (Ignore due date, set closed_at_gmt)',
            ],
            [
                'description' => '(not-yet) Status to which a new document is post-closed',
                'name' => 'post-closed',
                'rowDescription' => 'Stop rt_la_remaining from realtime',
            ],
        ];
        $dataInJson = Definitions::getAllOf($this->type);
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
