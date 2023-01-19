<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\DBTable;
use App\Utils\Support\Listeners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

trait TraitManageListeners
{
    private function getColumnsListener()
    {
        $columns = DBTable::getColumnNames($this->type);
        $columns = array_merge([""], $columns);
        // dump($columns);
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
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => $columns,
                "properties" => ["strFn" => 'same'],
            ],
            [
                "dataIndex" => "listen_action",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ['', 'reduce', 'assign', 'dot', 'aggregate', 'expression', 'date_offset', 'number_to_words'],
                "properties" => ["strFn" => 'same'],
            ],
            [
                "dataIndex" => "triggers",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_fields",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_attrs",
                "renderer" => "text",
                "editable" => true,
            ],

        ];
    }

    private function getDataSourceListener()
    {
        $dataSource = Listeners::getAllOf($this->type);
        foreach (array_keys($dataSource) as $key) {
            $this->attachActionButtons($dataSource, $key, ['right_by_name']);
        }
        return $dataSource;
    }

    public function indexListener(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-listener", '_ltn');
    }

    public function storeListener(Request $request)
    {
        return $this->storeObj($request, Listeners::class, '_ltn');
    }

    public function createListener(Request $request)
    {
        return $this->createObj($request, Listeners::class);
    }
}
