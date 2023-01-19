<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\UnitTests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageUnitTests
{
    private function getColumnsUnitTest()
    {
        $columns = [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "move_to",
                "align" => "center",
                "renderer" => "text",
                "editable" => true,
                "width" => 10,
            ],
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'column_name',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'text_value',
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'number_value',
                'renderer' => 'number',
                'editable' => true,
            ],
            [
                "dataIndex" => 'checkbox',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                "dataIndex" => 'dropdown',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => ['', 'value_1', 'value_2', 'value_3'],
            ],
        ];
        return $columns;
    }

    private function getDataSourceUnitTest()
    {
        $dataInJson = UnitTests::getAllOf($this->type);
        foreach ($dataInJson as $key => $columns) {
            // if (isset($columns['row_color']) && $columns['row_color'] === "green") continue;
            $this->attachActionButtons($dataInJson, $key, ['up', 'down', 'right_by_name']);
        }
        return $dataInJson;
    }

    public function indexUnitTest(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-unit-test", '_unt');
    }

    public function storeUnitTest(Request $request)
    {
        return $this->storeObj($request, UnitTests::class, '_unt');
    }

    public function createUnitTest(Request $request)
    {
        return $this->createObj($request, UnitTests::class);
    }
}
