<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageSettings
{
    private function getColumnsSetting()
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

    private function getDataSourceSetting()
    {
        // $allStatuses = LibStatuses::getFor($this->type);
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

    public function indexSetting(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-setting", '_stn');
    }

    public function storeSetting(Request $request)
    {
        return $this->storeObj($request, Settings::class, '_stn', ['description']);
    }
}
